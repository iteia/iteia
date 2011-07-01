<?php
include_once("ConteudoDAO.php");

class TextoDAO extends ConteudoDAO {

	public function cadastrar(&$textovo) {
		$codconteudo = $this->cadastrarConteudo($textovo);

		if ($codconteudo) {
			$sql = "INSERT INTO Textos (cod_conteudo, foto_credito, foto_legenda) VALUES (".$codconteudo.", '".$textovo->getFotoCredito()."', '".$textovo->getFotoLegenda()."');";
			$sql_result = $this->banco->executaQuery($sql);
		}
		return $codconteudo;
	}
	
	public function cadastrarArquivos(&$textovo,$codtexto){
		$lista = array();
		for($i=0; $i<sizeof($textovo['arquivo']['name']);$i++){
			if (is_uploaded_file($textovo["arquivo"]["tmp_name"][$i])) {
				$sql = "INSERT INTO Textos_Anexos (cod_conteudo) VALUES (".$codtexto.");";
				$sql_result = $this->banco->executaQuery($sql);
				$lista[]=$this->banco->insertId();
			}
		}
		return $lista;
	}

	public function atualizar(&$textovo) {
		$this->atualizarConteudo($textovo);
		
		$sql = "update Textos set foto_credito = '".$textovo->getFotoCredito()."', foto_legenda = '".$textovo->getFotoLegenda()."' where cod_conteudo = '".$textovo->getCodConteudo()."';";
		$sql_result = $this->banco->executaQuery($sql);
	}

	public function getTextoVO(&$codconteudo) {
		$textovo = new TextoVO;
		$this->getConteudoVO($codconteudo, $textovo);

		$sql = "SELECT * FROM Textos_Anexos WHERE cod_conteudo = ".$codconteudo.";";
		$sql_result = $this->banco->executaQuery($sql);
		
		while($sql_row = $this->banco->fetchObject()){
			$textovo->setCodArquivo($sql_row->cod_anexo);
			$textovo->setArquivo($sql_row->arquivo);
			$textovo->setTamanho($sql_row->tamanho);
			$textovo->setNomeArquivoOriginal($sql_row->nome_arquivo_original);
		}
		
		$sql = "SELECT * FROM Textos WHERE cod_conteudo = ".$codconteudo.";";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchObject();
		$textovo->setFotoCredito($sql_row->foto_credito);
		$textovo->setFotoLegenda($sql_row->foto_legenda);

		return $textovo;
	}

	public function atualizarArquivo(&$textovo, $codtexto) {
		foreach($textovo->getListAnexos() as $arquivo){
			$sql = "UPDATE Textos_Anexos SET arquivo = '".$arquivo['arquivo']."', nome_arquivo_original = '".$arquivo['nome_original']."', tamanho = '".$arquivo['tamanho']."' WHERE cod_anexo = '".$arquivo['cod']."' AND cod_conteudo = '".$codtexto."'";
			$this->banco->executaQuery($sql);
		}
	}
	
	public function getArquivoTexto($codtexto) {
        $sql = "SELECT * FROM Textos_Anexos WHERE cod_anexo='".$codtexto."';";
        $sql_result = $this->banco->executaQuery($sql);
		return $this->banco->fetchArray();
    }
    
    public function excluirArquivo($codanexo) {
		//$sql = "UPDATE Textos_Anexos SET cod_conteudo='', arquivo='', nome_arquivo_original='', tamanho='' WHERE cod_anexo='".$codanexo."'";
		$sql = "delete from Textos_Anexos WHERE cod_anexo='".$codanexo."'";
        $this->banco->executaQuery($sql);
	}
	
	public function getListArquivos($codconteudo) {
		$list = array();
		$sql = "SELECT * FROM Textos_Anexos WHERE cod_conteudo = ".$codconteudo.";";
		$sql_result = $this->banco->executaQuery($sql);
		while($sql_row = $this->banco->fetchObject()){
			$list[] = $sql_row->cod_anexo;
		}
		return $list;
	}
	
	public function getListArquivosCompletos($codconteudo) {
		$list = array();
		$sql = "SELECT * FROM Textos_Anexos WHERE cod_conteudo = ".$codconteudo.";";
		$sql_result = $this->banco->executaQuery($sql);
		while($sql_row = $this->banco->fetchObject()){
			$list[] = $sql_row;
		}
		return $list;
	}

}