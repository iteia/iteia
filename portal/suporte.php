<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
$topo_class = 'cat-regulamento iteia';
$titulopagina = 'Suporte';
include ('includes/topo.php');
?>
    <div id="migalhas"><span class="localizador">Voc� est� em:</span> <a href="/index.php" title="Voltar para a p�gina inicial" id="inicio">In�cio</a> <span class="marcador">&raquo;</span> <span class="atual">Suporte</span></div>
    <div id="conteudo">
      <h2 class="midia">Suporte</h2>
    </div>
<?php
include ('includes/rodape.php');
