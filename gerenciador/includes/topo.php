<?php
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");
include_once("classes/util/NavegacaoUtil.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?=$paginatitulo;?> &raquo; Gerenciador de conte&uacute;do</title>
<style type="text/css" media="screen">
<!--
@import url("css/style.css");
-->
</style>

<?php if ($jquerynova): ?>
<script type="text/javascript" src="jscripts/jquery-1.4.2.min.js"></script>
<?php else: ?>
<script type="text/javascript" src="jscripts/jquery-1.2.6.pack.js"></script>
<?php endif; ?>

<script type="text/javascript" src="jscripts/jquery.form.js"></script>
<script type="text/javascript" src="jscripts/datepicker/ui.datepicker.js"></script>
<script type="text/javascript" src="jscripts/input_mask/jquery.maskedinput-1.1.2.pack.js"></script>
<?php if (!$nao_carregar_thickbox): ?>
<script type="text/javascript" src="jscripts/thickbox/thickbox-compressed.js"></script>
<?php endif; ?>

<script type="text/javascript" src="jscripts/scripts.js"></script>
<script type="text/javascript" src="jscripts/funcoes.js"></script>

<script type="text/javascript" src="jscripts/jquery.ajaxQueue.js"></script>
<script type="text/javascript" src="jscripts/jquery.bgiframe.min.js"></script>

<?php if ($crop): ?>
		<script src="jscripts/jquery-1.3.2.min.js"></script>
		<script src="jcrop/js/jquery.Jcrop.js"></script>
		<link rel="stylesheet" href="jcrop/css/jquery.Jcrop.css" type="text/css" />
<?php endif; ?>

<link rel="stylesheet" type="text/css" href="css/jquery.autocomplete.css" />

</head>
<body>
<div id="container">
  <div id="header">
    <h1>Gerenciador de conte&uacute;do</h1>
    <div id="logo"><span id="ver">[<a href="<?=ConfigVO::URL_SITE;?>">Ver site</a>]</span></div>
    <ul id="menu-usuario">
      <li id="usuario"><strong><?=$_SESSION['logado_dados']['nome']/*$_SESSION['nome'];*/?></strong></li>
      <li id="editar-cadastro"><a href="cadastro_meu.php">Meu cadastro</a></li>
      <li id="logout"><a href="logout.php">Sair</a></li>
    </ul>
    
    <ul id="main-menu">
      <li<?=NavegacaoUtil::menuAtivo("index", $item_menu)?>><a href="index.php">Painel</a></li>
<?php if (($_SESSION['logado_dados']['nivel'] == 7) || ($_SESSION['logado_dados']['nivel'] == 8)): ?>
      <li<?=NavegacaoUtil::menuAtivo("home", $item_menu)?>><a href="home.php">Destaques</a></li>
<?php endif; ?>
      <li<?=NavegacaoUtil::menuAtivo("conteudo", $item_menu)?>><a href="conteudo.php">Conte&uacute;do</a></li>
      <li<?=NavegacaoUtil::menuAtivo("noticias", $item_menu)?>><a href="noticias.php">Not&iacute;cias</a></li>
<?php if ($_SESSION['logado_dados']['nivel'] >= 5): /*if ($_SESSION['logado_como'] > 1):*/ ?>
      <li<?=NavegacaoUtil::menuAtivo("banners", $item_menu)?>><a href="banners.php">An�ncios</a></li>
<?php endif; ?>
<?php /*if ($_SESSION['logado_dados']['nivel'] >= 5): /*if ($_SESSION['logado_como'] > 1):*/ ?>
      <li<?=NavegacaoUtil::menuAtivo("agenda", $item_menu)?>><a href="agenda.php">Eventos</a></li>
<?php /*endif;*/ ?>
<?php if /*(($_SESSION['logado_dados']['nivel'] == 7) || ($_SESSION['logado_dados']['nivel'] == 8)):*/ ($_SESSION['logado_dados']['nivel'] >= 5): /*if ($_SESSION['logado_como'] > 1):*/ ?>
      <li<?=NavegacaoUtil::menuAtivo("cadastro", $item_menu)?>><a href="cadastro.php">Usu&aacute;rios</a></li>
<?php endif; ?>
      <!--<li<?=NavegacaoUtil::menuAtivo("grupo", $item_menu)?>><a href="grupo.php">Grupos</a></li>-->
	  <li<?=NavegacaoUtil::menuAtivo("comentarios", $item_menu)?>><a href="comentarios.php">Coment&aacute;rios</a></li>
      <li<?=NavegacaoUtil::menuAtivo("boletim", $item_menu)?>><a href="home_newsletter.php">Boletim</a></li>
    </ul>
    
<?php if ($item_menu == 'index'): ?>
	<ul id="submenu">
      <li<?=NavegacaoUtil::menuAtivo("index", $item_submenu)?>><a href="index.php">In&iacute;cio</a></li>
<?php if (($_SESSION['logado_dados']['nivel'] >= 5) && (isset($_SESSION['logado_dados']['cod_colaborador']))): ?>
      <li<?=NavegacaoUtil::menuAtivo("lista_publica", $item_submenu)?>><a href="index_lista_notificacao.php">Lista de autoriza&ccedil;&otilde;es</a></li>
<?php endif; ?>
      <li<?=NavegacaoUtil::menuAtivo("conteudo_recente", $item_submenu)?>><a href="index_lista_recentes.php">Conte&uacute;dos recentes </a></li>
      <?php if (($_SESSION['logado_dados']['nivel'] == 7) || ($_SESSION['logado_dados']['nivel'] == 8)): /*if ($_SESSION['logado_como'] == 3):*/ ?>
      <li<?=NavegacaoUtil::menuAtivo("comentarios", $item_submenu)?>><a href="comentarios.php">Coment&aacute;rios recentes</a></li>
      <?php endif; ?>
    </ul>
<?php endif; ?>

<?php if ($item_menu == 'conteudo' && !$nao_carregar == 'conteudo'): ?>
    <ul id="submenu">
      <li<?=NavegacaoUtil::menuAtivo("inicio", $item_submenu)?>><a href="conteudo.php">Todos Conte&uacute;dos</a></li>
<?php if (($_SESSION['logado_dados']['nivel'] == 7) || ($_SESSION['logado_dados']['nivel'] == 8)): /*if ($_SESSION['logado_como'] == 3):*/ ?>
      <li<?=NavegacaoUtil::menuAtivo("formatos", $item_submenu)?>><a href="conteudo_formatos.php">Categorias</a></li>
      <li<?=NavegacaoUtil::menuAtivo("segmentos", $item_submenu)?>><a href="conteudo_segmentos.php">Canais</a></li>
      <li<?=NavegacaoUtil::menuAtivo("tags", $item_submenu)?>><a href="conteudo_tags.php">Tags</a></li>
      <li<?=NavegacaoUtil::menuAtivo("atividades", $item_submenu)?>><a href="conteudo_atividades.php">Atividades</a></li>
<?php endif; ?>
      <li<?=NavegacaoUtil::menuAtivo("licenca", $item_submenu)?>><a href="conteudo_licencas_padrao.php">Licen&ccedil;as</a></li>
      <li<?=NavegacaoUtil::menuAtivo("inserir", $item_submenu)?>><a href="conteudo_tipo.php">Publicar</a></li>
    </ul>
<?php endif; ?>

<?php if ($item_menu == 'noticias'): ?>
    <ul id="submenu">
     <li<?=NavegacaoUtil::menuAtivo("inicio", $item_submenu)?>><a href="noticias.php">Todas Not&iacute;cias</a></li>
     <li<?=NavegacaoUtil::menuAtivo("inserir", $item_submenu)?>><a href="noticia_edicao.php">Publicar</a></li>
	 <?php //<li><a href="noticias_ajuda.php?height=200&amp;width=550" title="Ajuda" class="thickbox">Ajuda</a></li>?>
     <li<?=NavegacaoUtil::menuAtivo("ajuda", $item_submenu)?>><a href="noticias_ajuda.php">Ajuda</a></li>
    </ul>
<?php endif; ?>

<?php if ($item_menu == 'agenda'): ?>
    <ul id="submenu">
     <li<?=NavegacaoUtil::menuAtivo("inicio", $item_submenu)?>><a href="agenda.php">Todos eventos</a></li>
     <li<?=NavegacaoUtil::menuAtivo("inserir", $item_submenu)?>><a href="agenda_edicao.php">Publicar</a></li>
     <li<?=NavegacaoUtil::menuAtivo("ajuda", $item_submenu)?>><a href="agenda_ajuda.php">Ajuda</a></li>
    </ul>
<?php endif; ?>

<?php if ($item_menu == 'cadastro'): ?>
    <ul id="submenu">
     <li<?=NavegacaoUtil::menuAtivo("inicio", $item_submenu)?>><a href="cadastro.php">Todos usu&aacute;rios</a></li>
     <li<?=NavegacaoUtil::menuAtivo("administrador", $item_submenu)?>><a href="cadastro_administradores.php">Administradores</a></li>
     <li<?=NavegacaoUtil::menuAtivo("autor", $item_submenu)?>><a href="cadastro_autores.php">Autores</a></li>
     <li<?=NavegacaoUtil::menuAtivo("colaborador", $item_submenu)?>><a href="cadastro_colaboradores.php">Colaboradores</a></li>
     <li<?=NavegacaoUtil::menuAtivo("wiki", $item_submenu)?>><a href="cadastro_autores_wiki.php">Wiki</a></li>
     <li<?=NavegacaoUtil::menuAtivo("ajuda", $item_submenu)?>><a href="cadastro_ajuda.php">Ajuda</a></li>
    </ul>
<?php endif; ?>

<?php if ($item_menu == 'banners'): ?>
	<ul id="submenu">
      <li<?=NavegacaoUtil::menuAtivo("inicio", $item_submenu)?>><a href="banners.php">Todos an&uacute;ncios</a></li>
      <li<?=NavegacaoUtil::menuAtivo("inserir", $item_submenu)?>><a href="banners_edicao.php">Publicar</a></li>
      <?php //<li><a href="banners_ajuda.php?height=170&amp;width=550" title="Ajuda" class="thickbox">Ajuda</a></li>?>
      <li<?=NavegacaoUtil::menuAtivo("ajuda", $item_submenu)?>><a href="banners_ajuda.php">Ajuda</a></li>
    </ul>
<?php endif; ?>

<?php if ($item_menu == 'grupo'): ?>
	<ul id="submenu">
<?php if ($_SESSION['logado_dados']['nivel'] >= 5): /*if ($_SESSION['logado_como'] > 1):*/ ?>
      <li<?=NavegacaoUtil::menuAtivo("inicio", $item_submenu)?>><a href="grupo.php">In&iacute;cio</a></li>
      <li<?=NavegacaoUtil::menuAtivo("tipo", $item_submenu)?>><a href="grupo_tipos.php">Tipos</a></li>
<?php endif; ?>
      <!--
<li<?=NavegacaoUtil::menuAtivo("meus_grupos", $item_submenu)?>><a href="grupo_meus_grupos.php">Meus grupos</a></li>
-->
      <li<?=NavegacaoUtil::menuAtivo("inserir", $item_submenu)?>><a href="grupo_edicao.php">Cadastrar</a></li>
      <li><a href="grupo_ajuda.php?height=200&amp;width=550" title="Ajuda" class="thickbox">Ajuda</a></li>
    </ul>
<?php endif; ?>

<?php if ($item_menu == 'home'): ?>
	<ul id="submenu">
	<?php /*if (($_SESSION['logado_dados']['nivel'] == 7) || ($_SESSION['logado_dados']['nivel'] == 8)): /*if ($_SESSION['logado_como'] == 3):*/ ?>
      <li<?=NavegacaoUtil::menuAtivo("destaque_home", $item_submenu)?>><a href="home.php">Todos destaques</a></li>
      <li<?=NavegacaoUtil::menuAtivo("destaque_inserir", $item_submenu)?>><a href="home_playlist.php">Inserir</a></li>
      <?php /*endif;*/ ?>
    </ul>
<?php endif; ?>
<?php
//print_r($_SESSION);
if ($item_menu == 'comentarios'): ?>
    <ul id="submenu">
      <li<?=NavegacaoUtil::menuAtivo("inicio", $item_submenu)?>><a href="comentarios.php">Todos coment&aacute;rios</a></li>
      <li<?=NavegacaoUtil::menuAtivo("aprovados", $item_submenu)?>><a href="comentarios_aprovados.php">Aprovados</a></li>
      <li<?=NavegacaoUtil::menuAtivo("rejeitados", $item_submenu)?>><a href="comentarios_rejeitados.php">Rejeitados</a></li>
      <li<?=NavegacaoUtil::menuAtivo("aguardando", $item_submenu)?>><a href="comentarios_aguardando.php">Aguardando aprova&ccedil;&atilde;o</a></li>
      <!--<li<?=NavegacaoUtil::menuAtivo("spam", $item_submenu)?>><a href="">Spam</a></li>-->
	<?php if (($_SESSION['logado_dados']['nivel'] == 7) || ($_SESSION['logado_dados']['nivel'] == 8)): /*if ($_SESSION['logado_como'] == 3):*/ ?>
      <li<?=NavegacaoUtil::menuAtivo("opcoes", $item_submenu)?>><a href="comentarios_opcoes.php">Op&ccedil;&otilde;es</a></li>
	<?php endif; ?>
      <li<?=NavegacaoUtil::menuAtivo("ajuda", $item_submenu)?>><a href="comentarios_ajuda.php">Ajuda</a></li>
    </ul>
<?php endif; ?>

<?php if ($item_menu == 'boletim'): ?>
    <ul id="submenu">
      <li<?=NavegacaoUtil::menuAtivo("inicio", $item_submenu)?>><a href="home_newsletter.php">Todos boletins</a></li>
      <li<?=NavegacaoUtil::menuAtivo("lista_email", $item_submenu)?>><a href="home_newsletter_listas.php">Listas de emails</a></li>
      <li<?=NavegacaoUtil::menuAtivo("cadastrar_email", $item_submenu)?>><a href="home_newsletter_emails_cadastrar.php">Cadastrar emails</a></li>
      <li<?=NavegacaoUtil::menuAtivo("criar_boletim", $item_submenu)?>><a href="home_newsletter_inserir.php">Criar boletim</a></li>
      <li<?=NavegacaoUtil::menuAtivo("bloqueados_boletim", $item_submenu)?>><a href="home_newsletter_bloqueados.php">Emails Bloqueados</a></li>
    </ul>
<?php endif; ?>
  </div>
  <hr />
  <div id="content">
