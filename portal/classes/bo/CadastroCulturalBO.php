<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'/vo/ConfigVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'vo/AutorVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'dao/AutorDAO.php');

class CadastroCulturalBO {

	private $dadosform = array();
	private $erro_campos = array();
	private $erro_mensagens = array();

	private $autorvo = null;
	private $autordao = null;

	public function __construct() {
		$this->autordao = new AutorDAO;
	}

	private function setDadosForm(&$dadosform) {
		$this->dadosform = $dadosform;
		$this->dadosform['nome'] = strip_tags(trim($this->dadosform['nome']));
		$this->dadosform['nomeartistico'] = strip_tags(trim($this->dadosform['nomeartistico']));
		$this->dadosform['cpf'] = strip_tags(trim($this->dadosform['cpf']));
		$this->dadosform['endereco'] = strip_tags(trim($this->dadosform['endereco']));
		$this->dadosform['complemento'] = strip_tags(trim($this->dadosform['complemento']));
		$this->dadosform['datanascimento'] = strip_tags(trim($this->dadosform['datanascimento']));
		$this->dadosform['codcidade'] = (int)$this->dadosform['codcidade'];
		$this->dadosform['codestado'] = (int)$this->dadosform['codestado'];
		$this->dadosform['codpais'] = (int)$this->dadosform['codpais'];
		$this->dadosform['cidade'] = strip_tags(trim($this->dadosform['cidade']));
		$this->dadosform['bairro'] = strip_tags(trim($this->dadosform['bairro']));
		
		$this->dadosform['codtipo'] = (int)$this->dadosform['codtipo'];
		$this->dadosform['numero'] = strip_tags(trim($this->dadosform['numero']));
		$this->dadosform['orgao'] = strip_tags(trim($this->dadosform['orgao']));
		
		$this->dadosform['cep'] = strip_tags(trim($this->dadosform['cep']));
		$this->dadosform['email'] = strip_tags(trim($this->dadosform['email']));
		$this->dadosform['email2'] = strip_tags(trim($this->dadosform['email2']));
		$this->dadosform['site'] = str_replace('http://', '', substr(trim($this->dadosform['site']), 0, 200));
		$this->dadosform['fone'] = strip_tags(trim($this->dadosform['fone']));
		$this->dadosform['celular'] = strip_tags(trim($this->dadosform['celular']));
		$this->dadosform['historico'] = strip_tags(trim(stripslashes($this->dadosform['historico'])));
		$this->dadosform['finalendereco'] = Util::geraTags(substr(trim($this->dadosform["finalendereco"]), 0, 30));
		
        $this->dadosform['senha'] = Util::geraRandomico();
		//$this->dadosform['senha'] = strip_tags(trim(stripslashes($this->dadosform['senha'])));
		//$this->dadosform['senha2'] = strip_tags(trim(stripslashes($this->dadosform['senha2'])));
		$this->dadosform['deacordo'] = (int)$this->dadosform['deacordo'];
	}

