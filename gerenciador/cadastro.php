<?php
include_once('verificalogin.php');
include_once('classes/bo/CadastroBO.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz().'util/Util.php');
$cadbo = new CadastroBO;

if ($_SESSION['logado_dados']['nivel'] == 2) {
	Header('Location: cadastro_meu.php');
	die;
}

$pagina 	= (int)Util::iif($_GET['pagina'], $_GET['pagina'], 1);
$mostrar 	= (int)Util::iif($_GET['mostrar'], $_GET['mostrar'], 10);
$inicial 	= ($pagina - 1) * $mostrar;
$buscar 	= (int)$_GET['buscar'];
$sucesso 	= (int)$_GET['sucesso'];

$_GET['usuario'] = 1;

if (isset($_COOKIE['pag_cadastro']) && !$_GET['mostrar'])
	$mostrar = $_COOKIE['pag_cadastro'];

$cadastros = $cadbo->getListaCadastros($_GET, $inicial, $mostrar);
$paginacao = Util::paginacao($pagina, $mostrar, $cadastros['total'], $cadastros['link']);

$item_menu = 'cadastro';
$item_submenu = 'inicio';
$paginatitulo = 'Usu&aacute;rios';
include('includes/topo.php');
?>
<script type="text/javascript" src="jscripts/autor.js"></script>
<h2>Usu&aacute;rios</h2>
<form action="cadastro.php" method="get" id="box-busca">
	<input type="hidden" name="buscar" value="1" />
	<input type="hidden" name="buscarpor" value="nome" />
	<label for="textfield" class="display-none">Palavras-chave</label>
	<input name="palavrachave" type="text" class="txt" id="textfield"  onfocus="if (this.value == 'Buscar') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Buscar';}" value="Buscar"  />
	<input type="image" src="img/ico/magnifier.gif" alt="Buscar" class="bt-buscar-img" />
	<a href="cadastro_busca_popup.php?height=330&amp;width=310" title="Busca avan&ccedil;ada" class="thickbox">Busca avan&ccedil;ada</a>
</form>
    
<p class="descricao">
	<?php if (($_SESSION['logado_dados']['nivel'] == 5) || ($_SESSION['logado_dados']['nivel'] == 6)): ?>
	Cadastre <a href="cadastro_autor.php">novos autores</a> ou gerencie os autores vinculados ao colaborador que voc&ecirc; representa.<br />
	<?php elseif (($_SESSION['logado_dados']['nivel'] == 7) || ($_SESSION['logado_dados']['nivel'] == 8)): ?>
    Clique aqui para cadastrar <a href="cadastro_colaborador.php">novos colaboradores</a> ou <a href="cadastro_autor.php">novos autores</a>.
    <?php endif; ?>
</p>

<form method="get" id="form-result" action="cadastro.php">
    <h3 class="titulo"><?=Util::iif($buscar, 'Resultado da busca', 'Mais recentes');?></h3>
	<input type="hidden" name="buscar" value="1" />
	<input type="hidden" name="palavrachave" value="<?=$cadbo->getValorCampo('palavrachave')?>" />
	<input type="hidden" name="buscarpor" value="<?=$cadbo->getValorCampo('buscarpor')?>" />
	<input type="hidden" name="situacao" value="<?=$cadbo->getValorCampo('situacao')?>" />
	<input type="hidden" name="de" value="<?=$cadbo->getValorCampo('de')?>" />
	<input type="hidden" name="ate" value="<?=$cadbo->getValorCampo('ate')?>" />
	<input type="hidden" id="acao" name="acao" value="0" />
	<?php if($_GET['pagina']):?>
	<input type="hidden" name="pagina" value="<?=$_GET['pagina'];?>" />
	<?php endif;?>
	<?php if($_GET['mostrar']):?>
	<input type="hidden" name="mostrar" value="<?=$_GET['mostrar'];?>" />
	<?php endif;?>

    <?php include('includes/cadastro_lista_usuario.php');?>
</form>
</div>

<?php include('includes/rodape.php'); ?>