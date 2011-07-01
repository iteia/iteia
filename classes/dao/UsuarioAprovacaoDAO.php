<?php
include_once('ConexaoDB.php');

class UsuarioAprovacaoDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}

	public function cadastrarNotificacaoAprovacao($codusuario, $tipo) {
		if ($codusuario)
			$this->banco->sql_insert('Usuarios_Aprovacao', array('cod_usuario' => $codusuario, 'tipo' => $tipo, 'data_cadastro' => date('Y-m-d H:i:s')));
		return true;
	}

	public function editarNotificacaoAprovacao($codusuario, $tipo, $codusuario_logado, $observacao) {
		if ($codusuario)
			$this->banco->sql_update('Usuarios_Aprovacao', array('cod_usuario_logado' => $codusuario_logado, 'tipo' => $tipo, 'observacao' => $observacao, 'data_atualizacao' => date('Y-m-d H:i:s')), "cod_usuario = ".$codusuario);
		return true;
	}
	
	public function aprovarUsuario($codusuario) {
		if ($codusuario)
			$this->banco->sql_update('Usuarios', array('situacao' => 3, 'disponivel' => 1), "cod_usuario = ".$codusuario);
		return true;
	}

	public function reprovarUsuario($codusuario) {
		if ($codusuario) {
			$this->banco->sql_update('Usuarios', array('situacao' => 2, 'disponivel' => 0), "cod_usuario = ".$codusuario);
			$this->banco->sql_delete('Urls', "cod_item = ".$codusuario." AND tipo = 2 AND cod_sistema = ".ConfigVO::getCodSistema());
		}
		return true;
	}
	
	public function obterListaAprovacao() {
		$lista = array();
		//$query = $this->banco->sql_select('cod_usuario', 'Usuarios_Aprovacao', 'tipo = 1');
		$query = $this->banco->sql_select('t1.cod_usuario', 'Usuarios_Aprovacao AS t1 LEFT JOIN Urls AS t2 ON t1.cod_usuario=t2.cod_item', 't1.tipo = 1 AND (t2.tipo = 1 OR t2.tipo = 2) AND t2.cod_sistema = 6','','t1.cod_usuario desc');
		while ($row = $this->banco->fetchArray($query))
			$lista[] = array('tipo' => 1, 'itens' => array('coditem' => $row['cod_usuario'], 'tipo' => 1));
		return $lista;
	}
    
    public function usuarioEstaAprovado($cod){
        $query = $this->banco->sql_select('tipo', 'Usuarios_Aprovacao', 'cod_usuario = '.$cod);
        $row = $this->banco->fetchArray($query);
        
        if($row['tipo'] == '1')
            return false;
        
        return true;
        
    }

}