	private function validaDadosForm() {
		if (!$this->dadosform['nome']) {
			$this->erro_campos[] = 'nome';
			$this->erro_mensagens[] = '<a href="#seu-nome">Escreva seu <strong>nome</strong></a>';
		}

		if (!$this->dadosform['historico']) {
			$this->erro_campos[] = 'historico';
			$this->erro_mensagens[] = '<a href="#historico">Escreva sua <strong>biografia</strong></a>';
		}

		if (!$this->dadosform['email']) {
			$this->erro_campos[] = 'email';
			$this->erro_mensagens[] = '<a href="#email">Adicione um <strong>e-mail</strong></a>';
		}

		if (!Util::checkEmail($this->dadosform['email']) && $this->dadosform['email']) {
			$this->erro_campos[] = 'email';
			$this->erro_mensagens[] = '<a href="#email">Adicione um <strong>e-mail</strong> v�lido</a>';
		}

		if (!$this->dadosform['codcidade'])
			$this->erro_campos[] = 'cidade';

		if (!$this->dadosform['codestado'])
			$this->erro_campos[] = 'estado';

		if (!$this->dadosform['codpais'])
			$this->erro_campos[] = 'pais';

		if ($this->dadosform['codpais'] == 2) {
			if (!$this->dadosform['codestado']) {
				$this->erro_campos[] = 'estado';
				$this->erro_mensagens[] = '<a href="#estado">Selecione um <strong>estado</strong></a>';
			}
			if (!$this->dadosform['codcidade']) {
				$this->erro_campos[] = 'cidade';
				$this->erro_mensagens[] = '<a href="#selectcidade">Selecione uma <strong>cidade</strong></a>';
			}
		} elseif (!$this->dadosform['cidade']) {
			$this->erro_campos[] = 'cidade';
			$this->erro_mensagens[] = '<a href="#cidade">Escreva o nome da <strong>cidade</strong></a>';
		}

		if (!$this->dadosform['finalendereco']) {
			$this->erro_campos[] = 'finalendereco';
			$this->erro_mensagens[] = '<a href="#final_endereco">Informe o Nome de <strong>usu�rio</strong></a>';
		}
		elseif (!eregi('^[a-zA-Z0-9]+$', $this->dadosform['finalendereco'])) {
			$this->erro_campos[] = 'finalendereco';
			$this->erro_mensagens[] = '<a href="#final_endereco">Nome de <strong>usu�rio</strong> inv�lido</a>';
		}
		elseif ($this->autordao->existeFinalEndereco($this->dadosform['finalendereco'], 0)) {
			$this->erro_campos[] = 'finalendereco';
			$this->erro_mensagens[] = "Final do endere�o j� existente";
			$this->erro_mensagens[] = '<a href="#final_endereco">Nome de <strong>usu�rio</strong> j� existe</a>';
		}

//		if (!$this->dadosform['senha']) {
//			$this->erro_campos[] = 'senha';
//			$this->erro_mensagens[] = '<a href="#pass">Informe uma <strong>senha</strong></a>';
//		}
//
//		if ($this->dadosform['senha'] != $this->dadosform['senha2']) {
//			$this->erro_campos[] = 'senha';
//			$this->erro_mensagens[] = '<a href="#pass">As <strong>senhas</strong> n�o conhecidem</a>';
//		}
//
//        if ($this->dadosform['senha'] && strlen($this->dadosform['senha']) < 6) {
//			$this->erro_campos[] = 'senha';
//			$this->erro_mensagens[] = '<a href="#pass">A <strong>senha</strong> precisa ter mais de 6 caracteres</a>';
//        }

		if (!$this->dadosform['deacordo']) {
			$this->erro_campos[] = 'deacordo';
			$this->erro_mensagens[] = '<a href="#acordo">Aceite os <strong>termos de uso</strong></a>';
		}

		if (count($this->erro_campos) || count($this->erro_mensagens))
			throw new Exception(' ');
	}

	public function cadastrar($dadosform) {
		$this->setDadosForm($dadosform);
		try {
			$this->validaDadosForm();
		} catch (exception $e) {
			throw $e;
		}
		$this->sendForm();
	}

