<?php
include_once("ConexaoDB.php");

class Newsletter_ListaDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}
	
	public function getCountLista($titulo, $codlista=0) {
		$query = $this->banco->executaQuery("SELECT titulo FROM Home_Newsletter_Listas WHERE titulo='".$titulo."' AND excluido='0' AND cod_lista!='".$codlista."'");
		$row = mysql_fetch_row($query);
		return $row[0];
	}
	
	public function cadastrar(&$newsvo) {
		$this->banco->sql_insert('Home_Newsletter_Listas', array(
			'titulo'		=> $newsvo->getTitulo(),
			'data_hora'		=> $newsvo->getDataHora()
		));
		
		$codlista = mysql_insert_id();
		
		return $codlista;
	}
	
	public function editar(&$newsvo) {
	
	}
	
	public function getNewsletterUsuariosListasBusca(&$dadosform, $inicial, $mostrar) {
		$lista = array();
		
        $descadastrados = implode(",",$this->listDescadastrados());
            $where = " AND t1.cod_usuario not in ($descadastrados)";
		if ($dadosform['codlista'])
			$where .= " AND t2.cod_lista='".$dadosform['codlista']."'";
		if ($dadosform['email'])
			$where .= " AND t1.email LIKE '%".$dadosform['email']."%'";
		if ($dadosform['titulo'])
			$where .= " AND t3.titulo LIKE '%".$dadosform['titulo']."%'";
		
		$sql = "FROM Home_Newsletter_Usuarios AS t1 LEFT JOIN Home_Newsletter_Usuarios_Lista AS t2 ON (t1.cod_usuario=t2.cod_usuario) RIGHT JOIN Home_Newsletter_Listas AS t3 ON (t2.cod_lista=t3.cod_lista) WHERE t1.excluido='0' ".$where." ORDER BY t3.titulo ASC";
		
		$query = $this->banco->executaQuery('SELECT COUNT(1) '.$sql);
		$row = mysql_fetch_row($query);
		$lista['total'] = $row[0];
		
		$sql_result = $this->banco->executaQuery("SELECT t1.cod_usuario, t1.nome, t1.email, t2.cod_lista, t3.titulo ".$sql." LIMIT ".$inicial.",".$mostrar);
		$lista['resultado'] = array();
		while ($sql_row = $this->banco->fetchArray($sql_result)) {
			$lista['resultado'][$sql_row['cod_lista']][] = array(
						'cod_usuario' 	=> $sql_row['cod_usuario'],
						'cod_lista' 	=> $sql_row['cod_lista'],
						'lista'			=> $sql_row['titulo'],
						'nome' 			=> $sql_row['nome'],
						'email' 		=> $sql_row['email'],
					);
		}	
		
		return $lista;
	}
	
	public function getEmailsLista($nomelista) {
		$array = array();
		$sql = "SELECT t1.email FROM Home_Newsletter_Usuarios AS t1 INNER JOIN Home_Newsletter_Usuarios_Lista AS t2 ON (t1.cod_usuario=t2.cod_usuario) LEFT JOIN Home_Newsletter_Listas AS t3 ON (t2.cod_lista=t3.cod_lista) WHERE t1.excluido='0' AND t3.excluido='0' AND t3.titulo='".$nomelista."' AND t1.cod_usuario not in (SELECT cod_usuario from Home_Newsletter_Usuarios_Descadastrados)";
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result))
			$array[] = $sql_row['email'];
		return $array;
	}

	public function getTodosEmailsLista($nomelista) {
		$array = array();
		//$sql = "SELECT email FROM Usuarios WHERE disponivel = '1' AND situacao = '3' AND email != ''";
        $sql = "SELECT t1.email FROM Usuarios AS t1 LEFT JOIN Home_Newsletter_Usuarios AS t2 ON t1.email=t2.email LEFT JOIN Home_Newsletter_Usuarios_Descadastrados AS t3 ON t2.cod_usuario=t3.cod_usuario WHERE t1.disponivel = '1' AND t1.situacao = '3' AND t1.email != '' AND t3.cod_usuario IS NULL ";
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result))
			$array[] = $sql_row['email'];
		return $array;
	}
    
	public function getCodLista($nomelista) {
		$array = array();
		$sql = "SELECT t1.cod_lista FROM Home_Newsletter_Listas AS t1 WHERE t1.excluido='0' AND t1.titulo='".$nomelista."'";
		$sql_result = $this->banco->executaQuery($sql);
		while ($sql_row = $this->banco->fetchArray($sql_result))
			$cod = $sql_row['cod_lista'];
		return $cod;
	}
	
	public function getListas() {
		$array = array();
		$query = $this->banco->executaQuery("SELECT * FROM Home_Newsletter_Listas WHERE excluido='0' ORDER BY titulo");
		while ($row = $this->banco->fetchArray($query))
			$array[] = $row;
		return $array;
	}
	
	public function getListaUsuarios($codcliente) {
		return $this->banco->executaQueryFetchRowSet($this->banco->executaQuery("SELECT * FROM Newsletter_Usuarios WHERE cod_cliente='".$codcliente."' AND excluido='0' ORDER BY email"));
	}
	
    /*
	public function apagarTodaLista($codlista) {
		$this->banco->sql_delete('Home_Newsletter_Usuarios_Lista', "cod_lista='".$codlista."'");
	}
    */
	
	public function apagarUsuarioLista($codlista, $codusuario) {
		$this->banco->sql_delete('Home_Newsletter_Usuarios_Lista', "cod_usuario='".$codusuario."' AND cod_lista='".$codlista."'");
	}
    
	public function apagarLista($codlista) {
		$this->banco->sql_update('Home_Newsletter_Listas', array("excluido"=>'1'), "cod_lista='".$codlista."'");
	}
	
    public function cadastrarAssinatura($email, $lista = 0){
        $this->banco->sql_insert('Home_Newsletter_Usuarios', array(
			'email'		=> $email,
            'data_hora' => date("Y-m-d H:i:s")
		));
        $id = $this->banco->insertId();
        
        if($lista == 0)
            $lista = 8;
            
        $this->banco->sql_insert('Home_Newsletter_Usuarios_Lista', array(
			'cod_usuario'	=> $id,
            'cod_lista'     => $lista
		));
    }
    
    public function existeAssinatura($email){
		$sql = "SELECT * FROM Home_Newsletter_Usuarios WHERE excluido = '0' AND email = '$email'";
		$sql_result = $this->banco->executaQuery($sql);
		$cont = $this->banco->numRows();
        if($cont>0)
            return true;
		return false;
    }
    
    public function descadastrarAssinatura($email,$motivo){
		$sql = "SELECT cod_usuario FROM Home_Newsletter_Usuarios WHERE excluido = '0' AND email = '$email'";
		$sql_result = $this->banco->executaQuery($sql);
        $l = $this->banco->fetchArray($sql_result);
        $id = $l['cod_usuario'];
        
        //print_r("insert into Home_Newsletter_Usuarios_Descadastrados(cod_usuario,motivo) values('$id', '".implode(",",$motivo)."')");die;
        $this->banco->sql_insert('Home_Newsletter_Usuarios_Descadastrados', array(
			'cod_usuario'	=> $id,
            'motivo'        => implode(",",$motivo)
		));
    }
    
    public function listDescadastrados(){
        $lista = array();
        $sql = "SELECT cod_usuario FROM Home_Newsletter_Usuarios_Descadastrados";
		$sql_result = $this->banco->executaQuery($sql);
        while($l = $this->banco->fetchArray($sql_result)){
            $lista[] = $l['cod_usuario'];
        }
        if(!$lista)
            return array(0);
        return $lista;
    }
    
	public function getNewsletterUsuariosDescadastradosBusca(&$dadosform, $inicial, $mostrar) {
		$lista = array();
		
		if ($dadosform['codlista'])
			$where .= " AND t2.cod_lista='".$dadosform['codlista']."'";
		if ($dadosform['email'])
			$where .= " AND t1.email LIKE '%".$dadosform['email']."%'";
		if ($dadosform['titulo'])
			$where .= " AND t3.titulo LIKE '%".$dadosform['titulo']."%'";
		
        $sql = "FROM Home_Newsletter_Usuarios_Descadastrados as t4 left join Home_Newsletter_Usuarios AS t1 ON (t4.cod_usuario=t1.cod_usuario) LEFT JOIN Home_Newsletter_Usuarios_Lista AS t2 ON (t1.cod_usuario=t2.cod_usuario) RIGHT JOIN Home_Newsletter_Listas AS t3 ON (t2.cod_lista=t3.cod_lista) WHERE t1.excluido='0' ".$where." ORDER BY t1.email ASC";
		
		$query = $this->banco->executaQuery('SELECT COUNT(1) '.$sql);
		$row = mysql_fetch_row($query);
		$lista['total'] = $row[0];
		
		$sql_result = $this->banco->executaQuery("SELECT t1.cod_usuario, t1.nome, t1.email, t2.cod_lista, t3.titulo, t4.motivo, t4.data_descadastro ".$sql." LIMIT ".$inicial.",".$mostrar);
		$lista['resultado'] = array();
        
		while ($sql_row = $this->banco->fetchArray($sql_result)) {
            
            $listmotivo = explode(',',$sql_row['motivo']);
            $motivos = array();
            foreach($listmotivo as $motivo){
                switch($motivo){
                    case 1:
                        $motivos[] = "Frequ&ecirc;ncia";
                        break;
                    case 2:
                        $motivos[] = "N&atilde;o autorizado";
                        break;
                    case 3:
                        $motivos[] = "Desinteressante";
                        break;
                }
            }
            
			$lista['resultado'][] = array(
						'cod_usuario' 	=> $sql_row['cod_usuario'],
						'lista'			=> $sql_row['titulo'],
						'email' 		=> $sql_row['email'],
                        'motivo' 		=> implode(', ',$motivos),
                        'data_descadastro'	=> $sql_row['data_descadastro']
					);
		}	
		
		return $lista;
	}
    
    public function desbloquearUsuario($cods){
        $lista = implode(",", $cods);
        $sql = "DELETE FROM Home_Newsletter_Usuarios_Descadastrados WHERE cod_usuario in ($lista)";
		$sql_result = $this->banco->executaQuery($sql);
    }
}
