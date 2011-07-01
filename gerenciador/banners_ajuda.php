<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$item_menu = "banners";
$item_submenu = "ajuda";
include('includes/topo.php');
?>
    <h2>Eventos</h2>
    <h3 class="titulo">Ajuda</h3>
    <div class="box">
  <p>Esta se&ccedil;&atilde;o &eacute; destinada ao cadastro de espa&ccedil;os publicit&aacute;rios que s&atilde;o  associados aos conte&uacute;dos publicados pelo Colaborador do iTEIA. Estes  banners aparecem durante a visualiza&ccedil;&atilde;o e nos resultados de buscas que  retornem conte&uacute;dos.</p>
  <p> Estes espa&ccedil;os publicit&aacute;rios tamb&eacute;m s&atilde;o vinculados  nos sites parceiros como o iTEIA e o AchaNot&iacute;cias. Cada colaborador  pode cadastrar 20 banners. De acordo com o peso selecionado o espa&ccedil;o  pode aparecer mais ou menos.</p>
  </div>
<?php include('includes/rodape.php'); ?>
