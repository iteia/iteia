<?php
include_once(ConfigGerenciadorVO::getDirClassesRaiz().'dao/UsuarioDAO.php');

class PrincipalBO {

	private $usrdao = null;

	public function __construct() {
		$this->usrdao = new UsuarioDAO;
	}

	public function getUsuarioDados() {
		$dadosusuario = $this->usrdao->getUsuarioDados($_SESSION['logado_cod']);
		if ($dadosusuario['imagem'])
			$dadosusuario['imagem'] = 'exibir_imagem.php?img='.$dadosusuario['imagem'].'&amp;tipo=a&amp;s=6';
		else
			$dadosusuario['imagem'] =  'img/imagens-padrao/autor.jpg';

		$dadosusuario['cod_usuario'] = $dadosusuario['cod_usuario'];
		$dadosusuario['nome'] = $dadosusuario['nome'];
		$dadosusuario['url'] = $dadosusuario['url'];
		$dadosusuario['cidade'] = $dadosusuario['cidade'];
		$dadosusuario['estado'] = $dadosusuario['sigla'];

		return $dadosusuario;
	}
	
	public function getListaColaboradoresEdicao() {
		return $this->usrdao->getListaColaboradoresEdicao($_SESSION['logado_dados']['cod']);
	}

	public function getEstatisticasUsuario($codtipo) {
		return $this->usrdao->getEstatisticasUsuario($codtipo);
	}

}
