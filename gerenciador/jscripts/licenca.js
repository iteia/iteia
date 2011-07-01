function atualizaImgLicenca(direitos, comercial,derivada){
	$.get("ajax_licenca_conteudo.php", { direitos: direitos, comercial: comercial, derivada: derivada}, function(data){
		$('#direitos_imagens1').html(data);
		$('#direitos_imagens2').html(data);
	});
}

function initTextos(){

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

}

function getParameters(){
valor = parseInt($("#licenca_direitos").val());
	switch(valor)
	{
	case 1:
	  $("#direitos_0").attr("checked","checked");
	  break;
	case 2:
	  $("#direitos_1").attr("checked","checked");
	  break;
	case 3:
	  $("#direitos_2").attr("checked","checked");
	  break;
	}
	
valor = parseInt($("#licenca_comercial").val());
	switch(valor)
	{
	case 1:
	  $("#radio").attr("checked","checked");
	  break;
	case 2:
	  $("#radio2").attr("checked","checked");
	  break;
	}
valor = parseInt($("#licenca_derivada").val());
	switch(valor)
	{
	case 1:
	  $("#radio3").attr("checked","checked");
	  break;
	case 2:
	  $("#radio4").attr("checked","checked");
	  break;
	case 3:
	  $("#radio5").attr("checked","checked");
	  break;
	}
}

function updateFieldsetLicenca(cod){
	switch(cod)
	{
	case 1:
	  $("#licenca_direitos").val("1");
	  $("#licenca_comercial").val("0");
	  $("#licenca_derivada").val("0")
	  break;
	case 2:
	  $("#licenca_direitos").val("2");
	  $("#licenca_comercial").val("0");
	  $("#licenca_derivada").val("0");
	  break;
	case 3:
	  $("#licenca_direitos").val("3");
	  $("#licenca_comercial").val("0");
	  $("#licenca_derivada").val("0");
	  break;
	case 4:
	  $("#licenca_comercial").val("1");
	  break;
	case 5:
	  $("#licenca_comercial").val("2");
	  break;
	case 6:
	  $("#licenca_derivada").val("1");
	  break;
	case 7:
	  $("#licenca_derivada").val("2");
	  break;
	case 8:
	  $("#licenca_derivada").val("3");
	  break;
	}
	getParameters();
	atualizaImgLicenca(parseInt($('#licenca_direitos').val()), parseInt($('#licenca_comercial').val()), parseInt($('#licenca_derivada').val()));
}