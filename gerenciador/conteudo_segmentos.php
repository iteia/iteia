<?php
include('verificalogin.php');
include_once("classes/bo/SegmentoBO.php");
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");
$segbo = new SegmentoEdicaoBO;

$editar = (int)$_POST['editar'];
$palavrachave = strip_tags($_GET["palavrachave"]);
$pagina = (int)Util::iif($_GET['pagina'], $_GET['pagina'], 1);
$inicial = ($pagina - 1) * 10;
$codsegmento = (int)$_GET["cod"];

if ($editar) {
	try {
		$cod_segmento = $segbo->editar($_POST, $_FILES);
		$exibir_form = false;

		Header("Location: conteudo_segmentos.php");
		exit();

	} catch (Exception $e) {
		$erro_mensagens = $e->getMessage();
	}
}

//$pais = $segbo->getSegmentosPai();
$pais = $segbo->getSegmentosPai($palavrachave, $inicial);
$lista_segmentos = $segbo->getListaSegmentos($_GET);
$paginacao = Util::paginacao($pagina, 10, $pais['total'], 'conteudo_segmentos.php?palavrachave='.$palavrachave);

$paginatitulo = 'Canais&nbsp;';
$item_menu = "conteudo";
$item_submenu = "segmentos";
$crop = true;
include('includes/topo.php');

if ($codsegmento && !$editar) {
	$segbo->setDadosCamposEdicao($codsegmento);
}

$codsegmento = (int)$segbo->getValorCampo("codsegmento");
?>
    <h2>Conte&uacute;do &gt; Segmentos</h2>

<?php if ($erro_mensagens || $segbo->verificaErroCampo("titulo") || $segbo->verificaErroCampo("descricao")): ?>
<div class="box box-alerta">
<h3>Erro! Preencha os campos obrigat&oacute;rios</h3><?=$erro_mensagens?>
</div>
<?php endif; ?>

    <h3 class="titulo">Adicionar Canal</h3>
    <div class="box">
      <form action="conteudo_segmentos.php" method="post" id="frm-add-categoria">
        <fieldset>

		<input type="hidden" name="editar" value="1" />
		<input type="hidden" name="codsegmento" value="<?=$codsegmento?>" />
		<input type="hidden" name="imgtemp" id="imgtemp" value="<?=$segbo->getValorCampo('imgtemp')?>" />

		<label for="textfield">Nome<span>*</span></label>
        <br />
        <input type="text" class="txt" id="textfield" maxlength="100" name="nome" <?=$segbo->verificaErroCampo("nome")?> value="<?=htmlentities(stripslashes($segbo->getValorCampo("nome")))?>" />
        <br />
        <label for="select">Canal principal</label>
        <br />
        <select id="select" name="codpai">
        <option value="0">Nenhum</option>
        <?php
        $arr = $segbo->getSegmentosPai(null,null);
        array_shift($arr);
        ?>
		<?php foreach ($arr as $value): ?>
        	<option value="<?=$value['cod_segmento'];?>" <?=Util::iif($segbo->getValorCampo("codpai") == $value['cod_segmento'], 'selected="selected"');?>><?=$value['nome'];?></option>
        <?php endforeach; ?>
        </select>
        <br />
        <label for="textarea">Descri&ccedil;&atilde;o</label>
        <br />
        <textarea id="textarea" cols="60" rows="10" name="descricao" <?=$segbo->verificaErroCampo("descricao")?>><?=htmlentities(stripslashes($segbo->getValorCampo("descricao")))?></textarea>
        <p><input type="checkbox" <?=Util::iif($segbo->getValorCampo("verbete"), 'checked="checked"');?> id="checkbox2" name="verbete" value="1" class="checkbox" />
        <label for="checkbox2">Integrar &agrave; lista de verbetes do dicion&aacute;rio cultural</label>
        </p>

<div class="box-imagem">
    	<div class="visualizar-img" id="div_imagem_exibicao">
<?php
	if ($segbo->getValorCampo('imgtemp')) {
?>
        	<img src="exibir_imagem_temp.php?img=<?=$segbo->getValorCampo('imgtemp')?>" id="imagem_exibicao" width="124" height="124" alt="" />
<?php
	}
	else {
		if (!$segbo->getValorCampo('imagem_visualizacao')) {
?>
        	<img src="img/imagens-padrao/colaborador.jpg" id="imagem_exibicao" width="124" height="124" alt="" />
<?php
		}
		else {
?>
	  		<input type="hidden" value="<?=$segbo->getValorCampo('imagem_visualizacao')?>" name="imagem_visualizacao" />
	  		<img src="exibir_imagem.php?img=<?=$segbo->getValorCampo('imagem_visualizacao')?>&amp;tipo=a&amp;s=6" width="124" height="124" alt="" id="imagem_exibicao" /><a href="javascript:void(0);" onclick="apagarImagem('<?=$codsegmento;?>', '8');" title="Remover" class="remover">Remover imagem</a>
<?php
		}
	}
