<?php
include("verificalogin.php");
include_once("classes/bo/AjaxConteudoBO.php");

if($_POST){
    $ajaxbo = new AjaxConteudoBO($_POST);
}
if($_GET){
    $ajaxbo = new AjaxConteudoBO($_GET);
}
$ajaxbo->executaAcao();
