<?php
require('classes/vo/ConfigPortalVO.php');
require(ConfigPortalVO::getDirClassesRaiz().'/vo/ConfigVO.php');
require(ConfigPortalVO::getDirClassesRaiz().'/util/Util.php');
//require(ConfigPortalVO::getDirClassesRaiz().'vo/Newsletter_ListaVO.php');
require(ConfigPortalVO::getDirClassesRaiz().'dao/Newsletter_ListaDAO.php');

class NewsletterBO {

	private $campos_erro = array();
	private $lista_email = array();
    private $dadosform = array();

    public function __construct(){
        $this->newlistadao = new Newsletter_ListaDAO();
    }
    public function indicar($dadosform) {
		$this->setDadosFormContato($dadosform);
		try {
			$this->validaDadosFormContato();
		} catch (exception $e) {
			throw $e;
		}
        $mail = $this->dadosform['mail'];
        $this->dadosform['mail'] = '';
        return $this->newlistadao->cadastrarAssinatura($mail);
	}
    
    public function remover($dadosform) {
		$this->setDadosFormContato($dadosform);
		try {
			$this->validaDadosRemover();
		} catch (exception $e) {
			throw $e;
		}
        $mail = $this->dadosform['mail'];
        $motivo = $this->dadosform['motivo'];
        $this->dadosform['mail'] = '';
        $this->dadosform['motivo'] = array();
        
        if(!$this->newlistadao->existeAssinatura($mail)){
            $this->newlistadao->cadastrarAssinatura($mail, 6);
        }
        return $this->newlistadao->descadastrarAssinatura($mail,$motivo);
	}

	private function setDadosFormContato(&$dadosform) {
		$this->dadosform['mail'] = strip_tags(trim($dadosform['mail']));
        $this->dadosform['motivo'] = $dadosform['motivo'];
	}

	private function validaDadosFormContato() {
		if (!Util::checkEmail($this->dadosform['mail'])) {
			$erroform[] = 'invalido';
			$this->campos_erro[] = 'invalido';
		}else{
            if($this->newlistadao->existeAssinatura($this->dadosform['mail'])){
                $erroform[] = 'existe';
                $this->campos_erro[] = 'existe';
            }
        }
        
		if (count($erroform)) {
			throw new Exception ('<em>'.implode('</em>, <em>', $erroform).'</em>');
		}
	}
    
	private function validaDadosRemover() {
		if (!Util::checkEmail($this->dadosform['mail'])) {
			$erroform[] = 'invalido';
			$this->campos_erro[] = 'invalido';
		}
        
        if(count($this->dadosform['motivo']) == 0){
                $erroform[] = 'motivo';
                $this->campos_erro[] = 'motivo';
        }
		if (count($erroform)) {
			throw new Exception ('<em>'.implode('</em>, <em>', $erroform).'</em>');
		}
	}

	private function enviaIndicacao() {
		$texto_email = file_get_contents(ConfigVO::getDirSite()."portal/templates/template_indique_site.html");

		/*
		$mensagem  = "";
		$mensagem .= "<p>Ol&aacute;!</p>";
		$mensagem .= "<p>".$this->dadosform['nome']." indicou este site para você.</p><br />";

		$mensagem .= "<p>Dados do contato:</p>";
		$mensagem .= "<p><strong>Nome:</strong> ".$this->dadosform['nome']."</p>";
		$mensagem .= "<p><strong>Email:</strong> ".$this->dadosform['email']."</p>";
		$mensagem .= "<p><strong>Comentário:</strong> ".$this->dadosform['comentario']."</p>";
		$mensagem .= "<h3>Portal PENC</h3><br />\n";
	    $mensagem .= "<p>Acesse: <a href=\"".ConfigVO::getUrlSite()."\">".ConfigVO::getUrlSiteSemHttp()."</p>\n";
	    */
	    
	    $texto_email = eregi_replace("<!--%AUTOR_NOTICIA%-->", $this->dadosform['nome'], $texto_email);
		
		$texto_email = eregi_replace("<!--%AUTOR_EMAIl%-->", $this->dadosform['email'], $texto_email);
		
		if ($this->dadosform['comentario'])
			$comentario = "<strong>Coment&aacute;rio do remetente:</strong> <span style=\"color:#d21301; font-style:italic\" >".stripslashes($this->dadosform['comentario'])."</span>\n";
		else
			$comentario = "<strong>O remetente n&atilde;o fez coment&aacute;rios.</strong>\n";
			
		$texto_email = eregi_replace("<!--%COMENTARIO%-->", $comentario, $texto_email);

		$texto_email = eregi_replace("<!--%URL_IMG%-->", ConfigVO::URL_SITE, $texto_email);
		$texto_email = eregi_replace("<!--%CORPO_EMAIL%-->", $mensagem, $texto_email);
		
		foreach ($this->lista_emails as $email)
			Util::enviaemail($this->dadosform['nome']." convidou você para conhecer o Portal Pernambuco Nação Cultural", 'Portal Pernambuco Nação Cultural', ConfigVO::getEmail(), $texto_email, $email);
	}

	public function getValorCampo($nomecampo) {
		if (isset($this->dadosform[$nomecampo]))
			return htmlentities(stripslashes($this->dadosform[$nomecampo]));
		return "";
	}

	public function getValorCampoArray($nomecampo) {
		if (isset($this->dadosform[$nomecampo]))
			return $this->dadosform[$nomecampo];
		return array();
	}    

	public function verificaErro($campo) {
		return in_array($campo, $this->campos_erro);
	}
}
