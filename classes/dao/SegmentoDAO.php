<?php
include_once("ConexaoDB.php");

class SegmentoDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}

//	public function getSegmentosPai() {
//		$array = array();
//		$sql = "SELECT * FROM Conteudo_Segmento WHERE disponivel='1' AND cod_pai='0' AND cod_sistema='".ConfigVO::getCodSistema()."' ORDER BY nome";
//    	$query = $this->banco->executaQuery($sql);
//    	while ($row = $this->banco->fetchArray($query)) {
//    		$total = $this->banco->numRows($this->banco->executaQuery("SELECT cod_segmento FROM Conteudo WHERE cod_segmento='".$row['cod_segmento']."' AND excluido='0' AND publicado='1'"));
//    		$row['total'] = $total;
//			$array[] = $row;
//    	}
//        return $array;
//	}
	public function getSegmentosPai($dados, $limite) {
		$array = array();

		if ($dados){
			$where = " AND t1.nome LIKE '%".$dados."%'";
            $pai = "";
        }else{
            $pai = "AND t1.cod_pai='0'";
        }
        
		if (isset($limite))
			$limite = " LIMIT ".$limite.",10";

        $sql = "SELECT * FROM Conteudo_Segmento AS t1 WHERE t1.disponivel='1' $pai AND t1.cod_sistema='".ConfigVO::getCodSistema()."' $where ORDER BY t1.nome";

		$array['total'] = $this->banco->numRows($this->banco->executaQuery($sql));
        
		$sql = "$sql $limite";
    	$query = $this->banco->executaQuery($sql);
    	while ($row = $this->banco->fetchArray($query)) {
    		$total = $this->banco->numRows($this->banco->executaQuery("SELECT cod_segmento FROM Conteudo WHERE cod_segmento='".$row['cod_segmento']."' AND excluido='0' AND publicado='1'"));
    		$row['total'] = $total;
			$array[] = $row;
    	}
        return $array;
	}

	public function cadastrar(&$segvo) {
		$this->banco->sql_insert('Conteudo_Segmento', array('cod_pai' => $segvo->getCodPai(), 'nome' => $segvo->getNome(), 'descricao' => $segvo->getDescricao(), 'cod_sistema' => ConfigVO::getCodSistema(), 'verbete' => $segvo->getVerbete(), 'disponivel' => 1));
		$codseg = $this->banco->insertId();

		$i = 0;
		$url = Util::geraUrlTitulo($segvo->getNome());
		do {
			if ($i)
				$url = $url.$i;
			$sql = "insert into Urls values ('".$url."', '".$codseg."', 5, '".ConfigVO::getCodSistema()."')";
			$tenta = $this->banco->executaQuery($sql);
			$i++;
		}
		while (!$tenta);

		return $codseg;
	}

	public function atualizar(&$segvo) {
		$sql = "select nome from Conteudo_Segmento where cod_segmento = '".$segvo->getCodSegmento()."';";
		$sql_result = $this->banco->executaQuery($sql);
		$sql_row = mysql_fetch_array($sql_result);

		$this->banco->sql_update('Conteudo_Segmento', array('cod_pai' => $segvo->getCodPai(), 'nome' => $segvo->getNome(), 'descricao' => $segvo->getDescricao(), 'verbete' => $segvo->getVerbete()), "cod_segmento='".$segvo->getCodSegmento()."'");

		if ($sql_row['nome'] != $segvo->getNome()) {
			$i = 0;
			$url = Util::geraUrlTitulo($segvo->getNome());
			if ($url) {
				do {
					if ($i)
						$url = $url.$i;
					$sql = "UPDATE Urls SET titulo='".$url."' WHERE cod_item='".$segvo->getCodSegmento()."' AND tipo=5 and cod_sistema = '".ConfigVO::getCodSistema()."';";
					$tenta = $this->banco->executaQuery($sql);
					$i++;
				}
				while (!$tenta);
			}
		}

		return $segvo->getCodSegmento();
	}

	public function atualizarFoto($nomearquivo, $codsegmento) {
		$sql = "UPDATE Conteudo_Segmento SET imagem = '".$nomearquivo."' WHERE cod_segmento='".$codsegmento."'";
		$sql_result = $this->banco->executaQuery($sql);
	}

	public function excluirImagem($codsegmento) {
		$sql = "UPDATE Conteudo_Segmento SET imagem = '' WHERE cod_segmento='".$codsegmento."'";
		$this->banco->executaQuery($sql);
	}

	public function getSegmentoVO($codseg) {
        $segvo = new SegmentoVO;
        $query = $this->banco->executaQuery("SELECT * FROM Conteudo_Segmento WHERE cod_segmento='".$codseg."' AND disponivel='1'");
        $row = $this->banco->fetchArray();
        $segvo->setCodSegmento($row['cod_segmento']);
        $segvo->setCodPai($row['cod_pai']);
        $segvo->setNome($row['nome']);
        $segvo->setDescricao($row['descricao']);
        $segvo->setImagem($row['imagem']);
        $segvo->setVerbete($row['verbete']);
        return $segvo;
    }

    public function executaAcao($codsegmento) {
		if (count($codsegmento))
			$this->banco->executaQuery("UPDATE Conteudo_Segmento SET disponivel='0' WHERE cod_segmento IN (".implode(',', $codsegmento).")");
	}

	public function getListaSegmentos() {
    	$array = $seg_array = array();
		$sql = "SELECT cod_segmento, nome FROM Conteudo_Segmento WHERE cod_pai='0' AND disponivel='1' AND cod_sistema='".ConfigVO::getCodSistema()."' ORDER BY nome";
    	$query = $this->banco->executaQuery($sql);
    	while ($row = $this->banco->fetchArray($query))
    		$seg_array[$row['cod_segmento']] = $row['cod_segmento'];

    	$sql = "SELECT * FROM Conteudo_Segmento WHERE cod_pai IN ('".implode("','", $seg_array)."') AND disponivel='1' AND cod_sistema='".ConfigVO::getCodSistema()."' ORDER BY nome";
    	$query = $this->banco->executaQuery($sql);
    	while ($row = $this->banco->fetchArray($query)) {
    		//$total = $this->banco->numRows($this->banco->executaQuery("SELECT cod_segmento FROM Conteudo WHERE cod_pai='".$row['cod_segmento']."' AND excluido='0' AND publicado='1'"));
            $total = $this->banco->numRows($this->banco->executaQuery("SELECT cod_segmento FROM Conteudo WHERE cod_subarea='".$row['cod_segmento']."' AND excluido='0' AND publicado='1'"));
			$array[$row['cod_pai']][] = array(
                'imagem' => $row['imagem'],
				'cod' => $row['cod_segmento'],
				'nome' => $row['nome'],
				'verbete' => $row['verbete'],
				'total' => (int)$total
			);
		}
        return $array;
    }


    public function getListaSegmentosCadastro() {
    	$array = array();
    	$sql = "SELECT cod_segmento, nome, cod_pai FROM Conteudo_Segmento WHERE disponivel='1' AND cod_sistema='".ConfigVO::getCodSistema()."' AND cod_pai='0' ORDER BY nome";
    	$query = $this->banco->executaQuery($sql);
    	while ($row = $this->banco->fetchArray($query))
    		$array[] = $row;
        return $array;
    }

    public function getListaSubAreasCadastro() {
    	$array = array();
    	$sql = "SELECT cod_segmento, nome, cod_pai FROM Conteudo_Segmento WHERE disponivel='1' AND cod_sistema='".ConfigVO::getCodSistema()."' AND cod_pai!='0' ORDER BY nome";
    	$query = $this->banco->executaQuery($sql);
    	while ($row = $this->banco->fetchArray($query))
    		$array[] = $row;
        return $array;
    }

	public function getListaSubAreasCadastroCodCanal($codcanal) {
    	$array = array();
    	$sql = "SELECT cod_segmento, nome, cod_pai FROM Conteudo_Segmento WHERE disponivel='1' AND cod_sistema='".ConfigVO::getCodSistema()."' AND cod_pai = '".$codcanal."' ORDER BY nome";
    	$query = $this->banco->executaQuery($sql);
    	while ($row = $this->banco->fetchArray($query))
    		$array[] = $row;
        return $array;
    }

	public function getSegmentoDados($codsegmento) {
		$query = $this->banco->executaQuery("SELECT * FROM Conteudo_Segmento WHERE cod_segmento='".$codsegmento."' AND disponivel='1'");
		$dados = $this->banco->fetchArray();
		$query = $this->banco->executaQuery("SELECT titulo as url FROM Urls WHERE cod_item='".$codsegmento."' AND tipo=5 and cod_sistema='".ConfigVO::getCodSistema()."';");
		$dados2 = $this->banco->fetchArray();
		$dados['url'] = $dados2['url'];
		/*echo "SELECT * FROM Conteudo_Segmento WHERE cod_segmento='".$codsegmento."' AND disponivel='1'
		AND imagem is not null AND imagem != '' ";*/
        return $dados;
	}

	public function getHomeSegmentosRandom() {
		$array = array();
    	$sql = "SELECT cod_segmento, nome FROM Conteudo_Segmento WHERE disponivel='1' AND cod_sistema='".ConfigVO::getCodSistema()."' AND cod_segmento IN (SELECT t1.cod_segmento FROM Conteudo AS t1 WHERE t1.cod_segmento=cod_segmento AND t1.excluido='0' AND t1.publicado='1' AND t1.situacao='1' AND cod_formato !='6' AND t1.cod_sistema='".ConfigVO::getCodSistema()."') ORDER BY RAND() LIMIT 2";
    	$query = $this->banco->executaQuery($sql);
    	while ($row = $this->banco->fetchArray($query))
    		$array[] = $row;
        return $array;
	}

