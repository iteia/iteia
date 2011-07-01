<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Licen&ccedil;as Creative Commons</title>
<style type="text/css" media="screen">
<!--
@import url("css/style.css");
body { background:none;}
-->
</style>
<script type="text/javascript" src="jscripts/jquery-1.2.1.pack.js"></script>
<script type="text/javascript" src="jscripts/scripts.js"></script>
</head>
<body>
<div id="lightbox">
	<fieldset>
        <legend>Qual licen&ccedil;a ser&aacute; usada como padr&atilde;o em todas as obras?</legend>
         <p> Estas configura&ccedil;&otilde;es poder&atilde;o ser substitu&iacute;das para cada conte&uacute;do, individualmente. </p>
        <p>
          <input type="radio" class="radio" name="direitos" id="direitos_0" value="1" onclick="javascript:updateFieldsetLicenca(1);" />
          <label for="direitos_0">Dom&iacute;nio p&uacute;blico</label>
          <br />
          <small>(A obra ficar&aacute; livre para ser distribu&iacute;da sem fins comerciais)</small> </p>
        <p>
          <input type="radio" class="radio" name="direitos" id="direitos_1" value="2" onclick="javascript:updateFieldsetLicenca(2);" />
          <label for="direitos_1">Todos direitos reservados</label>
          (Copyright) <br />
          <small>(Apenas voc&ecirc; ter&aacute; autonomia para ceder ou comercializar esta obra. O arquivo desse conte&uacute;do <strong>n&atilde;o</strong> ser&aacute; disponibilizado para <strong>download</strong> na p&aacute;gina)</small></p>
        <input type="radio" class="radio" name="direitos" id="direitos_2" value="3" onclick="javascript:updateFieldsetLicenca(3);" />
        <label for="direitos_2" id="lbl-alguns-direitos">Alguns direitos reservados (Copyleft)</label>
        <br />
        <small>(Qualquer pessoa poder&aacute; copiar e distribuir essa obra, desde que atribuam o cr&eacute;dito da mesma. O arquivo desse conte&uacute;do ser&aacute; disponibilizado para <strong>download</strong> na p&aacute;gina)</small>
        <div id="alguns-direitos">
          <p><strong>Permitir o uso comercial da obra?</strong></p>
          <input name="cc_usocomercial" type="radio" class="radio" id="radio" value="1" onclick="javascript:updateFieldsetLicenca(4);" />
          <label for="radio">Sim</label>
          <br />
          <input type="radio" class="radio" name="cc_usocomercial" id="radio2" value="2" onclick="javascript:updateFieldsetLicenca(5);" />
          <label for="radio2">N&atilde;o</label>
          <p><strong>Permitir modifica&ccedil;&otilde;es em sua obra?</strong></p>
          <input name="cc_obraderivada" type="radio" class="radio" id="radio3" value="1" onclick="javascript:updateFieldsetLicenca(6);" />
          <label for="radio3">Sim</label>
          <br />
          <input type="radio" class="radio" name="cc_obraderivada" id="radio4" value="2" onclick="javascript:updateFieldsetLicenca(7);" />
          <label for="radio4">Sim, contanto que outros compartilhem com a mesma licen&ccedil;a</label>
          <br />
          <input type="radio" class="radio" name="cc_obraderivada" id="radio5" value="3" onclick="javascript:updateFieldsetLicenca(8);" />
          <label for="radio5">N&atilde;o</label>
        </div>
        </fieldset>
        <br />
        <div><strong>Licen&ccedil;a escolhida:</strong><br />
            <div id="direitos_imagens2" class="direitos_imagens"></div>
      </div>
    </div>

<script type="text/javascript">
getParameters();
var x = parseInt($('#licenca_direitos').val());
var y = parseInt($('#licenca_comercial').val());
var z = parseInt($('#licenca_derivada').val());
atualizaImgLicenca(x, y, z);
</script>
</body>
</html>
