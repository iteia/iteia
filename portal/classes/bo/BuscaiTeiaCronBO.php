<?php
include_once(dirname(__FILE__).'/BuscaCacheBO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/PlayerUtil.php');

class BuscaiTeiaCronBO extends BuscaCacheBO {

	protected function gerarResultado() {
		$itensres = array();
		$param_extra = $this->buscavo->getParametrosExtra();

		if (isset($param_extra['ordenacao']) && (int)$param_extra['ordenacao'])
			$this->setOrdem($param_extra['ordenacao']);
                        

		if (in_array(2, $this->buscavo->getListaFormatos()) || !count($this->buscavo->getListaFormatos())) {
			$sql_complemento = 'CO.cod_formato = 3 AND CO.cod_sistema='.ConfigVO::getCodSistema();
			$itensres['audios'] = $this->gerarResultadoItens('conteudo', $sql_complemento);
		}

		if (in_array(3, $this->buscavo->getListaFormatos()) || !count($this->buscavo->getListaFormatos())) {
			$sql_complemento = 'CO.cod_formato = 4 AND CO.cod_sistema='.ConfigVO::getCodSistema();
			$itensres['videos'] = $this->gerarResultadoItens('conteudo', $sql_complemento);
		}

		if (in_array(4, $this->buscavo->getListaFormatos()) || !count($this->buscavo->getListaFormatos())) {
			$sql_complemento = 'CO.cod_formato = 1 AND CO.cod_sistema='.ConfigVO::getCodSistema();
			$itensres['textos'] = $this->gerarResultadoItens('conteudo', $sql_complemento);
		}

		if (in_array(5, $this->buscavo->getListaFormatos()) || !count($this->buscavo->getListaFormatos())) {
			$sql_complemento = 'CO.cod_formato = 2 AND CO.cod_sistema='.ConfigVO::getCodSistema();
			$itensres['imagens'] = $this->gerarResultadoItens('conteudo', $sql_complemento);
		}

		if (in_array(6, $this->buscavo->getListaFormatos()) || !count($this->buscavo->getListaFormatos())) {
			$sql_complemento = 'CO.cod_formato = 5 AND CO.cod_sistema='.ConfigVO::getCodSistema();
            $param_extra['noticia_agenda'] = true;
            $this->buscavo->setParametrosExtra($param_extra);
			$itensres['noticias'] = $this->gerarResultadoItens('conteudo', $sql_complemento);
		}

		if (in_array(7, $this->buscavo->getListaFormatos()) || !count($this->buscavo->getListaFormatos())) {
			$sql_complemento = 'CO.cod_formato = 6 AND CO.cod_sistema='.ConfigVO::getCodSistema();
            $param_extra['noticia_agenda'] = true;
            $this->buscavo->setParametrosExtra($param_extra);
			$itensres['eventos'] = $this->gerarResultadoItens('conteudo', $sql_complemento);
		}

		if (in_array(8, $this->buscavo->getListaFormatos()) || !count($this->buscavo->getListaFormatos())) {
			$sql_complemento = 'CS.cod_sistema='.ConfigVO::getCodSistema();
			$itensres['canais'] = $this->gerarResultadoItens('canais', $sql_complemento);
		}

		if (in_array(9, $this->buscavo->getListaFormatos()) || !count($this->buscavo->getListaFormatos())) {
			$sql_complemento = 'VA.cod_sistema='.ConfigVO::getCodSistema();
			$itensres['autores'] = $this->gerarResultadoItens('autores', $sql_complemento);
		}

		if (in_array(10, $this->buscavo->getListaFormatos()) || !count($this->buscavo->getListaFormatos())) {
			$sql_complemento = 'VC.cod_sistema='.ConfigVO::getCodSistema();
			$itensres['colaboradores'] = $this->gerarResultadoItens('colaboradores', $sql_complemento);
		}
        
		return $itensres;
	}
	
	public function setId($id) {
		$this->id = $id;
	}

	public function exibeResultadoHtml() {
	}
	
	public function apagarCache($id) {
		$this->buscadao->apagarCache($id);
	}

}
