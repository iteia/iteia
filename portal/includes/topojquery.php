<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt" lang="pt-br">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta http-equiv="content-language" content="pt-br" />
<meta http-equiv="imagetoolbar" content="no" />
<meta name="Keywords" content="" />
<meta name="robots" content="all" />
<meta name="revisit-after" content="1 day" />
<meta name="author" content="Billy Blay" />
<meta name="rating" content="General" />
<meta name="DC.title" content="Instituto Intercidadania" />
<link rel="shortcut icon" type="image/x-icon" href="/img/favicon.png" />
<link  rel="stylesheet" type="text/css" media="screen" href="/css/style.css" />
<link rel="stylesheet" type="text/css" media="print" href="/css/print.css" />
<!--[if lt IE 7]>
<link rel="stylesheet" type="text/css" href="/css/iteia-ie6.css" />
<![endif]-->
<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="/css/iteia-ie7.css" />
<![endif]-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<!-- slider -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.5.3/jquery-ui.min.js"></script>

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

<!-- slider -->
<title><?=Util::iif($titulopagina, $titulopagina . ' | ');?>iTEIA</title>
</head>
<body<?=($topo_class?' class="'.$topo_class.'"':'')?>>
<p id="descer"><a href="#conteudo" tabindex="1" title="Pular a navegação e ir direto para o conteúdo">Pular a navegação e ir direto para o conteúdo</a></p>
<div id="busca-achix">
	<a href="http://www.achix.com.br" id="achix" title="Ir para o site do Achix">Tecnologia Achix</a>
  <form method="get" action="/busca_resultado.php" id="searchform" name="searchform"
>
    <fieldset>
		<input type="hidden" name="buscar" value="1" />
		<input type="hidden" name="novabusca" value="1" />
		<input type="hidden" name="tipo_assunto" value="2" />
		 <input type="hidden" id="buscarpor" value="0" />
    <legend class="none">Busca</legend>
    <div id="box-input">
      <label for="termo" class="none">Digite uma palavra-chave</label>
      <input type="text" name="palavra" class="termo" onfocus="this.value = ''" id="termo" tabindex="2" value="Ache tudo aqui" />
    </div>
    <div id="box-select">
      <label for="myselectbox" class="none">Buscar por</label>
      <select id="myselectbox" name="buscatopo_tipo">
        <option value="todos" selected="selected">Todos</option>
        <option value="audios">Áudios</option>
        <option value="videos">Vídeos</option>
        <option value="textos">Textos</option>
        <option value="imagens">Imagens</option>
        <option value="noticias">Notícias</option>
        <option value="eventos">Eventos</option>
        <option value="autores">Autores</option>
        <option value="colaboradores">Colaboradores</option>
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
    <a href="/gerenciador/login.php">Entrar</a> | <a href="participar.php">Não é cadastrado?</a>
	<?php else: ?>
    Olá, 
    <!-- usuário logado como autor <a href="#" id="autor-nome">Fábrica do futuro</a> --> 
    <!-- usuário logado como colaborador --> <a href="/gerenciador" id="autor-nome"><?=$_SESSION['logado_dados']['nome'];?></a> 
    - <a href="/gerenciador/logout.php" id="sair">Sair</a>
	<?php endif; ?>
</div>
  <div id="topo" class="vcard"><strong class="fn" id="logo"><a href="/index.php" tabindex="2" class="url" rel="me" title="Ir para página inicial">iTEIA</a></strong>
    <div id="descricao"></div>
    <div id="banner">
      <object type="application/x-shockwave-flash" data="/banner/chesf_745_60_2.swf" width="745" height="60">
        <param name="movie" value="/banner/chesf_745_60_2.swf" />
        <a href="http://www.chesf.com.br"><img src="noflash.gif" width="200" height="100" alt="Apoio: Chesf" /></a>
      </object>
    </div>
    <div id="menu">
      <ul>
        <li class="canais<?=($ativa == 1 ? ' ativa':'')?>"><a href="/canais.php" tabindex="3" title="Canais">Canais</a></li>
        <li class="audios<?=($ativa == 2 ? ' ativa':'')?>"><a href="/audios.php" tabindex="4" title="Áudios">Áudios</a></li>
        <li class="videos<?=($ativa == 3 ? ' ativa':'')?>"><a href="/videos.php" tabindex="5" title="Vídeos">Vídeos</a></li>
        <li class="textos<?=($ativa == 4 ? ' ativa':'')?>"><a href="/textos.php" tabindex="6" title="Textos">Textos</a></li>
        <li class="imagens<?=($ativa == 5 ? ' ativa':'')?>"><a href="/imagens.php" tabindex="7" title="Imagens">Imagens</a></li>
        <li class="noticias<?=($ativa == 6 ? ' ativa':'')?>"><a href="/noticias.php" tabindex="8" title="Notícias">Jornal</a></li>
        <li class="eventos<?=($ativa == 7 ? ' ativa':'')?>"><a href="/eventos.php" tabindex="9" title="Eventos">Eventos</a></li>
        <li class="autores<?=($ativa == 8 ? ' ativa':'')?>"><a href="/autores.php" tabindex="10" title="Autores">Autores</a></li>
        <li class="colaboradores<?=($ativa == 9 ? ' ativa':'')?>"><a href="/colaboradores.php" tabindex="11" title="Colaboradores">Colaboradores</a></li>
        <li class="grupos<?=($ativa == 10 ? ' ativa':'')?>"><a href="/grupos.php" tabindex="12" title="Grupos">Grupos</a></li>
      </ul>
    </div>
  </div>
 <div id="conteiner">