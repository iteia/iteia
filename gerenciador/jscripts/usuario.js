function aprovarUsuario() {
	$.get('ajax_conteudo.php?get=aprovar_usuario&codusuario=' + $('#codusuario').val(), function html(html) {
		window.location.href = 'index_lista_notificacao.php?rand=' + html;
	});
}

function reprovarUsuario() {
	$.get('ajax_conteudo.php?get=reprovar_usuario&codusuario=' + $('#codusuario').val() + '&motivo=' + $('#motivo').val(), function html(html) {
		window.location.href = 'index_lista_notificacao.php?rand=' + html;
	});
}