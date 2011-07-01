function recomendar(cod_conteudo, cod_formato, tipo) {
	$('#voto'+tipo).load('/recomendar.php?c='+cod_conteudo+'&f='+cod_formato+'&t='+tipo);
}

function loadComentarios(cod_conteudo) {
    $('#carrega_comentarios').load('/comentarios.php?acao=carregar&cod='+cod_conteudo); 
}

function enviarComentario(element) {
	element.disabled = true;
	var formDeComentario = document.formcomentario.comentario;
	var formDeNome = document.formcomentario.nome;
	var formDeEmail = document.formcomentario.email;

	if(formDeComentario.value==''){
		formDeComentario.className='txt erro';
	} else {
		formDeComentario.className='txt';
	}

	if(formDeNome.value==""){
		formDeNome.className='txt erro';
	} else {
		formDeNome.className='txt';
	}

	if(formDeEmail.value==""){
		formDeEmail.className='txt erro';
	} else {
		formDeEmail.className='txt';
	}

	var teste = new RegExp("^[-!#$%&\'*+\\.\/0-9=?A-Z^_`{|}~]+@([-0-9A-Z]+\.)+([0-9A-Z]){2,4}$","i");
	
	if(formDeComentario.value!='' && formDeNome.value!="" && formDeEmail.value!="" && teste.test(formDeEmail.value)==true){
		$.post('/comentarios_enviar.php?ajax=sim', $('#formcomentario').serialize(), function html(html) {$('#resposta_comentario').html(html);limpaCamposComentario()});
	}else{
		$.post('/comentarios_enviar.php?ajax=sim', $('#formcomentario').serialize(), function html(html) {$('#resposta_comentario').html(html)});
		element.disabled = false;
	}
}

function limpaCamposComentario() {
    $('#comentario').val('');
    $('#seu-nome').val('');
    $('#seu-email').val('');
    $('#seu-site').val('');
}

function carregaImagemGaleria(cod_imagem) {
	//$('#carrega_imagem').load('/galeria.php?cod_imagem='+cod_imagem+'&cod_conteudo='+$('#cod1').val());
	$.get('/galeria.php?cod_imagem='+cod_imagem+'&cod_conteudo='+$('#cod1').val(), function html(html) {
		$('#carrega_imagem').html(html);
	});
}

function limparCss() {
	for (i = 1; i <= total_faixas; i++) {
		$('#faixa_'+i).removeClass('playing');
	}
}

