<?php
include_once('ConexaoDB.php');

class ConteudoAprovacaoDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}

	public function cadastrarNotificacaoAprovacao($codconteudo, $tipo, $colaboradores = '') {
		if ($codconteudo)
			$this->banco->sql_insert('Conteudo_Aprovacao', array('cod_conteudo' => $codconteudo, 'tipo' => $tipo, 'cod_colaboradores' => $colaboradores, 'data_cadastro' => date('Y-m-d H:i:s')));
		return true;
	}

	public function editarNotificacaoAprovacao($codconteudo, $tipo, $codusuario_logado, $observacao) {
		if ($codconteudo)
			$this->banco->sql_update('Conteudo_Aprovacao', array('cod_usuario_logado' => $codusuario_logado, 'tipo' => $tipo, 'observacao' => $observacao, 'data_atualizacao' => date('Y-m-d H:i:s')), "cod_conteudo = ".$codconteudo);
		return true;
	}
	
	public function editarEdicaoNotificacaoAprovacao($codconteudo, $tipo, $colaboradores = '') {
		if ($codconteudo){
                    if($this->banco->numRows($this->banco->sql_select('cod_conteudo','Conteudo_Aprovacao', "cod_conteudo = ".$codconteudo))){
                        $this->banco->sql_update('Conteudo_Aprovacao', array('tipo' => $tipo, 'cod_colaboradores' => $colaboradores, 'data_atualizacao' => date('Y-m-d H:i:s')), "cod_conteudo = ".$codconteudo);
                    }else{
                        $this->cadastrarNotificacaoAprovacao($codconteudo, $tipo, $colaboradores);
                    }

                }
		return true;
	}
	
	public function aprovarConteudo($codconteudo, $codcolaborador) {
		if ($codconteudo)
			$this->banco->sql_update('Conteudo', array('cod_colaborador' => $codcolaborador, 'situacao' => 1, 'publicado' => 1), "cod_conteudo='".$codconteudo."'");
		return true;
	}

	public function reprovarConteudo($codconteudo, $codcolaborador) {
		if ($codconteudo)
			$this->banco->sql_update('Conteudo', array('cod_colaborador' => $codcolaborador, 'situacao' => 0, 'publicado' => 2), "cod_conteudo='".$codconteudo."'");
		return true;
	}
	
	public function obterListaAprovacao() {
		$lista = array();
		$query = $this->banco->sql_select('t1.cod_conteudo, t1.tipo', 'Conteudo_Aprovacao AS t1 LEFT JOIN Conteudo AS t2 ON (t1.cod_conteudo=t2.cod_conteudo)', '(t1.tipo = 1 OR t1.tipo = 4) AND t2.excluido = 0 AND t2.cod_formato < 5 and t2.cod_sistema=6','','t1.cod_conteudo desc');
		while ($row = $this->banco->fetchArray($query)){
			$lista[] = array('tipo' => 2, 'itens' => array('coditem' => $row['cod_conteudo'], 'tipo' => $row['tipo']));
		}
		return $lista;
	}

}