?>
		</div>
			<a href="trocar_imagem.php?tipo=conteudo&amp;cod=<?=$codtexto;?>&amp;height=180&amp;width=305" title="Imagem ilustrativa" class="thickbox">Trocar imagem</a>
			<!--<a href="trocar_imagem_2.php?tipo=conteudo&amp;cod=<?=$codtexto;?>&amp;height=400&amp;width=600" title="Imagem ilustrativa" class="thickbox">Trocar imagem</a>-->
			<!--<a href="trocar_imagem_3.php?tipo=conteudo&amp;cod=<?=$codtexto;?>&amp;height=400&amp;width=800" title="Imagem ilustrativa" class="thickbox">Trocar imagem</a>-->
    	</div>

        <input type="submit" value="Gravar" class="bt-adicionar" />
        </fieldset>
      </form>
    </div>
    <h3 class="titulo">Canais</h3>
    <div id="resultado" class="box">
    <form method="get" id="form-result" action="conteudo_segmentos.php">
    <input type="hidden" id="acao" name="acao" value="0" />
<p><label for="textfield2">Filtrar canais</label>
  <br />
    
      <input name="palavrachave" type="text" class="txt" id="textfield2" /> <input type="submit" value="Buscar" class="bt-buscar" />
  </p>
    <div class="nav">P&aacute;ginas: <?=Util::iif($paginacao['anterior']['num'], "<a href=\"".$paginacao['anterior']['url']."\">&laquo; Anterior</a>");?> <?=$paginacao['page_string'];?> <?=Util::iif($paginacao['proxima']['num'], "<a href=\"".$paginacao['proxima']['url']."\">Pr&oacute;xima &raquo;</a>");?></div>
        <hr class="both" />
      <table width="100%" border="1" cellspacing="0" cellpadding="0" id="table-result">
        <thead>
          <tr>
            <th class="col-1"  scope="col"><input type="checkbox" id="check-all"  /></th>
            <th class="col-img"  scope="col">Miniaturas</th>
            <th class="col-titulo"  scope="col"><span class="col-titulo">Nome</span></th>
            <th class="col-conteudo"  scope="col">Verbete</th>
            <th class="col-conteudo"  scope="col">Conte&uacute;dos</th>
            <th class="col-editar" scope="col">Editar</th>
          </tr>
        </thead>
        <tbody>
            <?php array_shift($pais); ?>
        	<?php foreach ($pais as $key => $pai): ?>
          <tr>
            <td class="col-1"><input type="checkbox" class="check" name="codsegmento[]" value="<?=$pai['cod_segmento'];?>" /></td>
            <td class="col-img"><?=Util::iif($pai['imagem'],"<img src=\"/exibir_imagem.php?img={$pai['imagem']}&amp;tipo=1&amp;s=35\" alt=\"Capa do canal\" height=\"83\" width=\"100\">", '&nbsp;');?></td>
            <td class="col-titulo"><a href="conteudo_segmentos.php?cod=<?=$pai['cod_segmento'];?>" title="Clique para editar"><?=$pai['nome'];?></a></td>
            <td class="col-conteudo"><?=Util::iif($pai['verbete'], 'Sim', 'N&atilde;o');?></td>
            <td class="col-conteudo"><?=$pai['total'];?></td>
            <td class="col-editar"><a href="conteudo_segmentos.php?cod=<?=$pai['cod_segmento'];?>">Editar</a></td>
          </tr>
          		<?php
                if (count($lista_segmentos[$pai['cod_segmento']])):
                    foreach ($lista_segmentos[$pai['cod_segmento']] as $segmento):
            	?>
			          <tr>
			            <td class="col-1"><input type="checkbox" class="check" name="codsegmento[]" value="<?=$segmento['cod'];?>" /></td>
                        <td class="col-img"><?=Util::iif($segmento['imagem'],"<img src=\"/exibir_imagem.php?img={$segmento['imagem']}&amp;tipo=1&amp;s=35\" alt=\"Capa do canal\" height=\"83\" width=\"100\">", '&nbsp;');?></td>
			            <td class="col-titulo">&mdash; <a href="conteudo_segmentos.php?cod=<?=$segmento['cod'];?>" title="Clique para editar"><?=$segmento['nome'];?></a></td>
			            <td class="col-conteudo"><?=Util::iif($segmento['verbete'], 'Sim', 'Não');?></td>
			            <td class="col-conteudo"><?=$segmento['total'];?></td>
			            <td class="col-editar"><a href="conteudo_segmentos.php?cod=<?=$segmento['cod'];?>">Editar</a></td>
			          </tr>
			    <?php
                    endforeach;
                endif;
            endforeach;
        ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="6" class="selecionados"><strong>Selecionados:</strong> <a href="javascript:submeteAcoesCadastro(1);">Apagar</a></td>
          </tr>
        </tfoot>
      </table>
<div class="nav">P&aacute;ginas: <?=Util::iif($paginacao['anterior']['num'], "<a href=\"".$paginacao['anterior']['url']."\">&laquo; Anterior</a>");?> <?=$paginacao['page_string'];?> <?=Util::iif($paginacao['proxima']['num'], "<a href=\"".$paginacao['proxima']['url']."\">Pr&oacute;xima &raquo;</a>");?></div>
        <hr class="both" />
      </form>
    </div>
  </div>
  <hr />
<?php include('includes/rodape.php'); ?>
