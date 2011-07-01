// funcoes para vinculação de autores

// buscar autores
function buscaAutoresRelacionamento() {
	var nome = $("#relacionar_palavrachave").val();
	$("#mostra_resultados_autores_relacionamento").load("ajax_conteudo.php?get=buscar_autor&buscar=1&tipo=2&palavrachave="+nome+"&buscarpor=nome");
}

//function navegaConteudo(url, campo, pagina) {
//    $(campo).load(url+"&pagina="+pagina);
//}

function adicionarAutor() {
	$('#mostra_lista_autores_relacionado').load('ajax_conteudo.php?get=adicionar_autor&autores='+$('#lista_autores').val());
}

function removerAutor(cod_autor) {
	$('#mostra_lista_autores_relacionado').load('ajax_conteudo.php?get=remover_autor&cod_autor='+cod_autor);
}

function carregarAutores() {
	$('#mostra_lista_autores_relacionado').load('ajax_conteudo.php?get=carregar_autores');
}

function mudaCampoLogin() {
	if ($('#tipo_autor').val() == 1)
		$('#box_login').hide();
	else
		$('#box_login').show();
}

function aprovarAutor(redir, codautor) {
	$('#lightbox').load('ajax_conteudo.php?get=aprovar_autor&codautor='+codautor);
	//window.location = 'ajax_conteudo.php?get=redirecionar_conteudo&cod_conteudo='+cod_conteudo;
	//if (redir == 1)
	//	window.location = 'index_lista_publica.php';
	//else if (redir == 2)
		window.location.href = 'index_lista_notificacao.php';
}

function reprovarAutor() {
	$('#lightbox').load('ajax_conteudo.php?get=reprovar_autor&codautor='+$('#codautor').val()+'&comentario='+$('#textarea').val());
	window.location.href = 'index_lista_notificacao.php';
}

function submeteUnificacao() {
	//var lista_checkboxes = $("input[@id=codusuario]");
    var lista_checkboxes = $("input[name='codusuario[]']");
	var lista_marcados = new Array;
	var total = 0;
	
	for (i = 0; i < lista_checkboxes.length; i++) {
		var item = lista_checkboxes[i];
		if ((item.type == "checkbox") && item.checked && parseInt(item.value)) {
			lista_marcados.push(item.value);
			total++;
		}
	}

	if (total != 2)
		alert('Selecione 2 registros do tipo Autor ou Wiki');
	else if (($('#tipoautor_' + lista_marcados[0]).val() == 1) || ($('#tipoautor_' + lista_marcados[1]).val() == 1))
		alert('Selecione apenas registros do tipo Autor ou Wiki');
	else
		window.location.href = 'cadastro_autores_unificar.php?cod1='+lista_marcados[0]+'&cod2='+lista_marcados[1];
}

function enviaLembrete(){

    var lista_checkboxes = $("input[name='codusuario[]']");
    var lista_marcados = new Array;
    var apenas_autores = true;
    var total = 0;
    
	for (i = 0; i < lista_checkboxes.length; i++) {
		var item = lista_checkboxes[i];
		if ((item.type == "checkbox") && item.checked && parseInt(item.value)) {
			lista_marcados.push(item.value);
			total++;
		}
	}
	

	if (total == 0)
		alert('Selecione ao menos 1 registro');
	else{
		for (var i in lista_marcados){
			if (($('#tipoautor_' + lista_marcados[i]).val() != 2)){
				apenas_autores = false;
				alert('Selecione apenas registros do tipo Autor');
				break;
			}
		}
		if(apenas_autores){
			alert("Enviando lembretes.\nClique OK e aguarde.");
			$.get("ajax_envia_lembrete.php", { lembrete: lista_marcados.join(",") }, function(data){
			   alert(data);
			 });
		}
	}
}

function validarCEP() {
	var cep = $('#cep').val();
	if (cep) {
		$.get('ajax_cep.php?cep='+ cep, function html(data) {
			if (data)  {
				dados = data.split(',');
				$('#logradouro').val((dados[3] ? dados[3] + ' ' : '') + dados[4]);
				$('#bairro').val(dados[2]);
				
				$('#estado').val(conversaoEstado(dados[0]));
				$('#pais').val(2);
				if (dados[1]) {
					$.get('ajax_cep_cidade.php?cidade='+ dados[1] + '&codestado=' + conversaoEstado(dados[0]), function html(data2) {
						$('#selectcidade').val(data2);
						obterCidades(document.getElementById('estado'), data2);
					});
				}
			}
		});
	} else {
		alert('Preencha o CEP');
	}
}

function conversaoEstado(estado) {
	switch(estado) {
		case 'AC': return 1; break;
		case 'AL': return 2; break;
		case 'AP': return 3; break;
		case 'AM': return 4; break;
		case 'BA': return 5; break;
		case 'CE': return 6; break;
		case 'DF': return 7; break;
		case 'ES': return 8; break;
		case 'GO': return 9; break;
		case 'MA': return 10; break;
		case 'MT': return 11; break;
		case 'MS': return 12; break;
		case 'MG': return 13; break;
		case 'PA': return 14; break;
		case 'PB': return 15; break;
		case 'PR': return 16; break;
		case 'PE': return 17; break;
		case 'PI': return 18; break;
		case 'RJ': return 19; break;
		case 'RN': return 20; break;
		case 'RS': return 21; break;
		case 'RO': return 22; break;
		case 'RR': return 23; break;
		case 'SC': return 24; break;
		case 'SP': return 25; break;
		case 'SE': return 26; break;
		case 'TO': return 27; break;
	}
}

