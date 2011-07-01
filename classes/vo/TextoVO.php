<?php
include_once('ConteudoVO.php');

class TextoVO extends ConteudoVO {

	protected $cod_formato = 1;
	private $cod_arquivo = array();
	private $arquivo = array();
	private $nome_arquivo_original = array();
	private $tamanho = array();
	
	private $foto_credito = "";
	private $foto_legenda = "";

	public function getListAnexos(){
		$arr = array();
		for($i=0;$i<sizeof($this->cod_arquivo);$i++){
			$arr[] = array("cod" => $this->cod_arquivo[$i],
						"arquivo" => $this->arquivo[$i],
						"nome_original" => $this->nome_arquivo_original[$i],
						"tamanho" => $this->tamanho[$i]);
		}
		return $arr;
	}
	
	public function setCodArquivo($cod) {
		$this->cod_arquivo[] = $cod;
	}
	public function getCodArquivo() {
		return $this->cod_arquivo;
	}
	
	public function setArquivo($arquivo) {
		$this->arquivo[] = $arquivo;
	}
	public function getArquivo() {
		return $this->arquivo;
	}

	public function setNomeArquivoOriginal($nome_arquivo_original) {
		$this->nome_arquivo_original[] = $nome_arquivo_original;
	}
	public function getNomeArquivoOriginal() {
		return $this->nome_arquivo_original;
	}

	public function setTamanho($tamanho) {
		$this->tamanho[] = $tamanho;
	}
	public function getTamanho() {
		return $this->tamanho;
	}
	
	public function setFotoCredito($foto_credito) {
		$this->foto_credito = $foto_credito;
	}
	public function getFotoCredito() {
		return $this->foto_credito;
	}

	public function setFotoLegenda($foto_legenda) {
		$this->foto_legenda = $foto_legenda;
	}
	public function getFotoLegenda() {
		return $this->foto_legenda;
	}

}