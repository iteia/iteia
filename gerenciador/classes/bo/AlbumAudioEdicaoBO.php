<?php
include_once("ConteudoBO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/AlbumAudioVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/AudioDAO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/UsuarioDAO.php");

class AlbumAudioEdicaoBO extends ConteudoBO {

	private $auddao = null;

	public function __construct() {
		$this->auddao = new AudioDAO;
        $this->usrdao = new UsuarioDAO;
		parent::__construct();
	}

	protected function setDadosForm(&$dadosform) {
	    $this->dadosform = $dadosform;
		$this->dadosform["codaudio"] = (int)$this->dadosform["codaudio"];
		$this->dadosform['imgtemp'] = trim($this->dadosform['imgtemp']);
		$this->dadosform["titulo"] = substr(trim($this->dadosform["titulo"]), 0, 100);
		$this->dadosform["descricao"] = substr(trim($this->dadosform["descricao"]), 0, 2000);
		$this->dadosform["codclassificacao"] = (int)$this->dadosform["codclassificacao"];
		$this->dadosform["codsegmento"] = (int)$this->dadosform["codsegmento"];
		$this->dadosform["codcanal"] = (int)$this->dadosform["codcanal"];
		$this->dadosform["tags"] = $this->dadosform["tags"];
		$this->dadosform["permitir_comentarios"] = (int)$this->dadosform["permitir_comentarios"];

		$this->dadosform["pertence_voce"] = (int)$this->dadosform["pertence_voce"];
		$this->dadosform["codcolaborador"] = (int)$this->dadosform["codcolaborador"];

		$this->dadosform["pedir_autorizacao"] = (int)$this->dadosform["pedir_autorizacao"];
		$this->dadosform["colaboradores_lista"] = strip_tags(trim($this->dadosform["colaboradores_lista"]));

		$this->dadosform["sessao_id"] = trim($this->dadosform["sessao_id"]);

		$this->dadosform["contsegmento"] = (int)$this->dadosform["contsegmento"];
		$this->dadosform["contsubarea"] = (int)$this->dadosform["contsubarea"];
		$this->dadosform["codsubarea"] = (int)$this->dadosform["codsubarea"];
    }

	protected function validaDados() {
		if (!$this->dadosform["titulo"]) $this->erro_campos[] = "titulo";

		if (!$this->dadosform["descricao"]) $this->erro_campos[] = "descricao";

		if (!count($_SESSION["sess_conteudo_audios_album"][$this->dadosform["sessao_id"]])) {
			$this->erro_mensagens[] = "Adicione ao menos um arquivo MP3";
			$this->erro_campos[] = "audio";
		}

		if ($this->dadosform["contsegmento"] && !$this->dadosform["codsegmento"]) $this->erro_campos[] = "codsegmento";
		if (!count($_SESSION['sess_conteudo_autores_ficha'][$this->dadosform['sessao_id']])) {
			if ($this->dadosform['pertence_voce'] == 1)
				$this->erro_mensagens[] = "Na Ficha Técnica selecione a atividade exercida por você e clique em [+]Adicionar";
			else
				$this->erro_mensagens[] = "Selecione ao menos um autor na Ficha técnica";
			$this->erro_campos[] = "ficha";
		}

		if ($_SESSION['logado_dados']['nivel'] == 2) {
			if (!$this->dadosform["pedir_autorizacao"]) {
				$this->erro_mensagens[] = "Selecione um tipo de Autorização";
				$this->erro_campos[] = "autorizacao";
			}
			if ($this->dadosform["pedir_autorizacao"] == 2) {
				if (!count($this->usrdao->getCheckColabadoresAprovacao($this->dadosform["colaboradores_lista"]))) {
					$this->erro_mensagens[] = "Selecione ao menos um colaborador para solicitar aprovação";
					$this->erro_campos[] = "colaboradores_lista";
				}
			}
		}else{
			$this->validaColaborador();
		}
		
		if (is_uploaded_file($this->arquivos["imagem"]["tmp_name"])) {
			if ($this->arquivos["imagem"]["size"] > 2200000) {
				$this->erro_mensagens[] = "Foto está com mais de 2MB";
				$this->erro_campos[] = "imagem";
			}
			$extensao = strtolower(Util::getExtensaoArquivo($this->arquivos["imagem"]["name"]));
			if (($extensao != 'jpg') && ($extensao != 'jpeg') && ($extensao != 'gif') && ($extensao != 'png')) {
				$this->erro_mensagens[] = "Apenas enviar foto no formato jpg, gif ou png";
				$this->erro_campos[] = "imagem";
			}
		}

		$erros_direitos = $this->direitosbo->validaDados($this->dadosform);
		foreach ($erros_direitos as $errodir)
			$this->erro_mensagens[] = $errodir;

		if (count($this->erro_mensagens) || count($this->erro_campos))
			throw new Exception(implode("<br />\n", $this->erro_mensagens));
    }

