<?php
include("verificalogin.php");

include_once("classes/bo/ImagemTemporariaBO.php");
ImagemTemporariaBO::exibirProporcional($_GET["img"]);
