<?php
include('verificalogin.php');

$tipo = $_GET['tipo'];
$cod = (int)$_GET["cod"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Criar miniatura</title>
<!--<script type="text/javascript" src="jscripts/jquery-1.2.1.pack.js"></script>
-->
<script type="text/javascript" src="jscripts/crop/js/jquery.imgareaselect.min.js"></script>

<script type="text/javascript">

function vai() {

options = {
		url: "conteudo_ajax.php?get=imagem_conteudo_upload",
		target: "#imagem_html",
		type: "post",
		success: reexibeImagem
	};
	$('#photo').ajaxSubmit(options);
}

function reexibeImagem() {
	var imagemtemp = $('#imagem_html').html();
	if (imagemtemp.length) {
		$('.thumbnail').attr("src","exibir_imagem_temp_proporcional.php?img="+imagemtemp);
		$('#croppedimage').val(imagemtemp);
		//var html_img = '<img src="exibir_imagem_temp.php?img=' + imagemtemp + '" id="imagem_exibicao" width="124" height="124" alt="" />';
		//$('#div_imagem_exibicao').html(html_img);
		//$('#imgtemp').val(imagemtemp);
	}
	//tb_remove();
}

function finalizaImagem() {
	var imagemtemp = $('#imagem_html').html();
	if (imagemtemp.length) {
		var html_img = '<img src="exibir_imagem_temp.php?img=' + imagemtemp + '" id="imagem_exibicao" width="124" height="124" alt="" />';
		$('#div_imagem_exibicao').html(html_img);
		$('#imgtemp').val(imagemtemp);
	}
	//tb_remove();
}

function preview(img, selection) { 

	var scaleX = 120 / selection.width; 
	var scaleY = 90 / selection.height; 
	
	$('#thumbnail + div > img').css({ 
		width: Math.round(scaleX * 400) + 'px', 
		height: Math.round(scaleY * 300) + 'px',
		marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px', 
		marginTop: '-' + Math.round(scaleY * selection.y1) + 'px'
	});
	$('#x1').val(selection.x1);
	$('#y1').val(selection.y1);
	$('#x2').val(selection.x2);
	$('#y2').val(selection.y2);
	$('#w').val(selection.width);
	$('#h').val(selection.height);
} 

$(document).ready(function () { 
	$('#save_thumb').click(function() {
	console.log('form');	
		var x1 = $('#x1').val();
		var y1 = $('#y1').val();
		var x2 = $('#x2').val();
		var y2 = $('#y2').val();
		var w = $('#w').val();
		var h = $('#h').val();
		if(x1=="" || y1=="" || x2=="" || y2=="" || w=="" || h==""){
			alert("You must make a selection first");
			return false;
		}else{
			options = {
				url: "conteudo_ajax.php?get=imagem_conteudo_upload_crop",
				target: "#croppedimage",
				type: "post",
				success: finalizaImagem
			};
			$('#thumbnail_params').ajaxSubmit(options);
			return true;
		}
	});
});

$(document).ready(function () { 
console.log('load');
	$('#TB_ajaxContent #thumbnail').imgAreaSelect({ aspectRatio: '1.333:1', onSelectChange: preview }); 
});


</script>
</head>

<body>
<h3>Enviar Foto</h3>
<div id="imagem_html" style="display: none;"></div>
<form name="photo" id="photo" enctype="multipart/form-data" action="javascript:return false;" method="post">
	<input type="hidden" name="cod" value="<?=$cod?>" />
	<input type="hidden" name="tipo" value="<?=$tipo?>" />
  <input type="file" name="image" size="30" />
  <input type="submit" name="upload" value="Enviar" onclick="vai();"/>
</form>
<h3>Criar Miniatura</h3>
<div>
<img src="" alt="Create Thumbnail" name="thumbnail" width="400" align="left" id="thumbnail" class="thumbnail" style="margin-right: 10px; border:none; padding:0;" />

  <div style="border:1px #e5e5e5 solid; position:relative; overflow:hidden; width:120px; height:90px; " >
  <img src="" alt="Thumbnail Preview" width="120" class="thumbnail" style="position: relative; border:none; padding:0;" />  </div>
  <form name="thumbnail" action="javascript:return false;" method="post" id="thumbnail_params">
	<input type="hidden" name="image" value="" id="croppedimage"/>
    <input type="text" name="x1" value="" id="x1" />
    <input type="text" name="y1" value="" id="y1" />
    <input type="text" name="x2" value="" id="x2" />
    <input type="text" name="y2" value="" id="y2" />
    <input type="text" name="w" value="" id="w" />
    <input type="text" name="h" value="" id="h" />
    <input type="submit" name="upload_thumbnail" value="Salvar miniatura" id="save_thumb" />
  </form>  
</div>

</body>
</html>
