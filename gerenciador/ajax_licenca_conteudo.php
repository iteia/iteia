<?php
include("verificalogin.php");
include_once("classes/bo/DireitosBO.php");
$direitos = $_GET['direitos'];
$comercial = $_GET['comercial'];
$derivada = $_GET['derivada'];

?>
    <img src="img/cc/dp.gif" class="dp" alt="Dom&iacute;nio p&uacute;blico" title="Dom&iacute;nio p&uacute;blico" width="32" height="32" />
    <img src="img/cc/copyright.gif" class="copyright" alt="Todos direitos reservados (Copyright)" title="copiar, distribuir, exibir e executar a obra" width="32" height="32" />
    <img src="img/cc/share.gif" class="share" alt="copiar, distribuir, exibir e executar a obra" title="copiar, distribuir, exibir e executar a obra" width="32" height="32" />
    <img src="img/cc/remix.gif" class="remix" alt="criar obras derivadas" title="criar obras derivadas" width="32" height="32" />
    <img src="img/cc/by.gif" class="by" alt="Atribui&ccedil;&atilde;o" title="Atribui&ccedil;&atilde;o" width="32" height="32" />
    <img src="img/cc/nc.gif" class="nc" alt="Uso N&atilde;o Comercial" title="Uso N&atilde;o Comercial" width="32" height="32" />
    <img src="img/cc/sa.gif" class="sa" alt="Compartilhamento pela mesma Licen&ccedil;a" title="Compartilhamento pela mesma Licen&ccedil;a" width="32" height="32" />
    <img src="img/cc/nomod.gif" class="nomod" alt="Vedada a Cria&ccedil;&atilde;o de Obras Derivadas" title="Vedada a Cria&ccedil;&atilde;o de Obras Derivadas" width="32" height="32" />
    <div class="link"></div>
          
<script type="text/javascript">

		$(".share").hide();
		$(".remix").hide();
		$(".by").hide();
		$(".sa").hide();
		$(".nc").hide();
		$(".nomod").hide();
        $(".dp").hide();
        $(".copyright").hide();
        
	<?php if ( $direitos==1 ) { ?>
		$(".dp").show();
        $(".link").html("Domínio Público");
	<?php } ?>
    
	<?php if ( $direitos==2 ) { ?>
		$(".copyright").show();
        $(".link").html("Todos direitos reservados (Copyright) ");
	<?php } ?>
    
	<?php if ( $direitos==3 && $comercial==2 && $derivada==1 ) { ?>
		$(".share").show();
		$(".remix").show();
		$(".by").show();
		$(".sa").hide();
		$(".nc").show();
		$(".nomod").hide();
		$(".link").html("<a href='http://creativecommons.org/licenses/by-nc/2.5/br/' target='_blank'>Atribuição-Uso Não-Comercial 2.5 Brasil</a>");
		// Atribuição-Uso Não-Comercial 2.5 Brasil
		// http://creativecommons.org/licenses/by-nc/2.5/br/
	<?php } ?>
	
	<?php if ( $direitos==3 && $comercial==2 && $derivada==2 ) { ?>
		$(".share").show();
		$(".remix").show();
		$(".by").show();
		$(".nc").show();
		$(".sa").show();
		$(".nomod").hide();
		$(".link").html("<a href='http://creativecommons.org/licenses/by-nc-sa/2.5/br/' target='_blank'>Atribuição-Uso Não-Comercial-Compartilhamento pela mesma Licença 2.5 Brasil</a>");
		// Atribuição-Uso Não-Comercial-Compartilhamento pela mesma Licença 2.5 Brasil
		// http://creativecommons.org/licenses/by-nc-sa/2.5/br/
	<?php } ?>
		
	<?php if ( $direitos==3 && $comercial==2 && $derivada==3) { ?> // obra derivada Nao
		$(".share").show();
		$(".remix").hide();
		$(".by").show();
		$(".nc").show();
		$(".sa").hide();
		$(".nomod").show();
		$(".link").html("<a href='http://creativecommons.org/licenses/by-nc-nd/2.5/br/' target='_blank'>Atribuição-Uso Não-Comercial-Vedada a Criação de Obras Derivadas 2.5 Brasil</a>");
		// Atribuição-Uso Não-Comercial-Vedada a Criação de Obras Derivadas 2.5 Brasil
		// http://creativecommons.org/licenses/by-nc-nd/2.5/br/
	<?php } ?>

	<?php if ( $direitos==3 && $comercial==1 && $derivada==1 ) { ?> // obra derivada Nao
		$(".share").show();
		$(".remix").show();
		$(".by").show();
		$(".nc").hide();
		$(".sa").hide();
		$(".nomod").hide();
		$(".link").html("<a href='http://creativecommons.org/licenses/by/2.5/br/' target='_blank'>Atribuição 2.5 Brasil</a>");
		// Atribuição 2.5 Brasil
		// http://creativecommons.org/licenses/by/2.5/br/
	<?php } ?>
	
	<?php if ( $direitos==3 && $comercial==1 && $derivada==2 ) { ?> // obra derivada Nao
		$(".share").show();
		$(".remix").show();
		$(".by").show();
		$(".nc").hide();
		$(".sa").show();
		$(".nomod").hide();
		$(".link").html("<a href='http://creativecommons.org/licenses/by-sa/2.5/br/' target='_blank'>Atribuição-Compartilhamento pela mesma Licença 2.5 Brasil</a>");
		// Atribuição-Compartilhamento pela mesma Licença 2.5 Brasil
		// http://creativecommons.org/licenses/by-sa/2.5/br/
	<?php } ?>
	
	<?php if ( $direitos==3 && $comercial==1 && $derivada==3 ) { ?>  // obra derivada Nao
		$(".share").show();
		$(".remix").hide();
		$(".by").show();
		$(".nc").hide();
		$(".sa").hide();
		$(".nomod").show();
		$(".link").html("<a href='http://creativecommons.org/licenses/by-nd/2.5/br/' target='_blank'>Atribuição-Vedada a Criação de Obras Derivadas 2.5 Brasil</a>");
		// Atribuição-Vedada a Criação de Obras Derivadas 2.5 Brasil
		// http://creativecommons.org/licenses/by-nd/2.5/br/
	<?php } ?>

</script>