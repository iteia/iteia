function exibeEstadoCidade() {
	if (document.getElementById('pais').value == '2') {
		document.getElementById('mostraestado').style.display = 'inline';
		document.getElementById('selectcidade').style.display = 'inline';
		document.getElementById('cidade').style.display = 'none';
	} else {
		document.getElementById('mostraestado').style.display = 'none';
		document.getElementById('selectcidade').style.display = 'none';
		document.getElementById('cidade').style.display = 'inline';
	}
}

function obterCidades(obj, codcidade) {
	var codestado = obj.options[obj.options.selectedIndex].value;
	if (codestado == "0") {
		removeAllChildren(document.getElementById("selectcidade"));
		var node_option = document.createElement("option");
		node_option.setAttribute("value", "0");
		node_option.appendChild(document.createTextNode("Selecione a Cidade"));
		document.getElementById("selectcidade").appendChild(node_option);
	}
	else {
		AjaxRequest();
		var url = "/ajax.php?acao=getcidades&codestado=" + codestado;
		Ajax.onreadystatechange = function () {
			if ((Ajax.readyState == 4) && (Ajax.status == 200)){
				listaCidades(codcidade);
			}
		}
		Ajax.open("GET", url, true);
		Ajax.send(null);
	}
}

function obterSubcanais(obj) {
	var codCanal = obj.options[obj.options.selectedIndex].value;
	if (codCanal == "0") {
		removeAllChildren(document.getElementById("subchannel"));
		var node_option = document.createElement("option");
		node_option.setAttribute("value", "0");
		node_option.appendChild(document.createTextNode("Qualquer subcanal"));
		document.getElementById("subchannel").appendChild(node_option);
	}
	else {
		AjaxRequest();
		var url = "/ajax.php?acao=getsubcanais&codCanal=" + codCanal;
		Ajax.onreadystatechange = function () {
			if ((Ajax.readyState == 4) && (Ajax.status == 200)){
				listaSubcanais(codCanal);
			}
		}
		Ajax.open("GET", url, true);
		Ajax.send(null);
	}
}

//function listaCidades(codcidade) {
//	var select_cidades = document.getElementById("selectcidade");
//	removeAllChildren(select_cidades);
//
//	node_option = document.createElement("option");
//	node_option.setAttribute("value", "0");
//	node_option.appendChild(document.createTextNode("Selecione a Cidade"));
//	select_cidades.appendChild(node_option);
//
//	var cidades = Ajax.responseXML.getElementsByTagName("cidades")[0];
//	for (i = 0; i < cidades.childNodes.length; i++) {
//		codcid = cidades.childNodes[i].getAttribute("cod");
//		node_option = document.createElement("option");
//		node_option.setAttribute("value", codcid);
//		if (codcid == codcidade)
//			node_option.setAttribute("selected", "selected");
//		node_option.appendChild(document.createTextNode(cidades.childNodes[i].firstChild.nodeValue));
//		select_cidades.appendChild(node_option);
//	}
//}

function listaCidades(codcidade) {
	var select_cidades1 = document.getElementById("selectcidade");
	removeAllChildren(select_cidades1);

	var node_option = document.createElement("option");
	node_option.setAttribute("value", "0");
	node_option.appendChild(document.createTextNode("Selecione a Cidade"));
	select_cidades1.appendChild(node_option);

	var cidades = Ajax.responseXML.getElementsByTagName("cidades")[0];
	for (i = 0; i < cidades.childNodes.length; i++) {
		var codcid = cidades.childNodes[i].getAttribute("cod");
		node_option = document.createElement("option");
		node_option.setAttribute("value", codcid);
		if (codcid == codcidade)
			node_option.setAttribute("selected", "selected");
		node_option.appendChild(document.createTextNode(cidades.childNodes[i].firstChild.nodeValue));
		select_cidades1.appendChild(node_option);
	}
}

