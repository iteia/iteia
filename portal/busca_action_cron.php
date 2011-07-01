<?php
include_once(dirname(__FILE__).'/classes/vo/ConfigPortalVO.php');
include_once(dirname(__FILE__).'/classes/bo/BuscaiTeiaCronBO.php');

$dados = $_GET;
$dados['buscar'] = true;
$dados['formatos'] = array(9, 10);

/*$dados['extras'] = array(
	'conteudo' 		=> (int)$dados['conteudo'], // conteudos relacionados / autores
	'codcanal' 		=> (int)$dados['canal'], // conteudos
	'direito' 		=> (int)$dados['direito'], // conteudos
	'tag' 			=> trim(strip_tags($dados['tag'])), // conteudos
	'ordenacao' 	=> (int)$dados['ordem'], // conteudos
	'relacionado' 	=> (int)$dados['relacionado'], // conteudos
	'colaborador' 	=> (int)$dados['colaborador'], // conteudos -> colaborador
	'autor' 		=> (int)$dados['autor'], // conteudos -> autor
	'cidades' 		=> $dados['cidades'], // cidades -> colaboradores/autores
	'estados' 		=> $dados['estados'], // estados -> colaboradores/autores
);*/

//$dados_todos = $dados;
//$buscabo->setId('689542a36f3b471d1f19');
//$memid1 = $buscabo->efetuaBusca($dados_todos);
	
if (in_array(2, $dados['formatos']) || !count($dados['formatos'])) {
	$dados_audios = $dados;
	$dados_audios['formatos'] = array(2);
	$memid2 = $buscabo->efetuaBusca($dados_audios);
}
	
if (in_array(3, $dados['formatos']) || !count($dados['formatos'])) {
	$dados_videos = $dados;
	$dados_videos['formatos'] = array(3);
	$memid3 = $buscabo->efetuaBusca($dados_videos);
}
	
if (in_array(4, $dados['formatos']) || !count($dados['formatos'])) {
	$dados_textos = $dados;
	$dados_textos['formatos'] = array(4);
	$memid4 = $buscabo->efetuaBusca($dados_textos);
}
	
if (in_array(5, $dados['formatos']) || !count($dados['formatos'])) {
	$dados_imagens = $dados;
	$dados_imagens['formatos'] = array(5);
	$memid5 = $buscabo->efetuaBusca($dados_imagens);
}
	
if (in_array(6, $dados['formatos']) || !count($dados['formatos'])) {
	$dados_noticias = $dados;
	$dados_noticias['formatos'] = array(6);
	$memid6 = $buscabo->efetuaBusca($dados_noticias);
}
	
if (in_array(7, $dados['formatos']) || !count($dados['formatos'])) {
	$dados_eventos = $dados;
	$dados_eventos['formatos'] = array(7);
	$memid7 = $buscabo->efetuaBusca($dados_eventos);   
}
	
if (in_array(8, $dados['formatos']) || !count($dados['formatos'])) {
	$dados_canais = $dados;
	$dados_canais['formatos'] = array(8);
	$memid8 = $buscabo->efetuaBusca($dados_canais);
}

// ativos
if (in_array(9, $dados['formatos']) || !count($dados['formatos'])) {
	$dados_autores['formatos'] = array(9);
	$dados_autores['extras'] = array('ordenacao' => 3);
	$buscabo = new BuscaiTeiaCronBO;
	$buscabo->apagarCache('5b5d806acfae9e635194');
	$buscabo->setId('5b5d806acfae9e635194');
	$memid9 = $buscabo->efetuaBusca($dados_autores);
	
	$buscabo = new BuscaiTeiaCronBO;
	$buscabo->apagarCache('689542a36f3b471d1f19');
	$buscabo->setId('689542a36f3b471d1f19');
	$memid1 = $buscabo->efetuaBusca($dados_autores);
}

// recentes
if (in_array(9, $dados['formatos']) || !count($dados['formatos'])) {
	$dados_autores = array();
	$dados_autores['formatos'] = array(9);
	$buscabo = new BuscaiTeiaCronBO;
	$buscabo->apagarCache('5b78f3e0638600a485ab');
	$buscabo->setId('5b78f3e0638600a485ab');
	$memid9 = $buscabo->efetuaBusca($dados_autores);
	
	$buscabo = new BuscaiTeiaCronBO;
	$buscabo->apagarCache('4dd62256849d702f8363');
	$buscabo->setId('4dd62256849d702f8363');
	$memid1 = $buscabo->efetuaBusca($dados_autores);
}

// ativos
if (in_array(10, $dados['formatos']) || !count($dados['formatos'])) {
	$dados_colaboradores['formatos'] = array(10);
	$dados_colaboradores['extras'] = array('ordenacao' => 3);
	$buscabo = new BuscaiTeiaCronBO;
	$buscabo->apagarCache('8ab7b74f2ab8eb479448');
	$buscabo->setId('8ab7b74f2ab8eb479448');
	$memid10 = $buscabo->efetuaBusca($dados_colaboradores);
	
	$buscabo = new BuscaiTeiaCronBO;
	$buscabo->apagarCache('e33fcc724fa76896d002');
	$buscabo->setId('e33fcc724fa76896d002');
	$memid1 = $buscabo->efetuaBusca($dados_colaboradores);
}

// recentes
if (in_array(10, $dados['formatos']) || !count($dados['formatos'])) {
	$dados_colaboradores = array();
	$dados_colaboradores['formatos'] = array(10);
	$buscabo = new BuscaiTeiaCronBO;
	$buscabo->apagarCache('6b08ebd221999a8e9a7f');
	$buscabo->setId('6b08ebd221999a8e9a7f');
	$memid10 = $buscabo->efetuaBusca($dados_colaboradores);
	
	$buscabo = new BuscaiTeiaCronBO;
	$buscabo->apagarCache('0c7f4c2bf6f06d444bfb');
	$buscabo->setId('0c7f4c2bf6f06d444bfb');
	$memid1 = $buscabo->efetuaBusca($dados_colaboradores);
}
