<?php
include_once("ConteudoBO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."vo/TextoVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/TextoDAO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/UsuarioDAO.php");

class TextoEdicaoBO extends ConteudoBO {

	private $textovo = null;
	private $textodao = null;
	private $usrdao = null;

	public function __construct() {
		$this->textodao = new TextoDAO;
		$this->usrdao = new UsuarioDAO;
		parent::__construct();
	}

	protected function setDadosForm(&$dadosform) {
		$this->dadosform = $dadosform;
		$this->dadosform["codtexto"] = (int)$this->dadosform["codtexto"];
		$this->dadosform['imgtemp'] = trim($this->dadosform['imgtemp']);
		$this->dadosform["titulo"] = substr(trim($this->dadosform["titulo"]), 0, 100);
		$this->dadosform["descricao"] = substr(trim($this->dadosform["descricao"]), 0, 20000);
		$this->dadosform["codclassificacao"] = (int)$this->dadosform["codclassificacao"];
		$this->dadosform["codsegmento"] = (int)$this->dadosform["codsegmento"];
		$this->dadosform["codcanal"] = (int)$this->dadosform["codcanal"];

		$this->dadosform["pertence_voce"] = (int)$this->dadosform["pertence_voce"];
		$this->dadosform["codcolaborador"] = (int)$this->dadosform["codcolaborador"];

		$this->dadosform["pedir_autorizacao"] = (int)$this->dadosform["pedir_autorizacao"];
		$this->dadosform["colaboradores_lista"] = strip_tags(trim($this->dadosform["colaboradores_lista"]));

		$this->dadosform["foto_credito"] = substr(trim($this->dadosform["foto_credito"]), 0, 100);
		$this->dadosform["foto_legenda"] = substr(trim($this->dadosform["foto_legenda"]), 0, 200);

		$this->dadosform["tags"] = $this->dadosform["tags"];
		$this->dadosform["permitir_comentarios"] = (int)$this->dadosform["permitir_comentarios"];

		$this->dadosform["sessao_id"] = trim($this->dadosform["sessao_id"]);

		$this->dadosform["contsegmento"] = (int)$this->dadosform["contsegmento"];
		$this->dadosform["contsubarea"] = (int)$this->dadosform["contsubarea"];
		$this->dadosform["codsubarea"] = (int)$this->dadosform["codsubarea"];
	}

	protected function validaDados() {
		if (!$this->dadosform["titulo"]) $this->erro_campos[] = "titulo";

		if (!$this->dadosform["descricao"]) $this->erro_campos[] = "descricao";
		
		for($i=0; $i<sizeof($this->arquivos['arquivo']['name']);$i++){
			if (is_uploaded_file($this->arquivos["arquivo"]["tmp_name"][$i])) {
				if ($this->arquivos["arquivo"]["size"][$i] > 104857600) {
					$this->erro_mensagens[] = "Arquivo ".$this->arquivos['arquivo']['name'][$i]." está com mais de 100MB";
					$this->erro_campos[] = "arquivo";
				}
			}			
		}

		if ($this->dadosform["contsegmento"] && !$this->dadosform["codsegmento"]) $this->erro_campos[] = "codsegmento";
		if (!count($_SESSION['sess_conteudo_autores_ficha'][$this->dadosform['sessao_id']])) {
			if ($this->dadosform['pertence_voce'] == 1 || $_SESSION['logado_dados']['nivel'] == 2)
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

		foreach ($this->direitosbo->validaDados($this->dadosform) as $errodir)
			$this->erro_mensagens[] = $errodir;

		if (count($this->erro_mensagens) || count($this->erro_campos))
			throw new Exception(implode("<br />\n", $this->erro_mensagens));
	}

	protected function setDadosVO() {
		$this->textovo = new TextoVO;
		$this->textovo->setCodConteudo($this->dadosform["codtexto"]);
		$this->textovo->setCodAutor($_SESSION['logado_cod']);
		
        $this->textovo->setCodColaborador(($this->dadosform['colaborador_aprov'] ? $this->dadosform['colaborador_aprov'] : 0));
        
		$this->textovo->setCodClassificacao($this->dadosform['codclassificacao']);
		$this->textovo->setCodSegmento($this->dadosform['codsegmento']);
		$this->textovo->setCodSubArea($this->dadosform['codsubarea']);
		$this->textovo->setCodCanal($this->dadosform['codcanal']);
		$this->textovo->setTags(Util::geraTags($this->dadosform['tags']));
		$this->textovo->setCodLicenca($this->direitosbo->getCodLicenca($this->dadosform["direitos"], $this->dadosform["cc_usocomercial"], $this->dadosform["cc_obraderivada"]));

		if (!$this->dadosform["codtexto"])
			$this->textovo->setRandomico(Util::geraRandomico());
		else
		  $this->textovo->setRandomico($this->textodao->getRandomico($this->dadosform["codtexto"]));

		$this->textovo->setTitulo($this->dadosform["titulo"]);
		$this->textovo->setDescricao($this->dadosform["descricao"]);

		if (!$this->dadosform["codtexto"]) {
			$this->textovo->setDataHora(date("Y-m-d H:i:s"));
			$this->textovo->setSituacao(1);
			$this->textovo->setPublicado(($_SESSION['logado_dados']['nivel'] >= 5 ? 1 : 0));
			//$this->textovo->setSituacao(($_SESSION['logado_dados']['colaborador_responsavel'] == 1 ? 1 : 0));
			//$this->textovo->setPublicado(($_SESSION['logado_dados']['colaborador_responsavel'] == 1 ? 1 : 0));
		}

		$this->textovo->setFotoCredito($this->dadosform["foto_credito"]);
		$this->textovo->setFotoLegenda($this->dadosform["foto_legenda"]);

		$colaboradores = explode(';', $this->dadosform["colaboradores_lista"]);
		if (count($colaboradores)) {
			$arrayColab = array();
			foreach ($colaboradores as $nome) {
				if ($nome) {
					$usuario = $this->usrdao->getBuscaDadosUsuarioNome($nome, 1);
					$arrayColab[$usuario['cod_usuario']] = $usuario['cod_usuario'];
				}
			}
			$this->textovo->setListaColaboradoresRevisao(array_unique($arrayColab));
		}
		
		if($_SESSION['logado_dados']['nivel'] == 2){
			$this->dadosform["pedir_autorizacao"] = 2;
		}
		
		$this->textovo->setPedirAutorizacao($this->dadosform["pedir_autorizacao"]);
		$this->textovo->setUrl(Util::geraUrlTitulo($this->dadosform["titulo"]));
		$this->textovo->setPermitirComentarios($this->dadosform['permitir_comentarios']);
		$this->textovo->setListaAutores($_SESSION["sess_conteudo_autores_ficha"][$this->dadosform["sessao_id"]]);
	}

	protected function editarDados() {
		if (!$this->textovo->getCodConteudo()) {
			$codtexto = $this->textodao->cadastrar($this->textovo);
			$listaArquivos = $this->textodao->cadastrarArquivos($this->arquivos,$codtexto);
		} else {
			$this->textodao->atualizar($this->textovo);
			$codtexto = $this->textovo->getCodConteudo();
			
			if($this->arquivos){
				$listaArquivos = $this->textodao->cadastrarArquivos($this->arquivos,$codtexto);
			}
			$listaArquivos = array_merge($listaArquivos,$this->textodao->getListArquivos($codtexto));
		}

		if ($this->dadosform['imgtemp']) {
			include_once('ImagemTemporariaBO.php');
			$nomearquivo_parcial = "imgtexto_".$codtexto;
			$nomearquivo_final = ImagemTemporariaBO::criaDefinitiva($this->dadosform['imgtemp'], $nomearquivo_parcial, ConfigVO::getDirFotos());
			$this->removerImagensCache($nomearquivo_parcial);
			$this->textodao->atualizarFoto($nomearquivo_final, $codtexto);
		}
		for($i=0; $i<sizeof($this->arquivos['arquivo']['name']);$i++){
			if (is_uploaded_file($this->arquivos["arquivo"]["tmp_name"][$i])) {
				$nomearquivo_original = $this->arquivos["arquivo"]["name"][$i];
				$nomearquivo = "textoarquivo_".$this->textovo->getRandomico()."_".$i;
				copy($this->arquivos["arquivo"]["tmp_name"][$i], ConfigVO::getDirArquivos().$nomearquivo);
				$this->textovo->setCodArquivo($listaArquivos[$i]);
				$this->textovo->setNomeArquivoOriginal($nomearquivo_original);
				$this->textovo->setArquivo($nomearquivo);
				$this->textovo->setTamanho($this->arquivos["arquivo"]["size"][$i]);
			}
		}
		$this->textodao->atualizarArquivo($this->textovo, $codtexto);
		
		//if (is_uploaded_file($this->arquivos["arquivo"]["tmp_name"])) {
		//	$nomearquivo_original = $this->arquivos["arquivo"]["name"];
		//	$nomearquivo = "textoarquivo_".$this->textovo->getRandomico();
		//	copy($this->arquivos["arquivo"]["tmp_name"], ConfigVO::getDirArquivos().$nomearquivo);
		//	$this->textovo->setNomeArquivoOriginal($nomearquivo_original);
		//	$this->textovo->setArquivo($nomearquivo);
		//	$this->textovo->setTamanho($this->arquivos["arquivo"]["size"]);
		//	$this->textodao->atualizarArquivo($this->textovo, $codtexto);
		//}
		unset($_SESSION['sess_conteudo_autores_ficha'][$this->dadosform["sessao_id"]]);
		$this->dadosform = array();
		$this->arquivos = array();
		return $codtexto;
	}

	public function setDadosCamposEdicao($codtexto) {
		$textovo = $this->textodao->getTextoVO($codtexto);

		$this->dadosform["codtexto"] = $this->dadosform["codconteudo"] = $textovo->getCodConteudo();
		$this->dadosform["codcolaborador"] = $textovo->getCodColaborador();
		$this->dadosform["codautor"] = $textovo->getCodAutor();
		$this->dadosform["titulo"] = $textovo->getTitulo();
		$this->dadosform["descricao"] = $textovo->getDescricao();
		$this->dadosform["datahora"] = $textovo->getDataHora();
		$this->dadosform["imagem_visualizacao"] = $textovo->getImagem();
		//$this->dadosform["arquivo"] = $textovo->getArquivo();
		$this->dadosform["arquivo"] = $textovo->getListAnexos();
		$this->dadosform["nome_arquivo_original"] = $textovo->getNomeArquivoOriginal();

		$dados_direito = $this->direitosbo->setDadosCamposEdicao($textovo->getCodLicenca());
		$this->dadosform = array_merge($this->dadosform, $dados_direito);

		$this->dadosform["codlicenca"] = $textovo->getCodLicenca();

		$this->dadosform["foto_credito"] = $textovo->getFotoCredito();
		$this->dadosform["foto_legenda"] = $textovo->getFotoLegenda();

		$this->dadosform["codclassificacao"] = $textovo->getCodClassificacao();
		$this->dadosform["codsegmento"] = $textovo->getCodSegmento();
		$this->dadosform["codsubarea"] = $textovo->getCodSubArea();
		$this->dadosform["codcanal"] = $textovo->getCodCanal();
		$this->dadosform["tags"] = $textovo->getTags();

		$this->dadosform["url"] = $textovo->getUrl();
		$this->dadosform["situacao"] = $textovo->getSituacao();
		$this->dadosform["publicado"] = $textovo->getPublicado();
		$this->dadosform["permitir_comentarios"] = $textovo->getPermitirComentarios();

		$this->setSessionAutoresFicha($codtexto, $this->textodao, $this->dadosform['sessao_id']);

		foreach ((array)$_SESSION['sess_conteudo_autores_ficha'][$this->dadosform["sessao_id"]] as $key => $value) {
			if ($this->dadosform["codautor"] == $value['codautor']) {
				$this->dadosform["pertence_voce"] = 1;
				break;
			}
		}

	}

	public function DownloadArquivo($codarquivo) {
		$dados_arquivo = $this->textodao->getArquivoTexto($codarquivo);
        Util::force_download(file_get_contents(ConfigVO::getDirArquivos().$dados_arquivo['arquivo']), $dados_arquivo['nome_arquivo_original'], '', $dados_arquivo['tamanho']);
		die;
	}

	// metodos comuns a todo os formartos
	public function excluirImagem($codtexto) {
		return $this->textodao->excluirImagem($codtexto);
	}

	public function excluirArquivo($codanexo) {
		return $this->textodao->excluirArquivo($codanexo);
	}

	public function getPostadorConteudo($codusuario) {
		return $this->getColaboradorConteudo($codusuario);
	}

	public function getAutoresFichaConteudo($codtexto) {
		return $this->textodao->getAutoresFichaTecnicaCompletaConteudo($codtexto);
	}

	public function getAutoresConteudo($codtexto) {
		return $this->textodao->getAutoresConteudo($codtexto);
	}

	public function getColaboradorConteudo($codusuario) {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz().'dao/UsuarioDAO.php');
		$usrdao = new UsuarioDAO;
		$usuario = $usrdao->getUsuarioDados($codusuario);
		return $usuario;
	}

	public function getSegmentoConteudo($codtexto) {
		return $this->textodao->getSegmentoConteudo($codtexto);
	}

	public function getSubAreaConteudo($codtexto) {
		return $this->textodao->getSubAreaConteudo($codtexto);
	}

	public function getCategoriaConteudo($codtexto) {
		return $this->textodao->getCategoriaConteudo($codtexto);
	}

	public function getConteudoRelacionado($codtexto) {
		return $this->textodao->getConteudoRelacionadoConteudo($codtexto);
	}

	public function getGrupoRelacionado($codtexto) {
		return $this->textodao->getGrupoRelacionadoConteudo($codtexto);
	}

	public function getLicenca($codtexto) {
		return $this->textodao->getLicenca($codtexto);
	}

	public function getColaboradorConteudoAprovado($codusuario) {
		include_once(ConfigGerenciadorVO::getDirClassesRaiz().'dao/UsuarioDAO.php');
		$usrdao = new UsuarioDAO;
		$usuario = $usrdao->getUsuarioDados($codusuario);
		return $usuario['nome'];
	}

	public function getListaColaboradoresAprovacao($codtexto) {
		return $this->textodao->conteudoEnviadoParaColaboradores($codtexto);
	}

}