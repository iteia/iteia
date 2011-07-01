<?php
include ('verificalogin.php');

$coditem = (int)$_GET["cod"];
$tipo = (int)$_GET["tipo"];
$cod_texto = (int)$_GET["cod_texto"];

switch ($tipo) {
	case 1: //texto
		include_once("classes/bo/TextoEdicaoBO.php");
		$textobo = new TextoEdicaoBO;
		$textobo->excluirArquivo($coditem);
		break;
}
