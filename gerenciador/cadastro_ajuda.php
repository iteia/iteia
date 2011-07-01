<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

$item_menu = "cadastro";
$item_submenu = "ajuda";
include('includes/topo.php');
?>
    <h2>Agenda</h2>
    <h3 class="titulo">Ajuda</h3>
    <div class="box">
  <p><strong>Colaborador</strong></p>
  <p>Cadastro destinado a coletivos de cultura que agregam diversos grupos e  produtores culturais. Centros Culturais, Funda&ccedil;&otilde;es, Grupos de Economia  Solid&aacute;ria, Cultura Digital entre outros. </p>
  <p> Os colaboradores do  projeto formam a curadoria do projeto, sendo respons&aacute;veis por autorizar  quais conte&uacute;dos submetidos podem compor o acervo cultural.</p>
  <p> O colaborador tem um espa&ccedil;o pr&oacute;prio com suas informa&ccedil;&otilde;es e todos os conte&uacute;dos, autores e grupos aprovados pelo mesmo.</p>
  <p><strong>Autor</strong></p>
  <p>Cadastro destinado aos produtores culturais brasileiros. Cadastro de  pessoas f&iacute;sicas respons&aacute;veis pela autoria dos conte&uacute;dos existentes no  portal. Caso o cadastro de um autor seja realizado por outra pessoa  f&iacute;sica o mesmo ficar&aacute; pendente at&eacute; que um colaborador aprove o mesmo.</p>
</div>
<?php include('includes/rodape.php'); ?>