function listaSubcanais(codcidade) {
	var select_cidades1 = document.getElementById("subchannel");
	removeAllChildren(select_cidades1);

	var node_option = document.createElement("option");
	node_option.setAttribute("value", "0");
	node_option.appendChild(document.createTextNode("Qualquer subcanal"));
	select_cidades1.appendChild(node_option);

	var cidades = Ajax.responseXML.getElementsByTagName("subcanais")[0];
	for (i = 0; i < cidades.childNodes.length; i++) {
		var codcid = cidades.childNodes[i].getAttribute("cod");
		node_option = document.createElement("option");
		node_option.setAttribute("value", codcid);
		if (codcid == codcidade)
			node_option.setAttribute("selected", "selected");
		node_option.appendChild(document.createTextNode(cidades.childNodes[i].firstChild.nodeValue));
		select_cidades1.appendChild(node_option);
	}
}

function exibeTextoLogin(campo, idlogin) {
	document.getElementById(idlogin).value = campo.value.toLowerCase();
}

function contarCaracteres(campo, idvisual, limite) {
	total = limite;
	tam = campo.value.length;
	str = "";
	str = str+tam;
	document.getElementById(idvisual).value = total - str;
	if (tam > total) {
		campo.value = campo.value.substring(0, total);
		document.getElementById(idvisual).value = 0;
	}
}

function exibeTexto(campo, idvisual) {
	document.getElementById(idvisual).innerHTML = campo.value;
}

function lowercase(campo) {
	var texto = document.getElementById(campo).value;
	texto = texto.replace('.', '');
	texto = texto.replace(',', '');
	texto = texto.replace(' ', '');
	document.getElementById(campo).value = (texto.toLowerCase());
}

// cadastros
function exibeEstadoCidade2() {
	if (document.getElementById('pais2').value == '2') {
		document.getElementById('mostraestado2').style.display = 'inline';
		document.getElementById('selectcidade2').style.display = 'inline';
		document.getElementById('cidade2').style.display = 'none';
	} else {
		document.getElementById('mostraestado2').style.display = 'none';
		document.getElementById('selectcidade2').style.display = 'none';
		document.getElementById('cidade2').style.display = 'inline';
	}
}

function obterCidades2(obj, codcidade) {
	var codestado = obj.options[obj.options.selectedIndex].value;
	if (codestado == "0") {
		removeAllChildren(document.getElementById("selectcidade2"));
		var node_option = document.createElement("option");
		node_option.setAttribute("value", "0");
		node_option.appendChild(document.createTextNode("Selecione a Cidade"));
		document.getElementById("selectcidade2").appendChild(node_option);
	}
	else {
		AjaxRequest1();
		var url = "/ajax.php?acao=getcidades&codestado=" + codestado;
		Ajax1.onreadystatechange = function () {
			if ((Ajax1.readyState == 4) && (Ajax1.status == 200)){
				listaCidades2(codcidade);
			}
		}
		Ajax1.open("GET", url, true);
		Ajax1.send(null);
	}
}

function listaCidades2(codcidade) {
	var select_cidades = document.getElementById("selectcidade2");
	removeAllChildren(select_cidades);

	var node_option = document.createElement("option");
	node_option.setAttribute("value", "0");
	node_option.appendChild(document.createTextNode("Selecione a Cidade"));
	select_cidades.appendChild(node_option);

	var cidades = Ajax1.responseXML.getElementsByTagName("cidades")[0];
	for (i = 0; i < cidades.childNodes.length; i++) {
		var codcid = cidades.childNodes[i].getAttribute("cod");
		node_option = document.createElement("option");
		node_option.setAttribute("value", codcid);
		if (codcid == codcidade)
			node_option.setAttribute("selected", "selected");
		node_option.appendChild(document.createTextNode(cidades.childNodes[i].firstChild.nodeValue));
		select_cidades.appendChild(node_option);
	}
}