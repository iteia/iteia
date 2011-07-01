<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt" lang="pt-br">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="pt-br" />
<meta http-equiv="imagetoolbar" content="no" />
<meta name="Keywords" content="" />
<meta name="robots" content="all" />
<meta name="revisit-after" content="1 day" />
<meta name="author" content="Billy Blay" />
<meta name="rating" content="General" />
<?php if($conteudo){?>

<!--dublincore-->
<?php if($ativa == 7){
	$dubinfo = $conteudo;
	$dubautorurl = ConfigVO::getUrlSite().$dubinfo['publicado']['url'];
	
}else{
	$dubinfo = $conteudo['conteudo'];
	$dubautorurl = ConfigVO::getUrlSite().$dubinfo['autor_url'];
	
}
//print_r(ConfigVO::getUrlSite().$dubinfo['publicado']['url']);
?>
<link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" />
<meta name="DC.title" content="<?=$dubinfo['titulo'];?>" />
<meta name="DC.identifier" content="<?=ConfigVO::getUrlSite().substr($_SERVER['REQUEST_URI'],1);?>" />
<?php if ($dubinfo['descricao']){ ?>
<meta name="DC.description" content="<?=substr(strip_tags($dubinfo['descricao']), 0, 100);?>" />
<?php } ?>
<?php if ($conteudo['autores_ficha_tecnica_url']){ ?>
<?php foreach($conteudo['autores_ficha_tecnica_url'] as $url){ ?>
<meta name="DC.creator" content="<?=ConfigVO::getUrlSite().$url;?>" />
<?php } ?>
<?php } ?>
<?php if ($dubinfo['url_colaborador']){ ?>
<meta name="DC.contributor" content="<?=ConfigVO::getUrlSite().$dubinfo['url_colaborador'];?>" />
<?php } ?>
<?php if ($dubautorurl){ ?>
<meta name="DC.publisher" content="<?=$dubautorurl;?>" />
<?php } ?>
<?php
switch((int)$ativa){
	case 2:
		$tipo = "http://purl.org/dc/dcmitype/Sound";
		$tipocod = 3;
		$imagem = ConfigVO::URL_SITE."exibir_imagem.php?img=".$dubinfo['imagem']."&tipo=$tipocod&s=26";
	break;
	case 3:
		$tipo = "http://purl.org/dc/dcmitype/MovingImage";
		$tipocod = 15;
		if($dubinfo['imagem'] != ""){
			$tipocod = 'a';
			$imagem = ConfigVO::URL_SITE."exibir_imagem.php?img=".$dubinfo['imagem']."&tipo=$tipocod&s=26";
			break;
		}
		$imagem = ConfigVO::URL_SITE."exibir_imagem.php?img=".$conteudo['dados_arquivo']['video'].".png"."&tipo=$tipocod&s=26";
		if($conteudo['dados_arquivo']['link'] != ""){
			$codigo = explode("=",$conteudo['dados_arquivo']['video']);
			$imagem = "http://i3.ytimg.com/vi/".$codigo[1]."/default.jpg";
		}
	break;
	case 4:
		$tipo = "http://purl.org/dc/dcmitype/Text";
		$tipocod = 5;
		$imagem = ConfigVO::URL_SITE."exibir_imagem.php?img=".$dubinfo['imagem']."&tipo=$tipocod&s=26";
	break;
	case 6:
		$tipo = "http://purl.org/dc/dcmitype/Text";
		$tipocod = 1;
		$imagem = ConfigVO::URL_SITE."exibir_imagem.php?img=".$dubinfo['imagem']."&tipo=$tipocod&s=26";
	break;
	case 5:
		$tipo = "http://purl.org/dc/dcmitype/Image";
		$tipocod = 2;
		foreach($conteudo['dados_imagens'] as $dados)
			if($dados['cod_imagem_capa'] == $dados['cod_imagem'])
				$imagem = ConfigVO::URL_SITE."exibir_imagem.php?img=".$dados['imagem']."&tipo=$tipocod&s=26";
	break;
	case 7:
		$tipo = "http://purl.org/dc/dcmitype/Event";
		$tipocod = 1;
		$imagem = ConfigVO::URL_SITE."exibir_imagem.php?img=".$dubinfo['imagem']."&tipo=$tipocod&s=26";
	break;
}
if($tipocod == 15 && $conteudo['dados_arquivo']['link'] != "")
	$resultadoImagem = $imagem;
else
	$resultadoImagem = $imagem;
	//$resultadoImagem = ConfigVO::URL_SITE."exibir_imagem.php?img=$imagem&tipo=$tipocod&s=26";
?>
<?php if ($dubinfo['licenca_descricao']){ ?>
<meta name="DC.license" content="<?=$dubinfo['licenca_descricao'];?>" />
<?php } ?>
<meta name="DC.type" scheme="DCMITYPE" content="<?=$tipo?>" />
<link rel="schema.DCTERMS" href="http://purl.org/dc/terms/" />
<meta name="DCTERMS.created" scheme="ISO8601" content="<?=date('Y-m-d',strtotime($dubinfo['data_cadastro']));?>" />
<!--dublincore end-->

<!-- facebook -->
<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>
<meta property="og:title" content="<?=strip_tags($dubinfo['titulo']);?>" />
<meta property="og:description" content="<?=substr(strip_tags($dubinfo['descricao']), 0, 200);?>" />
<?php if($imagem != ""){?>
<meta property="og:image" content="<?=$resultadoImagem?>" />
<?php } ?>
<?php } ?>

