<?php
include_once('classes/bo/FeedsBO.php');
$feedsbo = new FeedsBO();

$formato = (int)$_GET['formato'];
$usuario = (int)$_GET['usuario'];
$codcanal = (int)$_GET['canal'];
$codconteudo = (int)$_GET['conteudo'];

if ($usuario) {
	echo $feedsbo->getFeedsUsuario($usuario);
} else {
	if ($formato == 5)
		echo $feedsbo->getFeedsNoticias();
	elseif ($formato == 6)
		echo $feedsbo->getFeedsAgenda();
	elseif ($formato == 8)
		echo $feedsbo->getFeedsCanal($codcanal, $codconteudo);
	elseif ($formato < 5)
		echo $feedsbo->getFeedsConteudo($formato);
	//elseif ($formato == 50)
	//	echo $feedsbo->getFeedsImagens($formato);
	else
		echo $feedsbo->getFeedsGeral();
}