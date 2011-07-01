<?php
include_once(ConfigGerenciadorVO::getDirClassesRaiz().'dao/NotificacaoDAO.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz().'dao/ConteudoDAO.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz().'dao/UsuarioAprovacaoDAO.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz().'dao/ConteudoAprovacaoDAO.php');

class NotificacaoBO {

	private $notdao = null;
	private $contdao = null;
	private $aprovdao = null;
	private $usraprovdao = null;
	private $total = 0;

	public function __construct() {
		$this->notdao = new NotificacaoDAO;
		$this->contdao = new ConteudoDAO;
		$this->aprovdao = new UsuarioAprovacaoDAO;
		$this->usraprovdao = new ConteudoAprovacaoDAO;
	}
	
	public function getListaNotificacao($inicial, $mostrar = 6) {
		$listaFinal = array();
		$listaAprovacao = array();
		
		$listaUsuarios = $this->aprovdao->obterListaAprovacao();
		$listaConteudos = $this->usraprovdao->obterListaAprovacao();
		
		$listaAprovacao = array_merge($listaUsuarios, $listaConteudos);
		//print_r($listaUsuarios);
		//$this->total = count($listaAprovacao);
		$this->total = 0;
		$listaAprovacao = array_chunk($listaAprovacao, $mostrar);
		
		include_once(ConfigGerenciadorVO::getDirClassesRaiz().'dao/UsuarioDAO.php');
		$usrdao = new UsuarioDAO;
		
		include_once(ConfigGerenciadorVO::getDirClassesRaiz().'dao/ConteudoExibicaoDAO.php');
		$condao = new ConteudoExibicaoDAO;
		
		if (count($listaAprovacao)) {
			foreach($listaAprovacao[($inicial - 1)] as $itens) {
				foreach ($itens as $item) {
					$imagem = $mensagem = $visualizacao = null;
					switch($itens['tipo']) {
						case 1:
							$dadosusuario = $usrdao->getUsuarioDados($item['coditem']);
							switch($dadosusuario['cod_tipo']) {
								case 1:
									$imagem = '<span class="colaborador" title="Colaborador">Colaborador</span>';
									$mensagem = 'Novo Colaborador: '.$dadosusuario['nome'].'<br/><strong>Solicita aprovação de cadastro</strong>';
									$visualizacao = 'index_exibir_colaborador_pendente.php?cod='.$dadosusuario['cod_usuario'];
									break;
								case 2:
									$imagem = '<span class="autor" title="Autor">Autor</span>';
									$mensagem = 'Novo Autor: '.$dadosusuario['nome'].'<br/><strong>Solicita aprovação de cadastro</strong>';
									$visualizacao = 'index_exibir_autor_pendente.php?cod='.$dadosusuario['cod_usuario'];
									break;
							}
							break;
						case 2:
							$dadosconteudo = $condao->getConteudoResumido($item['coditem'], false);
							if ($dadosconteudo['cod_conteudo']) {
								$dadosusuarioConteudo = $usrdao->getUsuarioDados($dadosconteudo['cod_autor']);
								switch($dadosconteudo['cod_formato']) {
									case 1:
										$imagem = '<span class="texto" title="Texto">Texto</span>';
										$mensagem = 'Autor: '.$dadosusuarioConteudo['nome'].'<br/><strong>Obra: '.$dadosconteudo['titulo'].'</strong>';
										$visualizacao = 'index_exibir_notificacao.php?cod='.$dadosconteudo['cod_conteudo'];
										break;
									case 2:
										$imagem = '<span class="imagem" title="Imagem">Imagem</span>';
										$mensagem = 'Autor: '.$dadosusuarioConteudo['nome'].'<br/><strong>Obra: '.$dadosconteudo['titulo'].'</strong>';
										$visualizacao = 'index_exibir_notificacao.php?cod='.$dadosconteudo['cod_conteudo'];
										break;
									case 3:
										$imagem = '<span class="audio" title="Áudio">Áudio</span>';
										$mensagem = 'Autor: '.$dadosusuarioConteudo['nome'].'<br/><strong>Obra: '.$dadosconteudo['titulo'].'</strong>';
										$visualizacao = 'index_exibir_notificacao.php?cod='.$dadosconteudo['cod_conteudo'];
										break;
									case 4:
										$imagem = '<span class="video" title="Vídeo">Vídeo</span>';
										$mensagem = 'Autor: '.$dadosusuarioConteudo['nome'].'<br/><strong>Obra: '.$dadosconteudo['titulo'].'</strong>';
										$visualizacao = 'index_exibir_notificacao.php?cod='.$dadosconteudo['cod_conteudo'];
										break;
								}
							}
						break;
					}
					if ($mensagem != null && $visualizacao != null){
						$listaFinal[] = array('imagem' => $imagem, 'mensagem' => $mensagem, 'visualizacao' => $visualizacao);
						$this->total++;
					}
				}
			}
		}
		return $listaFinal;
	}
	
	public function getTotal() {
		return $this->total;
	}
	
	public function getFormatoConteudo($codconteudo) {
		return $this->contdao->getFormatoConteudo($codconteudo);
	}
	
	public function getStatusConteudo($codconteudo) {
		return $this->contdao->getPublicacaoConteudo($codconteudo);
	}
	
	public function getMotivoReprovacao($codconteudo) {
		return $this->notdao->getMotivoReprovacao($codconteudo);
	}

}
