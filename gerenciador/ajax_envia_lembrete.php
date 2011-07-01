<?php
include('verificalogin.php');
include_once("classes/vo/ConfigGerenciadorVO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");
include_once("classes/bo/LoginBO.php");
$loginbo = new LoginBO;

$dados = array();
$dados['buscarpor'] = $_GET['lembrete'];
$dados['lembrar'] = 'senha'; 

$lembrar = $loginbo->lembrarAcesso($dados, true);

echo $loginbo->getErroMsg();

exit;