	protected function setDadosVO() {
	    $this->audvo = new AlbumAudioVO;
	    $this->audvo->setCodConteudo($this->dadosform["codaudio"]);
		$this->audvo->setCodAutor($_SESSION['logado_cod']);
		
		$this->audvo->setCodColaborador(($this->dadosform['colaborador_aprov'] ? $this->dadosform['colaborador_aprov'] : 0));
        
		$this->audvo->setCodClassificacao($this->dadosform['codclassificacao']);
		$this->audvo->setCodSegmento($this->dadosform['codsegmento']);
		$this->audvo->setCodSubArea($this->dadosform['codsubarea']);
		$this->audvo->setCodCanal($this->dadosform['codcanal']);
		$this->audvo->setTags(Util::geraTags($this->dadosform['tags']));
		$this->audvo->setCodLicenca($this->direitosbo->getCodLicenca($this->dadosform["direitos"], $this->dadosform["cc_usocomercial"], $this->dadosform["cc_obraderivada"]));

		if (!$this->dadosform["codaudio"])
			$this->audvo->setRandomico(Util::geraRandomico());
		else
		  $this->audvo->setRandomico($this->auddao->getRandomico($this->dadosform["codaudio"]));

		$this->audvo->setTitulo($this->dadosform["titulo"]);
		$this->audvo->setDescricao($this->dadosform["descricao"]);
		$this->audvo->setListaAudios($_SESSION["sess_conteudo_audios_album"][$this->dadosform["sessao_id"]]);

		if (!$this->dadosform["codaudio"]) {
			$this->audvo->setDataHora(date("Y-m-d H:i:s"));
			$this->audvo->setSituacao(1);
			$this->audvo->setPublicado(($_SESSION['logado_dados']['nivel'] >= 5 ? 1 : 0));
			//$this->audvo->setSituacao(($_SESSION['logado_dados']['colaborador_responsavel'] == 1 ? 1 : 0));
			//$this->audvo->setPublicado(($_SESSION['logado_dados']['colaborador_responsavel'] == 1 ? 1 : 0));
		}

		$colaboradores = explode(';', $this->dadosform["colaboradores_lista"]);
		if (count($colaboradores)) {
			$arrayColab = array();
			foreach ($colaboradores as $nome) {
				if ($nome) {
					$usuario = $this->usrdao->getBuscaDadosUsuarioNome($nome, 1);
					$arrayColab[$usuario['cod_usuario']] = $usuario['cod_usuario'];
				}
			}
			$this->audvo->setListaColaboradoresRevisao(array_unique($arrayColab));
		}

		if($_SESSION['logado_dados']['nivel'] == 2){
			$this->dadosform["pedir_autorizacao"] = 2;
		}
        
		$this->audvo->setPedirAutorizacao($this->dadosform["pedir_autorizacao"]);
		$this->audvo->setListaAutores($_SESSION["sess_conteudo_autores_ficha"][$this->dadosform["sessao_id"]]);
		$this->audvo->setUrl(Util::geraUrlTitulo($this->dadosform["titulo"]));
		$this->audvo->setPermitirComentarios($this->dadosform['permitir_comentarios']);
	}

    protected function editarDados() {
        if (!$this->audvo->getCodConteudo()) {
            $codaudio = $this->auddao->cadastrarAlbum($this->audvo);
		} else {
		    $this->auddao->atualizarAlbum($this->audvo);
			$codaudio = $this->audvo->getCodConteudo();
		}

		if ($this->dadosform['imgtemp']) {
			include_once('ImagemTemporariaBO.php');
			$nomearquivo_parcial = "imgaudio_".$codaudio;
			$nomearquivo_final = ImagemTemporariaBO::criaDefinitiva($this->dadosform['imgtemp'], $nomearquivo_parcial, ConfigVO::getDirFotos());
			$this->removerImagensCache($nomearquivo_parcial);
			$this->auddao->atualizarFoto($nomearquivo_final, $codaudio);
		}

		unset($_SESSION["sess_conteudo_imagens_album"][$this->dadosform["sessao_id"]]);
		unset($_SESSION['sess_conteudo_autores_ficha'][$this->dadosform["sessao_id"]]);
		$this->dadosform = array();
		$this->arquivos = array();
		return $codaudio;
    }