<?php if ($ativa==1){
?>
<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>
<meta property="og:title" content="<?=$dadoscanal['nome'];?>" />
<meta property="og:description" content="<?=substr($dadoscanal['descricao'], 0, 200);?>" />
<?php if($dadoscanal['imagem'] != ""){?>
<meta property="og:image" content="<?=ConfigVO::URL_SITE?>exibir_imagem.php?img=<?=$dadoscanal['imagem'];?>&tipo=1&s=26" />
<?php } ?>
<?php
}
?>


<link rel="shortcut icon" type="image/x-icon" href="/img/favicon.png" />
<link  rel="stylesheet" type="text/css" media="screen" href="css/style.css" />
<link rel="stylesheet" type="text/css" media="print" href="css/print.css" />
<!--[if lt IE 7]>
<link rel="stylesheet" type="text/css" href="/css/iteia-ie6.css" />
<![endif]-->
<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="/css/iteia-ie7.css" />
<![endif]-->
<?php if (!$js_sem_jquery): ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<?php endif; ?>
<?php if ($js_jquery_ui): ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.5.3/jquery-ui.min.js"></script>
<?php endif; ?>
<?php if ($js_player): ?>
<script type="text/javascript" src="/js/flowplayer-3.1.5/flowplayer-3.1.4.min.js"></script>
<?php endif; ?>
<?php if ($js_galeria): ?>
<script type="text/javascript" src="/js/jcarousel/jquery.jcarousel.pack.js"></script>
<link rel="stylesheet" type="text/css" href="/js/jcarousel/jquery.jcarousel.css" />
<link rel="stylesheet" type="text/css" href="/css/carrossel.css" />
<?php endif; ?>
<?php if ($js_busca): ?>
<script type="text/javascript" src="/js/input_mask/jquery.maskedinput-1.1.2.js" ></script>
<?php endif; ?>
<script type="text/javascript" src="/js/script_achanoticias.js"></script>
<?php if($css_mapa): ?>
<link rel="stylesheet" type="text/css" href="/css/map.css" />
<?php endif; ?>
<?php if($jsthickbox): ?>
<script type="text/javascript" src="/js/thickbox/thickbox.js"></script>
<link rel="stylesheet" type="text/css" href="/js/thickbox/thickbox.css" />
<?php endif; ?>
<?php if($jsconteudo): ?>
<script type="text/javascript" src="/js/conteudo.js"></script>
<?php endif; ?>
<?php if($jsautores): ?>
<script type="text/javascript" src="/js/autores.js"></script>
<?php endif; ?>
<?php if($js_texto): ?>
<script type="text/javascript">
<!--
jQuery(document).ready(function() {
	<?php if ($js_galeria): ?>
	jQuery("#mycarousel").jcarousel({ scroll: 3 });
	<?php endif; ?>
	$('.aviso a').click(function() {
		var target = $(this).attr("href");
		$(target).focus();
		return false;
	});

	$list = $("#criado li").size();
	if ($list > 2) {
		$("#criado li:gt(1)").hide();
		$("#criado .todos").html('<strong><a class="mostra" title="Ver todos autores deste conteï¿½do">Mostrar autores<\/a><\/strong>');
		$('.mostra').toggle(function() {
			$("#criado li:gt(0)").show();
			$(this).addClass("esconde").removeClass("mostra").text("Ocultar autores").attr('title','Esconder autores deste conteï¿½do');
		}, function() {
			$("#criado li:gt(1)").hide();
			$(this).addClass("mostra").removeClass("esconde").text("Mostrar autores").attr('title','Ver todos autores deste conteï¿½do');
		});
	};
	<?=$js_ready_complemento?>
});
-->
</script>
<? endif; ?>
<?php if($js_denuncie): ?>
<script type="text/javascript" src="/js/denuncie.js"></script>
<? endif; ?>
<?php if($js_contato): ?>
<script type="text/javascript" src="/js/contato.js">
</script>
<? endif; ?>
<?php if($js_usuariocontato): ?>
<script type="text/javascript" src="/js/usuariocontato.js"></script>
<? endif; ?>
<?php if($js_bookmarks): ?>
<script type="text/javascript">
$(function() {
$("div#bookmarks").hide();
$("#compartilhe").click(function(){
$("div#bookmarks").toggle();
return false;
});
})
</script>
<? endif; ?>
<?php if($js_index): ?>
<script type="text/javascript">
	$(document).ready(function(){
		$("#featured > ul").tabs({fx:{opacity: "toggle"}}).tabs("rotate", 5000, true);
		<?php if ($js_galeria): ?>
		jQuery("#mycarousel").jcarousel({ scroll: 3 });
		<?php endif; ?>
	});
