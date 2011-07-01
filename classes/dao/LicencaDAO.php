<?php
include_once('ConexaoDB.php');

class LicencaDAO {

	protected $banco = null;

	public function __construct() {
		$this->banco = ConexaoDB::singleton();
	}
	
	public function getLicencaPadrao($cod){
		$sql = "SELECT cod_usuario, cod_licenca as licenca FROM Usuarios_Licenca_Padrao WHERE cod_usuario = '$cod'";
		$query = $this->banco->executaQuery($sql);
		$row = $this->banco->fetchArray();
		return $row;
	}
	
	public function update($cod_licenca, $cod_usuario){
		$sql = "UPDATE Usuarios_Licenca_Padrao SET cod_licenca=$cod_licenca WHERE cod_usuario=$cod_usuario";
		$query = $this->banco->executaQuery($sql);
	}
	public function insert($cod_licenca){
		$sql = "insert into Usuarios_Licenca_Padrao values(".$_SESSION['logado_cod'].", $cod_licenca)";
		$query = $this->banco->executaQuery($sql);
	}
	//public function lembrarAcesso($buscarpor, $lembrar) {
	//	if ($lembrar == 'senha') $where = " AND login='".$buscarpor."'";
	//	if ($lembrar == 'login') $where = " AND email='".$buscarpor."'";
	//	if (!$buscarpor) return false;
	//	
	//	$sql = "SELECT cod_usuario, email, nome, login, senha FROM Usuarios WHERE disponivel='1' AND situacao='3' AND email != '' AND login != '' AND cod_sistema='".ConfigVO::getCodSistema()."' $where";
	//	$query = $this->banco->executaQuery($sql);
	//	//return $row = $this->banco->fetchArray();
	//	$list = array();
	//	while($row = $this->banco->fetchArray()){
	//		$list[] = $row;
	//	}
	//	return $list;
	//}
	//
	//public function lembrarAcessoCod($buscarpor, $lembrar) {
	//	if ($lembrar == 'senha') $where = " AND cod_usuario in (".$buscarpor.")";
	//	if (!$buscarpor) return false;
	//	
	//	$sql = "SELECT cod_usuario, email, nome, login, senha FROM Usuarios WHERE disponivel='1' AND situacao='3' AND email != '' AND login != '' AND cod_sistema='".ConfigVO::getCodSistema()."' $where order by cod_usuario DESC";
	//	$query = $this->banco->executaQuery($sql);
	//	
	//	$list = array();
	//	while($row = $this->banco->fetchArray()){
	//		$list[] = $row;
	//	}
	//	return $list;
	//}
	//
	//public function checaLogin($login, $senha) {
	//	$sql = "SELECT U.cod_usuario, U.nome, U.login, U.senha, U.cod_tipo, UN.nivel FROM Usuarios U, Usuarios_Niveis UN WHERE U.cod_usuario = UN.cod_usuario and U.login='".$login."' AND U.senha='".$senha."' AND U.disponivel='1' AND U.situacao='3' AND U.cod_sistema='".ConfigVO::getCodSistema()."' AND U.cod_tipo = 2 and UN.nivel in (2, 5, 6, 7, 8);";
	//	$query = $this->banco->executaQuery($sql);
	//	if ($this->banco->numRows()) {
	//		$row = $this->banco->fetchObject();
	//
	//		$return['cod'] = $row->cod_usuario;
	//		$return['login'] = $row->login;
	//		$return['senha'] = $row->senha;
	//		$return['nome'] = $row->nome;
	//		$return['nivel'] = $row->nivel;
	//
	//		$sql = "select t1.cod_colaborador, t1.responsavel from Colaboradores_Integrantes AS t1 LEFT JOIN Usuarios AS t2 ON (t1.cod_colaborador=t2.cod_usuario) where t1.cod_autor = '".$return['cod']."' AND t2.disponivel='1' AND t2.situacao='3' LIMIT 1;";
	//		$query = $this->banco->executaQuery($sql);
	//		$row = $this->banco->fetchArray();
	//		$return['cod_colaborador'] = $row['cod_colaborador'];
	//		$return['colaborador_responsavel'] = $row['responsavel'];
	//		return $return;
	//	}
	//}

}