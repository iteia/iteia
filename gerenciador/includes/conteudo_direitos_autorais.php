<?php
include_once('classes/bo/DireitosBO.php');
$dirbo = new DireitosBO;
if($_GET['alterar']){
		$direitos = $_GET['direitos'];
		$cc_usocomercial = $_GET['cc_usocomercial'];
		$cc_obraderivada = $_GET['cc_obraderivada'];
}else{
		$direitos = $contbo->getValorCampo("direitos");
		$cc_usocomercial = $contbo->getValorCampo("cc_usocomercial");
		$cc_obraderivada = $contbo->getValorCampo("cc_obraderivada");
}

$codlicenca = $dirbo->getCodLicenca($direitos, $cc_usocomercial, $cc_obraderivada);

?>
<div class="box" id="direitos">
        <fieldset>
		<input type="hidden" name="direitos" id="licenca_direitos" value="<?=$direitos;?>" />
		<input type="hidden" name="cc_usocomercial" id="licenca_comercial" value="<?=$cc_usocomercial;?>" />
		<input type="hidden" name="cc_obraderivada" id="licenca_derivada" value="<?=$cc_obraderivada;?>" />
		<legend>Direitos autorais</legend>
		</fieldset>
        <p class="dica">O iTEIA permite a licenca de conte&uacute;dos em copyleft utilizando licen&ccedil;as Creative Commons,  mantendo  direitos autorais e posibilitando a outros copiar e distribuir sua obra, contanto que atribuam cr&eacute;dito  e somente sob as condi&ccedil;&otilde;es especificadas. <a href="conteudo_licencas.php?height=400&amp;width=550" title="Licen&ccedil;as Creative Commons"  class="thickbox">Saiba mais sobre as licen&ccedil;as</a></p>
        <div id=""><strong>A licen&ccedil;a desta obra ser&aacute;:</strong>
            <small><a href="conteudo_licenca.php?height=550&amp;width=600" title="Licen&ccedil;as Creative Commons"  class="thickbox">(Altere a licença desta obra)</a></small><br />
            <div id="direitos_imagens1" class="direitos_imagens"></div>
        </div>
</div>
<script type="text/javascript" src="jscripts/licenca.js"></script>
<script type="text/javascript">
    var x = parseInt($('#licenca_direitos').val());
    var y = parseInt($('#licenca_comercial').val());
    var z = parseInt($('#licenca_derivada').val());
	atualizaImgLicenca(x,y,z);
</script> 
