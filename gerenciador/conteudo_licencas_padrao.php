<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz().'util/Util.php');
include_once('classes/bo/DireitosBO.php');

$licencabo = new DireitosBO();

if ($editar) {
	try {
		$licencabo->editar($_POST);
	} catch (Exception $e) {
		$erro_mensagens = $e->getMessage();
	}
}

$licencaDados = $licencabo->getLicencaPadrao($_SESSION['logado_cod']);

$paginatitulo = 'Licen&ccedil;as';
$item_menu = "conteudo";
$item_submenu = "licenca";
include('includes/topo.php');

?>

<h3 class="titulo">Configura&ccedil;&atilde;o das licen&ccedil;as </h3>
<div class="box" id="direitos">
	
    <p class="dica">O iTEIA permite a licenca de conte&uacute;dos em copyleft utilizando licen&ccedil;as Creative Commons,  mantendo  direitos autorais e posibilitando a outros copiar e distribuir sua obra, contanto que atribuam cr&eacute;dito  e somente sob as condi&ccedil;&otilde;es especificadas. <a href="conteudo_licencas.php?height=400&amp;width=550" title="Licen&ccedil;as Creative Commons"  class="thickbox">Saiba mais sobre as licen&ccedil;as</a></p>
	<form action="conteudo_licencas_padrao.php" method="post">
        <fieldset>
			<input type="hidden" name="editar" value="1" />
			<?php if($licencaDados['cod_usuario']): ?>
			<input type="hidden" name="edicaodados" value="<?=$licencaDados['cod_usuario']?>" />
			<?php endif; ?>
        <legend>Qual licen&ccedil;a ser&aacute; usada como padr&atilde;o em todas as obras?</legend>
         <p> Estas configura&ccedil;&otilde;es poder&atilde;o ser substitu&iacute;das para cada conte&uacute;do, individualmente. </p>
        <p>
        <input name="direitos" type="radio" id="direitos_0" value="1" <?=($licencaDados['licenca']['direitos'] == 1)?" checked=\"checked\"":""?> />
      	<label  for="direitos_0">Dom&iacute;nio p&uacute;blico</label>
      	<br />
          <small>(A obra ficar&aacute; livre para ser distribu&iacute;da sem fins comerciais)</small> </p>
    	<p>
      	<input name="direitos" type="radio" value="2" id="direitos_1" <?=($licencaDados['licenca']['direitos'] == 2)?" checked=\"checked\"":""?> />
      	<label for="direitos_1">Todos direitos reservados (Copyright)</label>
      	<br />
            <small>(Apenas voc&ecirc; ter&aacute; autonomia para ceder ou comercializar esta obra. O arquivo desse conte&uacute;do <strong>n&atilde;o</strong> ser&aacute; disponibilizado para <strong>download</strong> na p&aacute;gina)</small></p>
      	<input name="direitos" type="radio" id="direitos_2" value="3" <?=($licencaDados['licenca']['direitos'] == 3)?" checked=\"checked\"":""?> />
      	<label for="direitos_2" id="lbl-alguns-direitos">Alguns direitos reservados (Copyleft)</label><br />
      	<small>(Qualquer pessoa poder&aacute; copiar e distribuir essa obra, desde que atribuam o cr&eacute;dito da mesma. O arquivo desse conte&uacute;do ser&aacute; disponibilizado para <strong>download</strong> na p&aacute;gina)</small>
        <div id="alguns-direitos" class="display-none"> 
        <p><strong>Permitir o uso comercial da obra?</strong></p>
      <input name="cc_usocomercial" type="radio" id="radio" value="1" <?=($licencaDados['licenca']['cc_usocomercial'] == 1)?" checked=\"checked\"":""?> />
      <label for="radio">Sim</label>
      <br />
      <input type="radio" name="cc_usocomercial" id="radio2" value="2" <?=($licencaDados['licenca']['cc_usocomercial'] == 2)?" checked=\"checked\"":""?> />
      <label for="radio2">Não</label>
      <p><strong>Permitir modifica&ccedil;&otilde;es em sua obra?</strong></p>
      <input name="cc_obraderivada" type="radio" id="radio3" value="1" <?=($licencaDados['licenca']['cc_obraderivada'] == 1)?" checked=\"checked\"":""?> />
      <label for="radio3">Sim</label>
      <br />
      <input type="radio" name="cc_obraderivada" id="radio4" value="2" <?=($licencaDados['licenca']['cc_obraderivada'] == 2)?" checked=\"checked\"":""?> />
      <label for="radio4">Sim, contanto que outros compartilhem com a mesma licen&ccedil;a</label>
      <br />
      <input type="radio" name="cc_obraderivada" id="radio5" value="3" <?=($licencaDados['licenca']['cc_obraderivada'] == 3)?" checked=\"checked\"":""?> />
      <label for="radio5">N&atilde;o</label>
      </div>
      </fieldset>
        <br />
        <div id="licencas"><strong>A licença desta obra ser&aacute;:</strong><br />
		<img src="img/cc/dp.gif" id="dp" alt="Dom&iacute;nio p&uacute;blico" title="Dom&iacute;nio p&uacute;blico" width="32" height="32" />
        <img src="img/cc/copyright.gif" id="copyright" alt="Todos direitos reservados (Copyright)" title="copiar, distribuir, exibir e executar a obra" width="32" height="32" />
        <img src="img/cc/share.gif" id="share" alt="copiar, distribuir, exibir e executar a obra" title="copiar, distribuir, exibir e executar a obra" width="32" height="32" />
        <img src="img/cc/remix.gif" id="remix" alt="criar obras derivadas" title="criar obras derivadas" width="32" height="32" />
        <img src="img/cc/by.gif" id="by" alt="Atribui&ccedil;&atilde;o" title="Atribui&ccedil;&atilde;o" width="32" height="32" />
        <img src="img/cc/nc.gif" id="nc" alt="Uso N&atilde;o Comercial" title="Uso N&atilde;o Comercial" width="32" height="32" />
        <img src="img/cc/sa.gif" id="sa" alt="Compartilhamento pela mesma Licen&ccedil;a" title="Compartilhamento pela mesma Licen&ccedil;a" width="32" height="32" />
        <img src="img/cc/nomod.gif" id="nomod" alt="Vedada a Cria&ccedil;&atilde;o de Obras Derivadas" title="Vedada a Cria&ccedil;&atilde;o de Obras Derivadas" width="32" height="32" />
          <div id="link"></div> 
          </div>
		<br />
        <input type="submit" class="bt-gravar" value="Gravar" />
	</form>
