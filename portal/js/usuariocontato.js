usuariocontato_err = 0;
usuariocontato_sent = false;

function enviarContato() {
    if(document.formcontato.mensagem.value==''){
	document.formcontato.mensagem.className='txt erro';
	usuariocontato_err++;
    }
    if(document.formcontato.nome.value==''){
	document.formcontato.nome.className='txt erro';
	usuariocontato_err++;
    }
    if(document.formcontato.email.value==''){
	document.formcontato.email.className='txt erro';
	usuariocontato_err++;
    }

    if(usuariocontato_err == 0){
	$.post('/usuario_contatoajax.php',
	    $('#form-contato').serialize(),
	    function html(html) {
		$('#resposta_contato').html(html);
		limpaCamposContato();
		//usuariocontato_sent = true;
		$("#enviarcontato-btn").attr("disabled", "disabled"); 
	    }
	);
    }
    usuariocontato_err = 0;
}

function limpaCamposContato() {
    $('#mensagem').val('');
    $('#seu-nome').val('');
    $('#seu-email').val('');
}
