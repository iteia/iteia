<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$item_menu = "noticias";
$item_submenu = "ajuda";
include('includes/topo.php');
?>
    <h2>Not&iacute;cias</h2>
    <h3 class="titulo">Ajuda</h3>
    <div class="box">
  <p>O m&oacute;dulo de not&iacute;cias permite a divulga&ccedil;&atilde;o mat&eacute;rias jornalisticas  relacionadas a atividades promovidas pelo colaborador. Uma not&iacute;cia  publicada no iTEIA tamb&eacute;m alimenta o portal <a href="http://www.iteia.org.br" target="_blank" class="ext" title="Este link ser&aacute; aberto numa nova janela">iTEIA.org.br</a>, Fundarpe e o sistema de busca AchaNoticias.</p>
  </div>
<?php include('includes/rodape.php'); ?>
