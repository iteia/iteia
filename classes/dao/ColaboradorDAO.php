<?php
include_once("UsuarioDAO.php");

class ColaboradorDAO extends UsuarioDAO {
	
	public function cadastrar(&$usuariovo) {
	    $codusuario = $this->cadastrarUsuario($usuariovo);
	    if ($codusuario) {
	    	sort($usuariovo->getRede());
            $this->banco->sql_insert('Colaboradores', array('cod_usuario' => $codusuario, 'entidade' => $usuariovo->getEntidade(), 'rede' => implode(',', $usuariovo->getRede()), 'administrador' => $usuariovo->getAdministrador()));
        
        	// errado
			//$sql = "INSERT INTO Colaboradores_Autores (cod_autor, cod_colaborador, datacadastro, excluido) values ('".$codusuario."', '".$_SESSION['logado_cod']."', NOW(), 0);";
			//$sql_result = $this->banco->executaQuery($sql);
			$dataHora=date("Y-m-d H:m:i");
/*$sql_auto="INSERT INTO Conteudo_Notificacoes (cod_notificacao, cod_tipo, cod_conteudo, cod_autor, cod_colaborador, cod_grupo, comentario, data_cadastro) VALUES ('cod_notificacao', 2, 0, 'cod_autor', '$usuariovo', 'cod_grupo', 'comentario', '$dataHora');";*/
     /*       $this->banco->sql_insert('Conteudo_Notificacoes', array('cod_tipo' => '2', 'cod_autor' => '0', 'cod_colaborador' => $codusuario, 'data_cadastro' => $dataHora));*/
          // $this->banco->executaQuery($sql_auto);
		    
			$this->atualizarUsuariosNiveis($usuariovo, $codusuario);
			$this->atualizarIntegrantes($usuariovo, $codusuario);
			$this->banco->sql_insert('Usuarios_Estatisticas', array('cod_usuario' => $codusuario));
		}
        return $codusuario;
    }
    
    public function atualizar(&$usuariovo) {
	    $codusuario = $this->editarUsuario($usuariovo);
	    if ($codusuario) {
	    	sort($usuariovo->getRede());
            $this->banco->sql_update('Colaboradores', array('entidade' => $usuariovo->getEntidade(), 'rede' => implode(',', $usuariovo->getRede())), "cod_usuario='".$codusuario."'");
			$this->atualizarUsuariosNiveis($usuariovo, $codusuario);
			$this->atualizarIntegrantes($usuariovo, $codusuario);
		}
        return $codusuario;
    }
    
    private function atualizarIntegrantes(&$usuariovo, $codusuario) {
    	$this->banco->sql_delete('Colaboradores_Integrantes', "cod_colaborador='".$codusuario."'");
		if (count($usuariovo->getListaIntegrantes())) {
			foreach($usuariovo->getListaIntegrantes() as $key => $value) {
				$this->insereAutorIntegrante($codusuario, $value['cod_usuario'], $value['responsavel']);
			}
		}
    }
    
	public function insereAutorIntegrante($codcolaborador, $codautor, $responsavel = 0) {
		$this->banco->sql_insert('Colaboradores_Integrantes', array('cod_colaborador' => $codcolaborador, 'cod_autor' => $codautor, 'responsavel' => $responsavel));
	}
	
    private function atualizarUsuariosNiveis(&$usuariovo, $codusuario) {
    	// pego todos os intergrantes (autores) do colaborador
    	$query = $this->banco->executaQuery("SELECT cod_autor FROM Colaboradores_Integrantes WHERE cod_colaborador='".$codusuario."'");
    	$arrayId = array();
    	while ($row = $this->banco->fetchObject($query))
    		$arrayId[$row->cod_autor] = $row->cod_autor;
    		
    	foreach ($arrayId as $cod) {
    		$query = $this->banco->executaQuery("SELECT nivel FROM Usuarios_Niveis WHERE cod_usuario='".$cod."' AND nivel < 7");
    		if ($this->banco->numRows($query))
				$this->banco->executaQuery("REPLACE INTO Usuarios_Niveis VALUES ('".$cod."', 2)");
   		}
    	
		// aqui adiciono os novos autores com niveis 5 e 6
    	if (count($usuariovo->getListaIntegrantes())) {
			foreach($usuariovo->getListaIntegrantes() as $key => $value) {
				
				// não pode modificar administradores acima do 6
				$query = $this->banco->executaQuery("SELECT nivel FROM Usuarios_Niveis WHERE cod_usuario='".$value['cod_usuario']."' AND nivel < 7");
				
				if ($this->banco->numRows($query))
					$this->banco->executaQuery("REPLACE INTO Usuarios_Niveis VALUES ('".$value['cod_usuario']."', '".Util::iif($value['responsavel'], 6, 5)."')");
			}
		}
    }
    
