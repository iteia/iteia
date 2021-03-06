<?php
include('verificalogin.php');
include_once("classes/bo/TagBO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz().'util/Util.php');
$tagbo = new TagsBO;

$editar = (int)$_POST['editar'];
$apagar = (int)$_GET["apagar"];
//$palavrachave = stripcslashes(htmlspecialchars_decode(strip_tags($_GET["palavrachave"])));
$palavrachave = $_GET["palavrachave"];

$pagina = (int)Util::iif($_GET['pagina'], $_GET['pagina'], 1);
$inicial = ($pagina - 1) * 30;
$codtag = $_GET["codtag"];

if ($editar) {
	try {
		$cod_tag = $tagbo->editar($_POST);
		
		Header("Location: conteudo_tags.php");
		exit();

	} catch (Exception $e) {
		$erro_mensagens = $e->getMessage();
	}
}

if ($apagar && !$palavrachave) {
	$tagbo->executaAcao($codtag, 1);
	Header("Location: conteudo_tags.php");
	exit();
}

$lista_tags = $tagbo->getListaTags($palavrachave, $inicial);
Util::paginacaoComplementoUrl("&amp;palavrachave=".stripcslashes(htmlentities($palavrachave)));
$paginacao = Util::paginacao($pagina, 30, $lista_tags['total'], 'conteudo_tags.php?tag='.$palavrachave);

$paginatitulo = 'Tags';
$item_menu = "conteudo";
$item_submenu = "tags";
//echo"<!--";
//print_r($_SESSION['querover']);
//echo"-->";
//$_SESSION['querover'] = "";
include('includes/topo.php');

?>
    <h3 class="titulo">Adicionar Tags</h3>
    <div class="box">
      <form action="conteudo_tags.php" method="post" id="frm-add-categoria">
        <fieldset>
        <input type="hidden" name="editar" value="1" />
        <input type="hidden" name="codtag" value="0" />
        <label for="textfield">Nome<span>*</span></label>
        <br />
        <input type="text" name="tag" class="txt" value="<?=htmlentities(stripslashes($tagbo->getValorCampo("tag")))?>" <?=$tagbo->verificaErroCampo("tag")?> id="textfield" />
       
        <input type="submit" value="Adicionar" class="bt-adicionar" />
        </fieldset>
      </form>
    </div>
    <h3 class="titulo">Tags</h3>
<div id="resultado" class="box">
	<form method="get" id="form-result" action="conteudo_tags.php">
	<input type="hidden" name="apagar" value="1" />
	<input type="hidden" id="acao" name="acao" value="0" />
  
<p><label for="textfield2">Filtrar tags</label>
  <br />
      <input name="palavrachave" type="text" class="txt" id="textfield2" /> <input type="submit" value="Buscar" class="bt-buscar" /><br />
  </p>
    <div class="nav">P&aacute;ginas: <?=Util::iif($paginacao['anterior']['num'], "<a href=\"".$paginacao['anterior']['url']."&amp;palavrachave=".stripcslashes(htmlentities($palavrachave))."\">&laquo; Anterior</a>");?> <?=$paginacao['page_string'];?> <?=Util::iif($paginacao['proxima']['num'], "<a href=\"".$paginacao['proxima']['url']."&amp;palavrachave=".stripcslashes(htmlentities($palavrachave))."\">Pr&oacute;xima &raquo;</a>");?></div>
        <hr class="both" />
  <table width="100%" border="1" cellspacing="0" cellpadding="0" id="table-conteudo">
        <thead>
          <tr>
            <th class="col-1" scope="col"><input name="checkbox" type="checkbox" id="check-all" /></th>
            <th class="col-titulo" scope="col">T&iacute;tulo</th>
            <th class="col-conteudo"  scope="col">Conte&uacute;dos</th>
            <th class="col-editar" scope="col">Editar</th>
            <th class="col-remover" scope="col">Apagar</th>
          </tr>
        </thead>
        <tbody>
        	<?php
			foreach ($lista_tags as $value):
				if (intval($value['cod'])):
			?>
          <tr>
            <td class="col-1"><input type="checkbox" name="codtag[]" id="cod_tags" class="check" value="<?=$value['cod'];?>"  /></td>
            <td class="col-titulo"><?=$value['tag'];?></td>
            <td class="col-conteudo"><?=$value['total'];?></td>
            <td class="col-editar"><a href="conteudo_tags_editar.php?cod=<?=$value['cod'];?>&amp;height=100&amp;width=310" title="Editar tag" class="thickbox">Editar</a></td>
            <td class="col-remover"><a href="conteudo_tags.php?apagar=1&amp;codtag[]=<?=$value['cod'];?>">Apagar</a></td>
          </tr>
          	<?php
				endif;
			endforeach;
			?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="5" class="selecionados"><strong>Selecionados:</strong>
                <a href="javascript:submeteAcoesCadastro(1);">Apagar</a> |
                <a href="conteudo_tags_unificar.php?height=250&width=350&palavrachave=<?= stripcslashes(htmlentities($palavrachave))?>&pagina=<?=$pagina;?>" title="Unificar tags" class="thickbox">Unificar</a>
			</td>
          </tr>
        </tfoot>
      </table>
  <div class="nav">P&aacute;ginas: <?=Util::iif($paginacao['anterior']['num'], "<a href=\"".$paginacao['anterior']['url']."\">&laquo; Anterior</a>");?> <?=$paginacao['page_string'];?> <?=Util::iif($paginacao['proxima']['num'], "<a href=\"".$paginacao['proxima']['url']."\">Pr&oacute;xima &raquo;</a>");?></div>
        <hr class="both" />
      </form>
      <hr class="both" />
    </div>
  </div>
<?php include('includes/rodape.php'); ?>