	private function sendForm() {
		$mensagem  = "";
		$mensagem .= "<p>Ol&aacute;!</p>";
		$mensagem .= "<p><strong><em>".$this->dadosform['nome']."</em></strong> enviou seus dados para cadastro do iTEIA!.</p>";

		$mensagem .= "<p>Dados de cadastro:</p>";
		$mensagem .= "<p><strong>Nome:</strong> ".$this->dadosform['nome'];
		if ($this->dadosform['nomeartistico'])
			$mensagem .= "<p><strong>Nome Art�stico:</strong> ".$this->dadosform['nomeartistico'];
		if ($this->dadosform['datanascimento'])
			$mensagem .= "<p><strong>Data de nascimento:</strong> ".$this->dadosform['datanascimento'];
		if ($this->dadosform['historico'])
			$mensagem .= "<p><strong>Biografia:</strong> ".nl2br($this->dadosform['historico']);
		if ($this->dadosform['endereco'])
			$mensagem .= "<p><strong>Endere�o:</strong> ".$this->dadosform['endereco'];
		if ($this->dadosform['complemento'])
			$mensagem .= "<p><strong>Complemento:</strong> ".$this->dadosform['complemento'];
		if ($this->dadosform['bairro'])
			$mensagem .= "<p><strong>Bairro:</strong> ".$this->dadosform['bairro'];
		$mensagem .= "<p><strong>Pa�s:</strong> ".$this->getPais($this->dadosform['codpais']);

		if ($this->dadosform['codpais'] == 2) {
			$mensagem .= "<p><strong>Estado:</strong> ".$this->getEstado($this->dadosform['codestado']);
			$mensagem .= "<p><strong>Cidade:</strong> ".$this->getCidade($this->dadosform['codcidade']);
		} else {
			$mensagem .= "<p><strong>Cidade:</strong> ".$this->dadosform['cidade'];
		}

		if ($this->dadosform['fone'])
			$mensagem .= "<p><strong>Telefone fixo:</strong> ".$this->dadosform['fone'];
		$mensagem .= "<p><strong>E-mail:</strong> ".$this->dadosform['email'];
		$mensagem .= "<br />";

		$mensagem .= "<p><strong>Final do endere�o:</strong> ".$this->dadosform['finalendereco'];
		$mensagem .= "<p><strong>Login:</strong> ".$this->dadosform['finalendereco'];
		$mensagem .= "<p><strong>Senha:</strong> ".$this->dadosform['senha'];

		$mensagem .= "<p><strong>Acordo com as regras para participar do iTEIA:</strong> ".Util::iif($this->dadosform['deacordo'] == 1, 'Sim', 'N�o');

		$texto_email = file_get_contents(ConfigVO::getDirSite().'portal/templates/template_email.html');
		$texto_email = eregi_replace('<!--%URL%-->', ConfigVO::URL_SITE, $texto_email);
		$texto_email = eregi_replace('<!--%CORPO_EMAIL%-->', $mensagem, $texto_email);
		Util::enviaemail($this->dadosform['nome']." enviou seus dados para cadastro do iTEIA", $this->dadosform['email'], ConfigVO::getEmail(), $texto_email, ConfigVO::getEmail());

		$this->setDadosVO();
		$codusuario = $this->autordao->cadastrar($this->autorvo);
		$this->autordao->inseriDadosEndereco($codusuario, $this->dadosform['codtipo'], $this->dadosform['numero'], $this->dadosform['orgao']);
		
		include_once(ConfigPortalVO::getDirClassesRaiz().'dao/UsuarioAprovacaoDAO.php');
		$aprovdao = new UsuarioAprovacaoDAO;
		$aprovdao->cadastrarNotificacaoAprovacao($codusuario, 1);
		
		$mensagem  = "";
		$mensagem .= "<p>Ol� ".$this->dadosform['nome'].",</p>";
		$mensagem .= "<br/><p>A Equipe iTeia recebeu sua solicita��o para fazer parte da nossa rede como Autor.</p>";
		$mensagem .= "<p>Mas... Espere s� um pouquinho!</p>";
		$mensagem .= "<p>Seu cadastro est� aguardando a aprova��o de um <a href=\"".ConfigVO::URL_SITE."colaboradores\">Colaborador</a>.</p>";
		$mensagem .= "<br/>---";
		$mensagem .= "<br/>Equipe iTeia";
		$mensagem .= "<br/><a href=\"http://www.iteia.org.br\">http://www.iteia.org.br/</a>";
		$mensagem .= "<br/><a href=\"http://www.twitter.com/iteia\">http://www.twitter.com/iteia</a>";

		$texto_email = file_get_contents(ConfigVO::getDirSite()."portal/templates/template_email.html");
		$texto_email = eregi_replace('<!--%URL%-->', ConfigVO::URL_SITE, $texto_email);
		$texto_email = eregi_replace('<!--%CORPO_EMAIL%-->', $mensagem, $texto_email);
		Util::enviaemail('[iTEIA] - Seu cadastro aguarda uma aprova��o. Agora falta pouco!', 'Suporte iTEIA', ConfigVO::getEmail(), $texto_email, $this->dadosform['email']);
	}

