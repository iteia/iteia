<?php //print_r($_GET);?>
<script language="javascript" type="text/javascript">
function unificarTags() {
	if ($('#nova_tag').val() == '') {
		$("#nova_tag").attr({ style: 'border: 1px solid #FF0000; background: #FFDFDF'});
	} else {
        //var lista_checkboxes = $("input[@id=cod_tags]");
		var lista_checkboxes = $("input[id=cod_tags]");
		var lista_marcados = new Array;
        <?php
            $palavra = $_GET['palavrachave']; 
        ?>
        var palavra = "<?php echo utf8_encode($palavra);?>";

		for (i = 0; i < lista_checkboxes.length; i++) {
			var item = lista_checkboxes[i];
			if ((item.type == "checkbox") && item.checked && parseInt(item.value))
				lista_marcados.push(item.value);
		}
		//$.get("ajax_conteudo.php?get=unificar_tags&codtag="+lista_marcados.join(",")+"&tag="+$('#nova_tag').val());
        $.post("ajax_conteudo.php",{get:"unificar_tags",codtag:lista_marcados.join(","),tag:$('#nova_tag').val()});
		window.location = 'conteudo_tags.php?palavrachave='+palavra+'&pagina=<?=$_GET["pagina"];?>';
	}
}

function listaTags(){
	var lista_checkboxes = $("input[id=cod_tags]");
	var lista_marcados = new Array;
	var lista_puretext = new Array;
	var atributos;

	for (i = 0; i < lista_checkboxes.length; i++) {
		var item = lista_checkboxes[i];
		if ((item.type == "checkbox") && item.checked && parseInt(item.value)){
			atributos = item.parentNode.parentNode.getElementsByTagName("td");
			lista_puretext.push(atributos[1].firstChild.data);
			lista_marcados.push("<strong>"+atributos[1].firstChild.data+"</strong>");
		}
	}
	
	if(lista_marcados.length <= 1){
		tb_remove();
		alert('Selecione no mínimo 2 tags');
	}else{
		$("#texto_unific").html("As tags "+lista_marcados.join(", ")+" serão unificadas.");
		$("#nova_tag").val(lista_puretext[0]);
	}
}

function onEnter( evt, frm ) {
    var keyCode = null;
    
    if( evt.which ) {
        keyCode = evt.which;
    } else if( evt.keyCode ) {
        keyCode = evt.keyCode;
    }
    if( 13 == keyCode ) {
        frm.btnUnificar.click();
        return false;
    }
    return true;
}
</script>

<form onsubmit="return false;" method="get" id="lightbox">
<fieldset>
<p id="texto_unific"></p>
  <label for="nova_tag" id="label_nova_tag">Defina a tag unficada</label>
  <br />
  <input type="text" class="txt" id="nova_tag" onkeypress="return onEnter(event,this.form);" />
  <br>
  <input type="button" onclick="javascript:unificarTags();" value="Salvar" class="bt-adicionar" name="btnUnificar" id="btnUnificar"/>
  </fieldset>
</form>
</div>
<script>
listaTags();
</script>