</div>
<script type="text/javascript">

$(document).ready(function(){
	if ( $('#direitos_0, #direitos_1').is(':checked') ) {
		$("#lbl-alguns-direitos").html( "Alguns direitos reservados (Copyleft) " );
		$("#alguns-direitos").slideUp("slow");
		$("#alguns-direitos input:radio").attr("checked","");
		//$("#licencas img").hide();
		$("#link").html(" ");
	}

	if ( $('#direitos_0').is(':checked') ) {
		$("#dp").show();
	}
	if ( $('#direitos_1').is(':checked') ) {
		$("#copyright").show();
	}
	if ( $('#direitos_0, #direitos_2').is(':checked') ) {
		$("#copyright").hide();
	}

	if ( $('#direitos_2').is(':checked') ) {
		$("#lbl-alguns-direitos").html( "Alguns direitos reservados (Copyleft) " + " (<strong class='green'>Responda as perguntas abaixo</strong>) " );
		$("#alguns-direitos").slideDown("slow");
		//$("#licencas img").hide();
	}

	if ( $('#radio2').is(':checked') && $('#radio3').is(':checked') ) {
		$("#share").show();
		$("#remix").show();
		$("#by").show();
		$("#sa").hide();
		$("#nc").show();
		$("#nomod").hide();
		$("#link").html("<a href='http://creativecommons.org/licenses/by-nc/2.5/br/' target='_blank'>Atribuição-Uso Não-Comercial 2.5 Brasil</a>");
		// Atribuição-Uso Não-Comercial 2.5 Brasil
		// http://creativecommons.org/licenses/by-nc/2.5/br/
	}
	
	if ( $('#radio2').is(':checked') && $('#radio4').is(':checked') ) {
		$("#share").show();
		$("#remix").show();
		$("#by").show();
		$("#nc").show();
		$("#sa").show();
		$("#nomod").hide();
		$("#link").html("<a href='http://creativecommons.org/licenses/by-nc-sa/2.5/br/' target='_blank'>Atribuição-Uso Não-Comercial-Compartilhamento pela mesma Licença 2.5 Brasil</a>");
		// Atribuição-Uso Não-Comercial-Compartilhamento pela mesma Licença 2.5 Brasil
		// http://creativecommons.org/licenses/by-nc-sa/2.5/br/
	}
		
	if ( $('#radio2').is(':checked') && $('#radio5').is(':checked') ) { // obra derivada Nao
		$("#share").show();
		$("#remix").hide();
		$("#by").show();
		$("#nc").show();
		$("#sa").hide();
		$("#nomod").show();
		$("#link").html("<a href='http://creativecommons.org/licenses/by-nc-nd/2.5/br/' target='_blank'>Atribuição-Uso Não-Comercial-Vedada a Criação de Obras Derivadas 2.5 Brasil</a>");
		// Atribuição-Uso Não-Comercial-Vedada a Criação de Obras Derivadas 2.5 Brasil
		// http://creativecommons.org/licenses/by-nc-nd/2.5/br/
	}

	if ( $('#radio').is(':checked') && $('#radio3').is(':checked') ) { // obra derivada Nao
		$("#share").show();
		$("#remix").show();
		$("#by").show();
		$("#nc").hide();
		$("#sa").hide();
		$("#nomod").hide();
		$("#link").html("<a href='http://creativecommons.org/licenses/by/2.5/br/' target='_blank'>Atribuição 2.5 Brasil</a>");
		// Atribuição 2.5 Brasil
		// http://creativecommons.org/licenses/by/2.5/br/
	}
	
	if ( $('#radio').is(':checked') && $('#radio4').is(':checked') ) { // obra derivada Nao
		$("#share").show();
		$("#remix").show();
		$("#by").show();
		$("#nc").hide();
		$("#sa").show();
		$("#nomod").hide();
		$("#link").html("<a href='http://creativecommons.org/licenses/by-sa/2.5/br/' target='_blank'>Atribuição-Compartilhamento pela mesma Licença 2.5 Brasil</a>");
		// Atribuição-Compartilhamento pela mesma Licença 2.5 Brasil
		// http://creativecommons.org/licenses/by-sa/2.5/br/
	}
	
	if ( $('#radio').is(':checked') && $('#radio5').is(':checked') ) { // obra derivada Nao
		$("#share").show();
		$("#remix").hide();
		$("#by").show();
		$("#nc").hide();
		$("#sa").hide();
		$("#nomod").show();
		$("#link").html("<a href='http://creativecommons.org/licenses/by-nd/2.5/br/' target='_blank'>Atribuição-Vedada a Criação de Obras Derivadas 2.5 Brasil</a>");
		// Atribuição-Vedada a Criação de Obras Derivadas 2.5 Brasil
		// http://creativecommons.org/licenses/by-nd/2.5/br/
	}
});
</script>

<?php include('includes/rodape.php'); ?>
