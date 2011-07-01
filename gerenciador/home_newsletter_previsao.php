<?php
include("verificalogin.php");

include_once("classes/bo/NewsletterBO.php");
$newsbo = new NewsletterBO;

echo utf8_encode($newsbo->getPrevisaoInterna($_GET['cod']));