    public function getColaboradorVO($codcolaborador) {
		$colaboradorvo = new ColaboradorVO;
		
		$this->getUsuarioVO($codcolaborador, $colaboradorvo);
		
		$sql = "SELECT t1.*, t2.titulo FROM Colaboradores AS t1 LEFT JOIN Urls AS t2 ON (t1.cod_usuario=t2.cod_item) WHERE t2.tipo='1' AND t1.cod_usuario='".$codcolaborador."'";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchObject();
		
		$colaboradorvo->setEntidade($sql_row->entidade);
		$colaboradorvo->setRede($sql_row->rede);
		$colaboradorvo->setAdministrador($sql_row->administrador);
		$colaboradorvo->setUrl($sql_row->titulo);
		
		$sql = "SELECT t1.cod_autor, t1.responsavel, t2.nome, t2.imagem, t3.* FROM Colaboradores_Integrantes AS t1 INNER JOIN Usuarios AS t2 ON (t1.cod_autor=t2.cod_usuario) LEFT JOIN Autores AS t3 ON (t2.cod_usuario=t3.cod_usuario) WHERE t1.cod_colaborador='".$codcolaborador."'";
		$sql_result = $this->banco->executaQuery($sql);
		$arrayIntegrantes = array();
		while ($sql_row = $this->banco->fetchObject()) {
			$arrayIntegrantes[] = array(
				'cod_usuario' 	=> $sql_row->cod_autor,
				'responsavel' 	=> $sql_row->responsavel,
				'nome' 			=> ($sql_row->nome ? $sql_row->nome : $sql_row->nome_completo),
				'imagem' 		=> $sql_row->imagem
			);
		}
		$colaboradorvo->setListaIntegrantes($arrayIntegrantes);
		
		return $colaboradorvo;
	}
	
	public function getComunicadoresColaborador($codcolaborador) {
		return $this->getComunicadoresUsuario($codcolaborador);
	}
	
	public function getSitesColaborador($codcolaborador) {
		return $this->getSitesUsuario($codcolaborador);
	}
	
	public function getAutoresParticipantes($codcolaborador) {
		$sql = "SELECT t1.cod_usuario, t1.nome, t3.titulo AS url, t2.responsavel FROM Usuarios AS t1 INNER JOIN Colaboradores_Integrantes AS t2 ON (t1.cod_usuario=t2.cod_autor) INNER JOIN Urls AS t3 ON (t1.cod_usuario=t3.cod_item) WHERE t2.cod_colaborador='".$codcolaborador."' AND t3.tipo='2'";
        $query1 = $this->banco->executaQuery($sql);
        $lista = array();
    	while ($row = $this->banco->fetchArray($query1))
    		$lista[] = $row;
    	return $lista;
    }
	
	public function getListaDadosColaboradores(&$lista_colaboradores) {
		$dados_colaboradores = array();
		$sql = "select t1.cod_usuario, t2.nome, t2.imagem, t3.titulo AS url, t4.sigla FROM Colaboradores AS t1 INNER JOIN Usuarios AS t2 ON (t1.cod_usuario=t2.cod_usuario) LEFT JOIN Urls AS t3 ON (t1.cod_usuario=t3.cod_item) LEFT JOIN Estados AS t4 ON (t2.cod_estado=t4.cod_estado) WHERE t2.disponivel='1' AND t1.cod_usuario IN ('".implode("', '", $lista_colaboradores)."') AND t3.tipo='1' ORDER BY t2.nome";
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result))
			$dados_colaboradores[] = $sql_row;
		return $dados_colaboradores;
	}
	
	public function addNotificacaoNovoColaboradorAprovacao($codusuario) {
    	$sql = "INSERT INTO Conteudo_Notificacoes (cod_tipo, cod_colaborador, data_cadastro) VALUES ('250', '".$codusuario."', NOW())";
		$this->banco->executaQuery($sql);
    }

	public function getListaColaboradoresPorNome($nome) {
		$array = array();

		// usuarios - nome artistico
		$where = " WHERE t1.disponivel = 1 AND t1.situacao = 3 AND t1.cod_sistema = ".ConfigVO::getCodSistema()." AND t1.cod_tipo = 1 AND t1.nome LIKE '%".utf8_decode($nome)."%'";
		$sql = "SELECT t1.cod_usuario, t1.nome FROM Usuarios AS t1". $where;
		$query = $this->banco->executaQuery($sql . " ORDER BY t1.nome ASC LIMIT 30");

		while ($row = $this->banco->fetchArray($query)) {
			$dadosusuario = $this->getUsuarioDados($row['cod_usuario']);
			$array[$row['cod_usuario']] = array(
				'cod' 		=> $row['cod_usuario'],
				'nome' 		=> $dadosusuario['nome'],
			);
		}
		
		return $array;
	}
    
}