</script>
<link  rel="stylesheet" type="text/css" media="screen" href="css/slider.css" />
<? endif; ?>
<title><?=Util::iif($titulopagina, $titulopagina . ' | ') . Util::iif($titulopagina_index, $titulopagina_index, 'iTEIA');?></title>
</head>
<body<?=($topo_class?' class="'.$topo_class.'"':'')?>>
<p id="descer"><a href="#conteudo" tabindex="1" title="Pular a navegaï¿½ï¿½o e ir direto para o conteï¿½do">Pular a navegaï¿½ï¿½o e ir direto para o conteï¿½do</a></p>
<div id="busca-achix">
	<a href="http://www.achix.com.br" id="achix" title="Ir para o site do Achix">Tecnologia Achix</a>
  <form method="get" action="/busca_action.php" id="searchform" name="searchform"><!-- onsubmit="return false;"-->
    <fieldset>
			<input type="hidden" name="buscar" value="1" />
			<input type="hidden" name="novabusca" value="1" />
			<input type="hidden" name="tipo_assunto" value="2" />
			<input type="hidden" name="formatos" id="buscarpor" value="0" />
    <legend class="none">Busca</legend>
    <div id="box-input">
      <label for="termo" class="none">Digite uma palavra-chave</label>
      <input type="text" name="palavra" class="termo" onfocus="this.value = ''" id="termo" tabindex="2" value="Ache tudo aqui" />
    </div>
    <div id="box-select">
      <label for="myselectbox" class="none">Buscar por</label>
      <select id="myselectbox" name="buscatopo_tipo">
        <option value="0" selected="selected">Todos</option>
        <option value="2">ï¿½udios</option>
        <option value="3">Vï¿½deos</option>
        <option value="4">Textos</option>
        <option value="5">Imagens</option>
        <option value="6">Notï¿½cias</option>
        <option value="7">Eventos</option>
        <option value="9">Autores</option>
        <option value="10">Colaboradores</option>
      </select>
    </div>
    </fieldset>
    <fieldset class="escolha">
    <label>
    <input type="checkbox" value="1" name="destbusca" id="destbusca" onclick="javascript:setBuscaOpc(1);" /> no Achix</label>
    </fieldset>
    <button type="submit" value="Buscar" onclick="javascript:EfetuarBusca(0);" name="botao">Buscar</button>
		<a href="javascript:;" onclick="javascript:BuscaAvanc();" id="busca-avancada">Busca Avançada</a>
  </form>
</div>
<div id="tudo">
<div id="login">
    <?php if ((!$_SESSION['logado']) && (!$_SESSION['logado_dados']['cod'])): ?>
    <a href="/gerenciador/login.php">Entrar</a> | <a href="participar">Não é cadastrado?</a>
	<?php else: ?>
ï¿½, <a href="/gerenciador" id="autor-nome"><?=$_SESSION['logado_dados']['nome'];?></a>
    - <a href="/gerenciador/logout.php" id="sair">Sair</a>
	<?php endif; ?>
</div>
  <div id="topo" class="vcard"><strong class="fn" id="logo"><a href="/" tabindex="2" class="url" rel="me" title="Ir para pï¿½gina inicial">iTEIA</a></strong>
    <div id="descricao"></div>
    <div id="banner">
      <object type="application/x-shockwave-flash" data="http://www.iteia.org.br/banner/745x60_40kb.swf" width="745" 
height="60">
       <param name="movie" value="http://www.iteia.org.br/banner/745x60_40kb.swf" />
</object>
    </div>

    <div id="menu">
      <ul>
        <li class="canais<?=($ativa == 1 ? ' ativa':'')?>"><a href="canais" tabindex="3" title="Canais">Canais</a></li>
        <li class="audios<?=($ativa == 2 ? ' ativa':'')?>"><a href="audios" tabindex="4" title="ï¿½udios">Áudios</a></li>
        <li class="videos<?=($ativa == 3 ? ' ativa':'')?>"><a href="videos" tabindex="5" title="Vï¿½deos">Vídeos</a></li>
        <li class="textos<?=($ativa == 4 ? ' ativa':'')?>"><a href="textos" tabindex="6" title="Textos">Textos</a></li>
        <li class="imagens<?=($ativa == 5 ? ' ativa':'')?>"><a href="imagens" tabindex="7" title="Imagens">Imagens</a></li>
        <li class="noticias<?=($ativa == 6 ? ' ativa':'')?>"><a href="jornal" tabindex="8" title="Jornal">Jornal</a></li>
        <li class="eventos<?=($ativa == 7 ? ' ativa':'')?>"><a href="eventos" tabindex="9" title="Eventos">Eventos</a></li>
        <li class="autores<?=($ativa == 8 ? ' ativa':'')?>"><a href="autores" tabindex="10" title="Autores">Autores</a></li>
        <li class="colaboradores<?=($ativa == 9 ? ' ativa':'')?>"><a href="colaboradores" tabindex="11" title="Colaboradores">Colaboradores</a></li>
        <li class="grupos<?=($ativa == 10 ? ' ativa':'')?>"><a href="grupos" tabindex="12" title="Grupos">Grupos</a></li>
      </ul>
    </div>
  </div>
 <div id="conteiner">