	public function setDadosCamposEdicao($codaudio) {
        $audvo = $this->auddao->getAudioVO($codaudio);

		$this->dadosform["codaudio"] = $this->dadosform["codconteudo"] = $audvo->getCodConteudo();
		$this->dadosform["codcolaborador"] = $audvo->getCodColaborador();
		$this->dadosform["codautor"] = $audvo->getCodAutor();
		$this->dadosform["titulo"] = $audvo->getTitulo();
		$this->dadosform["descricao"] = $audvo->getDescricao();
		$this->dadosform["imagem_visualizacao"] = $audvo->getImagem();
		$_SESSION["sess_conteudo_audios_album"][$this->dadosform["sessao_id"]] = $audvo->getListaAudios();

		$dados_direito = $this->direitosbo->setDadosCamposEdicao($audvo->getCodLicenca());
		$this->dadosform = array_merge($this->dadosform, $dados_direito);

		$this->dadosform["codlicenca"] = $audvo->getCodLicenca();

		$this->dadosform["codclassificacao"] = $audvo->getCodClassificacao();
		$this->dadosform["codsegmento"] = $audvo->getCodSegmento();
		$this->dadosform["codsubarea"] = $audvo->getCodSubArea();
		$this->dadosform["codcanal"] = $audvo->getCodCanal();
		$this->dadosform["tags"] = $audvo->getTags();

		$this->dadosform["url"] = $audvo->getUrl();
		$this->dadosform["datahora"] = $audvo->getDataHora();
		$this->dadosform["situacao"] = $audvo->getSituacao();
		$this->dadosform["publicado"] = $audvo->getPublicado();
		$this->dadosform["permitir_comentarios"] = $audvo->getPermitirComentarios();

		$this->setSessionAutoresFicha($codaudio, $this->auddao, $this->dadosform["sessao_id"]);

		foreach ((array)$_SESSION['sess_conteudo_autores_ficha'][$this->dadosform["sessao_id"]] as $key => $value) {
			if ($this->dadosform["codautor"] == $value['codautor']) {
				$this->dadosform["pertence_voce"] = 1;
				break;
			}
		}
    }

    // metodos comuns a todo os formatos
    public function getColaboradorConteudoAprovado($codusuario) {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/UsuarioDAO.php");
		$usrdao = new UsuarioDAO;
		$usuario = $usrdao->getUsuarioDados($codusuario);
		return $usuario['nome'];
	}

	public function getAudiosAlbum($codaudio) {
		return $this->auddao->getAudiosAlbum($codaudio);
	}

	public function excluirImagem($codaudio) {
		return $this->auddao->excluirImagem($codaudio);
	}

	public function getPostadorConteudo($codusuario) {
		return $this->getColaboradorConteudo($codusuario);
	}

	public function getAutoresFichaConteudo($codaudio) {
		return $this->auddao->getAutoresFichaTecnicaCompletaConteudo($codaudio);
	}

	public function getAutoresConteudo($codaudio) {
		return $this->auddao->getAutoresConteudo($codaudio);
	}

	public function getColaboradorConteudo($codusuario) {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz().'dao/UsuarioDAO.php');
		$usrdao = new UsuarioDAO;
		$usuario = $usrdao->getUsuarioDados($codusuario);
		return $usuario;
	}

	public function getSegmentoConteudo($codaudio) {
		return $this->auddao->getSegmentoConteudo($codaudio);
	}

	public function getSubAreaConteudo($codaudio) {
		return $this->auddao->getSubAreaConteudo($codaudio);
	}

	public function getCategoriaConteudo($codaudio) {
		return $this->auddao->getCategoriaConteudo($codaudio);
	}

	public function getConteudoRelacionado($codaudio) {
		return $this->auddao->getConteudoRelacionadoConteudo($codaudio);
	}

	public function getGrupoRelacionado($codaudio) {
		return $this->auddao->getGrupoRelacionadoConteudo($codaudio);
	}

	public function getLicenca($codaudio) {
		return $this->auddao->getLicenca($codaudio);
	}

	public function getListaColaboradoresAprovacao($codaudio) {
		return $this->auddao->getListaColaboradoresAprovacao($codaudio);
	}

}