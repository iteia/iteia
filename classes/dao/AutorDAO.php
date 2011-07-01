<?php
include_once('UsuarioDAO.php');

class AutorDAO extends UsuarioDAO {

	public function cadastrar(&$usuariovo) {
	    $codusuario = $this->cadastrarUsuario($usuariovo);
	    if ($codusuario) {
            $this->banco->sql_insert('Autores', array('cod_usuario' => $codusuario, 'nome_completo' => $usuariovo->getNomeCompleto(), 'data_nascimento' => $usuariovo->getDataNascimento(), 'data_falecimento' => $usuariovo->getDataFalecimento(), 'cpf' => $usuariovo->getCPF(), 'exibir_endereco' => $usuariovo->getExibirEndereco()));

        	if ($_SESSION['logado_dados']['nivel'] >= 5) {
				// se cadastro foi realizado por colaborador ele associa o autor a si mesmo
				$sql = "INSERT INTO Colaboradores_Autores (cod_autor, cod_colaborador, datacadastro, excluido) values ('".$codusuario."', '".$_SESSION['logado_dados']['cod_colaborador']."', NOW(), 0);";
				$sql_result = $this->banco->executaQuery($sql);

				// e atualiza sua quantidade de autores cadastrados por ele
				$this->banco->executaQuery("UPDATE Usuarios_Estatisticas SET autores = autores + 1 WHERE cod_usuario='".$_SESSION['logado_dados']['cod_colaborador']."'");
			}

			$this->banco->sql_insert('Usuarios_Niveis', array('cod_usuario' => $codusuario, 'nivel' => $usuariovo->getCodNivel()));

			$this->banco->sql_insert('Usuarios_Estatisticas', array('cod_usuario' => $codusuario));
		}
        return $codusuario;
    }

    public function atualizar(&$usuariovo) {
	    $codusuario = $this->editarUsuario($usuariovo);
	    if ($codusuario) {
            $this->banco->sql_update('Autores', array('nome_completo' => $usuariovo->getNomeCompleto(), 'data_nascimento' => $usuariovo->getDataNascimento(), 'cpf' => $usuariovo->getCPF(), 'exibir_endereco' => $usuariovo->getExibirEndereco()), "cod_usuario='".$codusuario."'");
            if ($usuariovo->getDataFalecimento()) {
            	$this->banco->sql_update('Autores', array('data_falecimento' => $usuariovo->getDataFalecimento()), "cod_usuario='".$codusuario."'");
            }
			if (!$usuariovo->getDataFalecimento() || ($usuariovo->getDataFalecimento() == '0000-00-00')) {
				$senha_atual = $this->verificarSenha($codusuario);
				if (!$senha_atual && ($usuariovo->getCodNivel() >= 2)) {
					$usuariovo->setSenha(mt_rand(1000000, 9999999));
					$this->atualizaSenha($usuariovo);
				}
			}
            if ($usuariovo->getCodNivel())
				$this->banco->sql_update('Usuarios_Niveis', array('nivel' => $usuariovo->getCodNivel()), "cod_usuario='".$codusuario."'");
			if ($usuariovo->getLogin())
				$this->banco->sql_update('Usuarios', array('login' => $usuariovo->getLogin()), "cod_usuario='".$codusuario."'");
		}
        return $codusuario;
    }
    
    public function atualizarDataCadastro(&$usuariovo) {
	    $codusuario = $usuariovo->getCodUsuario();
            $this->banco->sql_update('Usuarios', array('datacadastro' => $usuariovo->getDataCadastro()), "cod_usuario='".$codusuario."'");
    }