	protected function setDadosVO() {
		$this->autorvo = new AutorVO;
		$this->autorvo->setCodUsuario(0);
		$this->autorvo->setCodTipo(2);
		$this->autorvo->setNome($this->dadosform['nomeartistico']);
		$this->autorvo->setNomeCompleto($this->dadosform['nome']);
		$this->autorvo->setDataNascimento(substr($this->dadosform['datanascimento'], 6, 4).'-'.substr($this->dadosform['datanascimento'], 3, 2).'-'.substr($this->dadosform['datanascimento'], 0, 2));
		$this->autorvo->setDataFalecimento('0000-00-00');
		$this->autorvo->setDescricao($this->dadosform['historico']);
		$this->autorvo->setEndereco($this->dadosform['endereco']);
		$this->autorvo->setComplemento($this->dadosform['complemento']);
		$this->autorvo->setBairro($this->dadosform['bairro']);
		$this->autorvo->setCep($this->dadosform['cep']);
		$this->autorvo->setCodCidade((int)$this->dadosform['codcidade']);
		$this->autorvo->setCidade($this->dadosform['cidade']);
		$this->autorvo->setCodEstado((int)$this->dadosform['codestado']);
		$this->autorvo->setCodPais((int)$this->dadosform['codpais']);
		$this->autorvo->setTelefone($this->dadosform['fone']);
		$this->autorvo->setCelular($this->dadosform['celular']);
		$this->autorvo->setEmail($this->dadosform['email']);
		$this->autorvo->setSite($this->dadosform['site']);
		$this->autorvo->setUrl($this->dadosform['finalendereco']);
		$this->autorvo->setCPF($this->dadosform['cpf']);
        $this->autorvo->setLogin($this->dadosform['finalendereco']);
        $this->autorvo->setSenha($this->dadosform['senha']);
        $this->autorvo->setCodNivel(2);
		$this->autorvo->setSituacao(1);
	}

	public function getValorCampo($nomecampo) {
		if (isset($this->dadosform[$nomecampo]))
			return $this->dadosform[$nomecampo];
		return "";
	}
	
	public function setValorCampo($campo, $valor) {
		$this->dadosform[$campo] = $valor;
	}
	
	public function getErros() {
		return '<li>'.implode("</li><li>\n", $this->erro_mensagens).'</li>';
	}

	public function verificaErroCampo($nomecampo) {
		if (in_array($nomecampo, $this->erro_campos))
			return ' erro';
		else
			return '';
	}

	public function getListaEstados() {
		include_once(ConfigPortalVO::getDirClassesRaiz().'dao/EstadoDAO.php');
		$estdao = new EstadoDAO;
		return $estdao->getListaEstados();
	}

	public function getListaPaises() {
		include_once(ConfigPortalVO::getDirClassesRaiz().'dao/PaisDAO.php');
		$paisdao = new PaisDAO;
		return $paisdao->getListaPaises();
	}

	public function getListaCidades($codestado) {
		include_once(ConfigPortalVO::getDirClassesRaiz().'dao/CidadeDAO.php');
		$ciddao = new CidadeDAO;
		return $ciddao->getListaCidades($codestado);
	}

	public function getEstado($codestado) {
		include_once(ConfigPortalVO::getDirClassesRaiz().'dao/EstadoDAO.php');
		$estdao = new EstadoDAO;
		return $estdao->getNomeEstado($codestado);
	}

	public function getPais($codpais) {
		include_once(ConfigPortalVO::getDirClassesRaiz().'dao/PaisDAO.php');
		$paisdao = new PaisDAO;
		return $paisdao->getNomePais($codpais);
	}

	public function getCidade($codcidade) {
		include_once(ConfigPortalVO::getDirClassesRaiz().'dao/CidadeDAO.php');
		$ciddao = new CidadeDAO;
		return $ciddao->getNomeCidade($codcidade);
	}

}
