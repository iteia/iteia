<?php
include('verificalogin.php');

include_once("classes/bo/CadastroBO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");
$cadbo = new CadastroBO;

if ($_SESSION['logado_dados']['nivel'] == 2) {
	Header('Location: cadastro_meu.php');
	die;
}

$pagina = (int)Util::iif($_GET['pagina'], $_GET['pagina'], 1);
$mostrar = (int)Util::iif($_GET['mostrar'], $_GET['mostrar'], 10);
$inicial = ($pagina - 1) * $mostrar;

$buscar = (int)$_GET['buscar'];
$sucesso = (int)$_GET['sucesso'];

//$_GET['usuario'] = 1;
if(!$buscar){
    $_GET['tipo'] = 2;
    $_GET['palavrachave'] = '';
    $_GET['buscarpor'] = 'wiki';
    $_GET['buscar'] = 1;
}

$cadastros = $cadbo->getListaCadastros($_GET, $inicial, $mostrar);
$paginacao = Util::paginacao($pagina, $mostrar, $cadastros['total'], $cadastros['link']);

$item_menu = 'cadastro';
$item_submenu = 'wiki';
$paginatitulo = 'Usu&aacute;rios&nbsp;';
include('includes/topo.php');
?>
    <h2>Usu&aacute;rios</h2>

	<form action="cadastro_autores_wiki.php" method="get" id="box-busca">
	<input type="hidden" name="buscar" value="1" />
	<input type="hidden" name="tipo" value="2" />
	<input type="hidden" name="buscarpor" value="wiki" />
      <label for="textfield" class="display-none">Palavras-chave</label>
      <input name="palavrachave" type="text" class="txt" id="textfield"  onfocus="if (this.value == 'Buscar') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Buscar';}" value="Buscar"  />
      <input type="image" src="img/ico/magnifier.gif" alt="Buscar" class="bt-buscar-img" />
      <a href="cadastro_autores_wiki_busca_popup.php?height=330&amp;width=310" title="Busca avan&ccedil;ada" class="thickbox">Busca avan&ccedil;ada</a>
    </form>

    <p class="descricao">
	<?php if (($_SESSION['logado_dados']['nivel'] == 5) || ($_SESSION['logado_dados']['nivel'] == 6)): ?>
	Cadastre <a href="cadastro_autor.php">novos autores</a> ou gerencie os autores vinculados ao colaborador que voc&ecirc; representa.<br />
	<?php elseif (($_SESSION['logado_dados']['nivel'] == 7) || ($_SESSION['logado_dados']['nivel'] == 8)): ?>
    Clique aqui para cadastrar <a href="cadastro_colaborador.php">novos colaboradores</a> ou <a href="cadastro_autor.php">novos autores</a>.
    <?php endif; ?>
    </p>

	<!--
    <form id="busca" method="get" action="cadastro.php">
      <fieldset>
      <legend>Buscar</legend>
      <div class="campos">
        <label for="textfield">Palavras-chave</label>
        <br />
        <input type="hidden" name="buscar" value="1" />
        <input type="text" name="palavrachave" class="txt" id="textfield"  />
      </div>

      <div class="campos">
        <label for="type">Filtrar por</label>
        <br />
        <select id="type" name="buscarpor">
          <option value="nome">Nome</option>
          <option value="estado">Estado</option>
        </select>
      </div>

      <div class="campos">
        <label for="select">Situa&ccedil;&atilde;o</label>
        <br />
        <select id="select" name="situacao">
          <option value="0" selected="selected">Todos</option>
          <option value="3">Ativo</option>
          <option value="2">Inativo</option>
          <option value="1">Pendente</option>
        </select>
      </div>
      <fieldset id="periodo">
      <legend class="seta">Buscar por per&iacute;odo</legend>
      <div class="fechada">

          <label for="dFrom">De:</label>
          <input type="text" name="de" class="txt calendario date" id="dFrom" />
          <em><small>dd/mm/aaaa</small></em>

          <label for="dTo">At&eacute;:</label>
          <input type="text" name="ate" class="txt calendario date" id="dTo" />
          <em><small>dd/mm/aaaa</small></em>
      </div>
      </fieldset>
      <input type="submit" class="bt-buscar" value="Buscar" />
      </fieldset>
    </form>

    -->

    <form method="get" id="form-result" action="cadastro_autores_wiki.php">
          <h3 class="titulo"><?=Util::iif($buscar, 'Resultado da busca', 'Wiki');?></h3>

	<input type="hidden" name="buscar" value="1" />
	<input type="hidden" name="palavrachave" value="<?=$cadbo->getValorCampo('palavrachave')?>" />
	<input type="hidden" name="buscarpor" value="<?=$cadbo->getValorCampo('buscarpor')?>" />
	<input type="hidden" name="situacao" value="<?=$cadbo->getValorCampo('situacao')?>" />
	<input type="hidden" name="de" value="<?=$cadbo->getValorCampo('de')?>" />
	<input type="hidden" name="ate" value="<?=$cadbo->getValorCampo('ate')?>" />
	<input type="hidden" id="acao" name="acao" value="0" />
    
    <?php include('includes/cadastro_lista_usuario.php');?>
</form>
  </div>
<?php if (count($cadastros) == 2 && $buscar): ?>
<script language="javascript" type="text/javascript">
alert('Nenhum registro foi encontrado. Tente novamente com outras palavras-chave.');
</script>
<?php endif; ?>
<?php include('includes/rodape.php'); ?>