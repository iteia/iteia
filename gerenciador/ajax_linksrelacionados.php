<?php
include('verificalogin.php');
include_once("classes/vo/ConfigGerenciadorVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

if (!$_SESSION['sites_relacionados'])
	$_SESSION['sites_relacionados'] = array();
	
$cod = (int)$_GET['cod'];	
$titulo = trim($_POST['titulo']);
$endereco = trim($_POST['endereco']);
$acao = $_GET['acao'];

if ($acao == 'adicionar') {
	if ($titulo && $endereco && $endereco != 'http://')
		$_SESSION['sites_relacionados'][] = array('titulo' => $titulo, 'endereco' => Util::VerificaHttp($endereco));
	$acao = 'listar';
}

if ($acao == 'remover') {
	unset($_SESSION['sites_relacionados'][$cod]);
	$acao = 'listar';
}

if ($acao == 'listar') {
	foreach ($_SESSION['sites_relacionados'] as $key => $value) {
		echo "<li><span>".$value['titulo']." - ".$value['endereco']."</span> <a href=\"javascript:removerSiteRelacionado('".$key."');\" title=\"Remover\" class=\"remover\">Remover</a></li>\n";
	}
}
