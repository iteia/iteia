<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$codconteudo = (int)$_GET['cod'];
$cod_textoS = (int)$_GET['cod_texto']
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Enviar para lista p&uacute;blica</title>
<style type="text/css" media="screen">
<!--
@import url("css/style.css");
body { background:none;}
-->
</style>

<script type="text/javascript" src="jscripts/funcoes.js"></script>

</head>
<body>
<div id="confirmado" style="display:none;">
<p>Conte&uacute;do enviado para lista P&uacute;blica</p>
<input type="button" value="Fechar" class="bt-nao" onclick="avascript:tb_remove()" />
</div>

<form action="" method="get" id="lightbox">
<p>Deseja realmente remover o arquivo anexado?</p>
<input type="button" value="Sim" class="bt-sim" onclick="javascript:apagarArquivo('<?=$codconteudo?>', '1','<?=$cod_textoS?>');" />
<input type="button" value="N&atilde;o" class="bt-nao" onclick="avascript:tb_remove()" />
</form>

</body>
</html>