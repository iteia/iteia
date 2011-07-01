<?php
include_once(ConfigGerenciadorVO::getDirClassesRaiz().'dao/Newsletter_ListaDAO.php');
//include_once('inc_interjornal/classes/util/GlobalSiteUtil.kmf');

class BuscaNewsletterBloqueadosBO {

	private $listadao = null;
	protected $dadosform = array();

	public function __construct() {
		$this->listadao = new Newsletter_ListaDAO;
	}

	public function getNewsletterUsuariosListasBusca($dadosget, $inicial, $mostrar) {
		if ((int)$dadosget['acao'] == 1) {
			if (count($dadosget['cod'])) {
				$this->listadao->desbloquearUsuario($dadosget['cod']);
			}
			Header('location: home_newsletter_bloqueados.php?mostrar='.$dadosget['mostrar'].'&pagina='.$dadosget['pagina'].'&titulo='.$dadosget['titulo'].'&codlista='.$dadosget['codlista']);
			die;
		}
		return $this->listadao->getNewsletterUsuariosDescadastradosBusca($dadosget, $inicial, $mostrar);
	}
	
	public function getListasEnvio() {
		return $this->listadao->getListas();
	}
	
}
