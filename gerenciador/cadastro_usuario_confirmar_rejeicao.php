<?php
include('verificalogin.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Enviar para lista p&uacute;blica</title>
<style type="text/css" media="screen">
<!--
@import url("css/style.css");
body { background:none; }
-->
</style>
</head>
<body>
<form action="" method="get" id="lightbox">
<label for="textarea">Qual o motivo da rejeição?</label><br />
<input type="hidden" value="<?=(int)$_GET['cod'];?>" name="codusuario" id="codusuario" />
<textarea id="motivo" class="txt" name="motivo" cols="40" rows="10"></textarea><br />
<p>Deseja realmente rejeitar este cadastro?</p>
<input type="button" value="Sim" class="bt-sim" onclick="javascript:reprovarUsuario();" />
<input type="button" value="N&atilde;o" class="bt-nao" onclick="tb_remove()" />
</form>
</body>
</html>