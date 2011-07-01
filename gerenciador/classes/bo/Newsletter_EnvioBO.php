<?php
include_once(dirname(__FILE__).'/../vo/ConfigGerenciadorVO.php');
include_once(dirname(__FILE__).'/NewsletterBO.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz().'util/Util.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz().'dao/NewsletterDAO.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz().'dao/Newsletter_ListaDAO.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz().'util/htmlMimeMail5/htmlMimeMail5.php');

class Newsletter_EnvioBO {

	protected $newsdao = null;
	protected $newslistadao = null;
	
	public function __construct() {
		$this->newsdao = new NewsletterDAO;
		$this->newslistadao = new Newsletter_ListaDAO;
	}
	
	public function envioNewsletter() {
		$lista_news_envio = $this->newsdao->getCodNewsletterListaProgramada();
		
		if (count($lista_news_envio)) {
			
			$newsbo = new NewsletterBO();
			
			foreach($lista_news_envio as $value) {
				$codnewsletter = $value['cod_newsletter'];
				
				$usuarios = $this->getUsuariosEnvio($codnewsletter);
				
				if ($total = count($usuarios)) {
					foreach($usuarios as $email){
                        $mail = new htmlMimeMail5();
                        $newsbo->setEmailDescadastro($email);
                        $mail->setHtml($newsbo->getPrevisaoInterna($codnewsletter, false));
                        $mail->setReturnPath('fundarpe@gmail.com');
                        //$mail->setFrom("\"Fundarpe\" <fundarpe@gmail.com>");
                        $mail->setFrom("\"Boletim iTeia\" <no-reply@iteia.org.br>");
                        //$mail->setSubject("Newsletter Fundarpe ". date('d/m/Y', strtotime($value['data_cadastro'])));
                        $mail->setSubject("Boletim iTeia ". date('d/m/Y', strtotime($value['data_cadastro']))." - ".$value['titulo']);
                        
                        // envia a newsletter
						$result = $mail->send(array($email));
                    }
				}
				
				$this->newsdao->atualizaEnvio($codnewsletter);
			}
		}
		//die;
	}
	
	private function getUsuariosEnvio($codnewsletter) {
		$arrayEmails = array();
		
		$lista = $this->newsdao->getListaProgramada($codnewsletter);
		
		$comlista = explode(',', $lista);
		if (is_array($comlista)) {
			foreach($comlista as $key => $value) {
			
				// emails individuais
				if (Util::checkEmail($value))
					$arrayEmails[] = $value;
				
				// emails da lista
				if (ereg('\[.*\]', $value)) {
					$nomelista = substr(substr($value, 0, -1), 1);
					
					$codlista = $this->newslistadao->getCodLista($nomelista);
					if($codlista == 6){
						$emails = $this->newslistadao->getTodosEmailsLista($nomelista);
					}else{
						$emails = $this->newslistadao->getEmailsLista($nomelista);
					}
					
					foreach($emails as $value_email)
						if (Util::checkEmail($value_email))
							$arrayEmails[] = $value_email;
				}
			}
		}
		return array_unique($arrayEmails);
	}
	
}