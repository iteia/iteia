<?php
include_once("ConteudoDAO.php");

class VideoDAO extends ConteudoDAO {

    public function cadastrar(&$vidvo) {
        $codconteudo = $this->cadastrarConteudo($vidvo);

        if ($codconteudo) {
            $sql = "insert into Videos (cod_conteudo, link) values (".$codconteudo.", '".$vidvo->getLinkVideo()."');";
            $this->banco->executaQuery($sql);
        }

        return $codconteudo;
    }

    public function atualizar(&$vidvo) {
        $this->atualizarConteudo($vidvo);
        $sql = "update Videos set link = '".$vidvo->getLinkVideo()."' where cod_conteudo = '".$vidvo->getCodConteudo()."';";
        if ($vidvo->getLinkVideo())
            $this->banco->executaQuery($sql);
    }

    public function getVideoVO(&$codconteudo) {
        $vidvo = new VideoVO;
        $this->getConteudoVO($codconteudo, $vidvo);

        $sql = "select * from Videos where cod_conteudo = ".$codconteudo.";";
        $sql_result = $this->banco->executaQuery($sql);
        $sql_row = $this->banco->fetchObject();

        $vidvo->setArquivo($sql_row->arquivo);
        $vidvo->setArquivoOriginal($sql_row->arquivo_original);
        $vidvo->setTamanho($sql_row->tamanho);
        $vidvo->setLinkVideo($sql_row->link);
        return $vidvo;
    }

    public function atualizarArquivo(&$vidvo, $codvideo) {
        $sql = "update Videos set arquivo = '".$vidvo->getArquivo()."', arquivo_original = '".$vidvo->getArquivoOriginal()."', tamanho = '".$vidvo->getTamanho()."' where cod_conteudo = ".$codvideo.";";
        $sql_result = $this->banco->executaQuery($sql);
    }
    
    public function getArquivoVideo($codvideo) {
        $sql = "SELECT * FROM Videos WHERE cod_conteudo='".$codvideo."';";
        $this->banco->executaQuery($sql);
        return $this->banco->fetchArray();
    }
    
    public function getDadosVideo($codconteudo){
		$sql = "
				SELECT c.cod_conteudo, c.titulo, c.descricao, a.nome_completo AS Autor, col.entidade AS Colaborador
				FROM Conteudo AS c
				LEFT JOIN Colaboradores AS col ON(c.cod_colaborador = col.cod_usuario)
				INNER JOIN Autores AS a ON(c.cod_autor = a.cod_usuario)
				WHERE c.cod_conteudo = '".$codconteudo."'";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = $this->banco->fetchArray($sql_result);
		return $sql_row;
    }

}