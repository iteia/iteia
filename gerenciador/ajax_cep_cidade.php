<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."dao/CidadeDAO.php");

$cidade = $_GET['cidade'];
$cidade = strtoupper(Util::removeAcentos($cidade));

$cidDao = new CidadeDAO;
echo $cidDao->getCodCidade($cidade, (int)$_GET['codestado']);
die;