    public function getAutorVO($codautor) {
		$autorvo = new AutorVO;

		$this->getUsuarioVO($codautor, $autorvo);

		$sql = "SELECT t1.*, t2.titulo FROM Autores AS t1 LEFT JOIN Urls AS t2 ON (t1.cod_usuario=t2.cod_item) WHERE t2.tipo='2' AND t1.cod_usuario='".$codautor."' AND t1.cod_usuario!='0'";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchObject();

		$autorvo->setNomeCompleto($sql_row->nome_completo);
		$autorvo->setDataNascimento($sql_row->data_nascimento);
		$autorvo->setDataFalecimento($sql_row->data_falecimento);
		$autorvo->setCPF($sql_row->cpf);
		$autorvo->setUrl($sql_row->titulo);
		$autorvo->setExibirEndereco($sql_row->exibir_endereco);

		$sql = "SELECT nivel FROM Usuarios_Niveis WHERE cod_usuario='".$codautor."' AND cod_usuario!='0'";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchObject();

		$autorvo->setTipoAutor($sql_row->nivel);

		return $autorvo;
	}

	public function cadastrarUrl($codautor, $titulo) {
		$i = 0;
		$url = Util::geraUrlTitulo(substr(trim($titulo), 0, 30));
		do {
			if ($i)
				$url = $url.$i;
				$sql = "insert into Urls values ('".$url."', '".$codautor."', '2', '".ConfigVO::getCodSistema()."')";
				$tenta = $this->banco->executaQuery($sql);
				$i++;
		}
		while (!$tenta);
		return true;
	}

	public function editarUrl($codautor, $titulo) {
		$this->banco->sql_delete('Urls', "cod_item='".$codautor."' AND tipo='2' AND cod_sistema='".ConfigVO::getCodSistema()."'");
		$this->cadastrarUrl($codautor, $titulo);
	}

	public function atualizarUrlUnificacao($codautor) {
		$query = $this->banco->sql_select('titulo', 'Urls', "cod_item='".$codautor."' AND tipo='2' AND cod_sistema='".ConfigVO::getCodSistema()."'");
		$row = $this->banco->fetchArray($query);

		$this->banco->sql_update('Usuarios', array('disponivel' => 0, 'situacao' => 2), "cod_usuario='".$codautor."' AND cod_sistema='".ConfigVO::getCodSistema()."'");

		$this->banco->executaQuery("DELETE FROM Conteudo_Notificacoes WHERE cod_autor = '".$codautor."' AND cod_tipo='150'");
		$this->banco->executaQuery("DELETE FROM Urls WHERE cod_item = '".$codautor."' AND tipo='2' AND cod_sistema = '".ConfigVO::getCodSistema()."'");
	}

	public function atualizarConteudoUnificacao($codautor1, $codautor2) {
		$this->banco->sql_update('Conteudo', array('cod_autor' => $codautor1), "cod_autor='".$codautor2."'");
		$this->banco->sql_update('Conteudo_Autores', array('cod_usuario' => $codautor1), "cod_usuario='".$codautor2."'");
		$this->banco->sql_update('Conteudo_Autores_Ficha', array('cod_usuario' => $codautor1), "cod_usuario='".$codautor2."'");
	}

	public function getComunicadoresAutor($codautor) {
		return $this->getComunicadoresUsuario($codautor);
	}

	public function getSitesAutor($codautor) {
		return $this->getSitesUsuario($codautor);
	}