//	public function getHomeSegmentosRandom() {
//		$array = array();
//        $list = array();
//        $sql = "SELECT t1.cod_segmento, COUNT(t1.cod_segmento) FROM Conteudo AS t1 INNER JOIN Conteudo_Segmento AS t2 ON t1.cod_segmento=t2.cod_segmento WHERE t1.cod_segmento != 0 AND t1.excluido='0' AND t1.publicado='1' AND t1.situacao='1' AND t1.cod_sistema='".ConfigVO::getCodSistema()."' AND t2.cod_sistema='".ConfigVO::getCodSistema()."' GROUP BY t1.cod_segmento";
//        $query = $this->banco->executaQuery($sql);
//    	while ($row = $this->banco->fetchArray($query))
//    		$list[] = $row[0];
//            
//    	$sql = "SELECT cod_segmento, nome FROM Conteudo_Segmento WHERE disponivel='1' AND cod_sistema='".ConfigVO::getCodSistema()."' AND cod_segmento IN (".implode(",",$list).") ORDER BY RAND() LIMIT 2";
//    	$query = $this->banco->executaQuery($sql);
//    	while ($row = $this->banco->fetchArray($query))
//    		$array[] = $row;
//        return $array;
//	}

	public function getCodConteudoPorCodSegmento($codsegmento, $limit=4) {
		$sql = "SELECT t1.cod_conteudo, t1.cod_formato FROM Conteudo AS t1 WHERE (t1.cod_segmento='$codsegmento' OR t1.cod_subarea='$codsegmento')  AND t1.excluido='0' AND t1.publicado='1' AND t1.situacao='1' AND t1.cod_sistema = ".ConfigVO::getCodSistema(). " ORDER BY RAND() LIMIT ".$limit;
		$query = $this->banco->executaQuery($sql);
		$array = array();
    	while ($row = $this->banco->fetchArray($query))
    		$array[] = $row;
        return $array;
	}

	public function getSegmentosAtivosRandom($limite=10) {
		//$sql = "SELECT COUNT(c.cod_segmento) AS total, c.cod_segmento, c.imagem FROM Conteudo c, Conteudo_Segmento cs WHERE c.excluido='0' AND c.situacao='1' AND c.publicado='1' AND c.cod_sistema='".ConfigVO::getCodSistema()."' AND c.cod_segmento>0 AND c.cod_segmento=cs.cod_segmento AND cs.disponivel=1 GROUP BY c.cod_segmento DESC LIMIT ".$limite;
        $sql = "SELECT t1.* FROM (SELECT COUNT(cs.cod_segmento) AS total, cs.cod_segmento, c.imagem FROM Conteudo c, Conteudo_Segmento cs WHERE c.excluido='0' AND c.situacao='1' AND c.publicado='1' AND c.cod_sistema='".ConfigVO::getCodSistema()."' AND c.cod_segmento>0 AND (cs.cod_segmento=c.cod_segmento OR cs.cod_segmento=c.cod_subarea) AND cs.disponivel=1 GROUP BY cs.cod_segmento DESC) AS t1 ORDER BY t1.total DESC LIMIT {$limite}";
		//echo $sql;
		$query = $this->banco->executaQuery($sql);
		$array = array();
    	while ($row = $this->banco->fetchArray($query)) {
    		$array[$row['cod_segmento']] = $row;
			$array[$row['cod_segmento']]['dados'] = $this->getSegmentoDados($row['cod_segmento']);
		}
		//shuffle($array);
        return $array;
	}

	public function getTotalConteudoPorCodSegmento($codsegmento) {
		#$sql = "SELECT COUNT(c.cod_segmento) AS total FROM Conteudo c WHERE c.excluido='0' AND c.situacao='1' AND c.publicado='1' AND c.cod_sistema='".ConfigVO::getCodSistema()."' AND c.cod_segmento>0 AND c.cod_segmento='".$codsegmento."'";
		$sql = "SELECT COUNT(c.cod_segmento) AS total FROM Conteudo c WHERE c.excluido='0' AND c.situacao='1' AND c.publicado='1' AND c.cod_sistema='".ConfigVO::getCodSistema()."' AND c.cod_segmento>0 AND (c.cod_segmento='".$codsegmento."' OR c.cod_subarea='".$codsegmento."')";
		$query = $this->banco->executaQuery($sql);
		$row = $this->banco->fetchArray($query);
        return (int)$row['total'];
	}

	public function getSegmentosAtivosCultura() {
		//$sql = "SELECT COUNT(c.cod_segmento) AS total, c.cod_segmento, c.imagem FROM Conteudo c, Conteudo_Segmento cs WHERE c.excluido='0' AND c.situacao='1' AND c.publicado='1' AND c.cod_sistema='".ConfigVO::getCodSistema()."' AND c.cod_segmento>0  AND c.cod_segmento=cs.cod_segmento AND cs.disponivel=1 /*AND cs.imagem IS NOT NULL AND cs.imagem != ''*/ GROUP BY c.cod_segmento DESC LIMIT 4 ";
        $sql = "SELECT t1.* FROM (SELECT COUNT(cs.cod_segmento) AS total, cs.cod_segmento, c.imagem FROM Conteudo c, Conteudo_Segmento cs WHERE c.excluido='0' AND c.situacao='1' AND c.publicado='1' AND c.cod_sistema='".ConfigVO::getCodSistema()."' AND c.cod_segmento>0  AND (cs.cod_segmento=c.cod_segmento OR cs.cod_segmento=c.cod_subarea) AND cs.disponivel=1 /*AND cs.imagem IS NOT NULL AND cs.imagem != ''*/ GROUP BY cs.cod_segmento) AS t1 ORDER BY RAND() DESC LIMIT 4";
		$query = $this->banco->executaQuery($sql);
		$array = array();
    	while ($row = $this->banco->fetchArray($query)) {
    		$array[$row['cod_segmento']] = $row;
			$array[$row['cod_segmento']]['dados'] = $this->getSegmentoDados($row['cod_segmento']);
		}
		shuffle($array);
        return $array;
	}

}