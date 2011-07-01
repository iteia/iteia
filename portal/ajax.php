<?php
$dom = new DOMDocument("1.0", "iso-8859-1");

$acao = trim($_GET["acao"]);
switch ($acao) {
	case "getcidades":
		$codestado = (int)$_GET["codestado"];
		include_once("classes/bo/CadastroCulturalBO.php");
		$colabbo = new CadastroCulturalBO;
		$lista_cidades = $colabbo->getListaCidades($codestado);
		$elem_cidades = $dom->createElement("cidades");
		foreach ($lista_cidades as $cidade) {
			$elem_cidade = $dom->createElement("cidade", utf8_encode($cidade["cidade"]));
			$elem_cidade->setAttribute("cod", $cidade["cod_cidade"]);
			$elem_cidades->appendChild($elem_cidade);
		}
		$dom->appendChild($elem_cidades);
	break;
	case "getsubcanais":
		$codCanal = (int)$_GET["codCanal"];
		include_once("classes/bo/CanalBO.php");
		$colabbo = new CanalBO;
		$lista_cidades = $colabbo->getListaSubcanais($codCanal);
		$elem_cidades = $dom->createElement("subcanais");
		foreach ($lista_cidades as $cidade) {
			$elem_cidade = $dom->createElement("subcanal", utf8_encode($cidade["nome"]));
			$elem_cidade->setAttribute("cod", $cidade["cod_segmento"]);
			$elem_cidades->appendChild($elem_cidade);
		}
		$dom->appendChild($elem_cidades);
	break;
}

header("Content-type: application/xml");
echo $dom->saveXML();