	public function getListaDadosAutores(&$lista_autores) {
		$dados_autores = array();
		$sql = "select t1.cod_usuario, t1.nome_completo, t2.nome, t2.imagem, t3.titulo AS url, t4.sigla FROM Autores AS t1 INNER JOIN Usuarios AS t2 ON (t1.cod_usuario=t2.cod_usuario) LEFT JOIN Urls AS t3 ON (t1.cod_usuario=t3.cod_item) LEFT JOIN Estados AS t4 ON (t2.cod_estado=t4.cod_estado) WHERE t2.disponivel='1' AND t1.cod_usuario IN ('".implode("', '", $lista_autores)."') AND t3.tipo='2' ORDER BY t2.nome";
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result))
			$dados_autores[] = $sql_row;
		return $dados_autores;
	}

	public function getColaboradoresRepresentantes($codautor) {
		$sql = "SELECT t1.cod_usuario, t1.nome, t3.titulo AS url FROM Usuarios AS t1 INNER JOIN Colaboradores_Integrantes AS t2 ON (t1.cod_usuario=t2.cod_colaborador) INNER JOIN Urls AS t3 ON (t1.cod_usuario=t3.cod_item) WHERE t2.cod_autor='".$codautor."' AND t1.situacao='3' AND t1.disponivel='1' AND t2.responsavel='1' AND t3.tipo='1'";
        $query1 = $this->banco->executaQuery($sql);
        $lista = array();
    	while ($row = $this->banco->fetchArray($query1))
    		$lista[] = $row;
    	return $lista;
    }
	
	public function getColaboradoresParticipantes($codautor) {
		$sql = "SELECT t1.cod_usuario, t1.nome, t3.titulo AS url FROM Usuarios AS t1 INNER JOIN Colaboradores_Integrantes AS t2 ON (t1.cod_usuario=t2.cod_colaborador) INNER JOIN Urls AS t3 ON (t1.cod_usuario=t3.cod_item) WHERE t2.cod_autor='".$codautor."' AND t1.situacao='3' AND t1.disponivel='1' AND t2.responsavel='0' AND t3.tipo='1'";
        $query1 = $this->banco->executaQuery($sql);
        $lista = array();
    	while ($row = $this->banco->fetchArray($query1))
    		$lista[] = $row;
    	return $lista;
    }

    public function addNotificacaoNovoAutorAprovacao($cod_autor) {
    	$sql = "INSERT INTO Conteudo_Notificacoes (cod_tipo, cod_autor, data_cadastro) VALUES ('150', '".$cod_autor."', NOW())";
		$this->banco->executaQuery($sql);
    }
	
	public function getExibirEndereco($cod_autor) {
		$query = $this->banco->executaQuery("SELECT exibir_endereco FROM Autores WHERE cod_usuario='".$cod_autor."'");
		$row = $this->banco->fetchArray($query);
		return (bool)$row['exibir_endereco'];
	}
	
	public function getListaCadastrosFicha($dadosget) {
		$array = array();
		extract($dadosget);

		// usuarios - nome artistico
		$where = " WHERE t1.disponivel = 1 AND t1.situacao = 3 AND t1.cod_sistema = ".ConfigVO::getCodSistema()." AND t1.cod_tipo = 2 AND t1.nome LIKE '%".utf8_decode($palavrachave)."%'";
		$sql = "SELECT t1.cod_usuario FROM Usuarios AS t1". $where;
		$query = $this->banco->executaQuery($sql . " ORDER BY t1.cod_usuario DESC LIMIT 30");

		while ($row = $this->banco->fetchArray($query)) {
			$dadosusuario = $this->getUsuarioDados($row['cod_usuario']);
			$array[$row['cod_usuario']] = array(
				'cod' 		=> $row['cod_usuario'],
				'nome' 		=> $dadosusuario['nome'],
				'cidade' 	=> $dadosusuario['cidade'],
				'estado' 	=> $dadosusuario['sigla'],
				'descricao'	=> strip_tags($dadosusuario['descricao'])
			);
		}
		
		// usuarios (autores) - nome completo
		$where = " WHERE t1.disponivel = 1 AND t1.situacao = 3 AND t1.cod_sistema = ".ConfigVO::getCodSistema()." AND t1.cod_tipo = 2 AND t2.nome_completo LIKE '%".utf8_decode($palavrachave)."%'";
		$sql = "SELECT t1.cod_usuario, t2.nome_completo, t1.descricao FROM Usuarios AS t1 LEFT JOIN Autores AS t2 ON (t1.cod_usuario=t2.cod_usuario)". $where;
		$query = $this->banco->executaQuery($sql . " ORDER BY t1.cod_usuario DESC LIMIT 30");

		while ($row = $this->banco->fetchArray($query)) {
			$dadosusuario = $this->getUsuarioDados($row['cod_usuario']);
			$array[$row['cod_usuario']] = array(
				'cod' 		=> $row['cod_usuario'],
				'nome' 		=> $dadosusuario['nome'],
				'cidade' 	=> $dadosusuario['cidade'],
				'estado' 	=> $dadosusuario['sigla'],
				'descricao'	=> strip_tags($dadosusuario['descricao'])
			);
		}
		
		return $array;
	}

}