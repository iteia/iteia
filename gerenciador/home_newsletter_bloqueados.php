<?php
include('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");

include_once('classes/bo/Newsletter_ListaEdicaoBO.php');
$newsbo = new Newsletter_ListaEdicaoBO;

if($_POST['novo']){
    $retorno = $newsbo->bloquearEmail($_POST['email']);
}

include_once('classes/bo/BuscaNewsletterBloqueadosBO.php');
$buscabo = new BuscaNewsletterBloqueadosBO();

//$pagina  = (int)GlobalSiteUtil::iif($_GET['pagina'], $_GET['pagina'], 1);
$pagina  = (int)Util::iif($_GET['pagina'], $_GET['pagina'], 1);
$inicial = ($pagina - 1) * 10;

if($_GET['cod']){
    $buscabo->getNewsletterUsuariosListasBusca($_GET, $inicial, 10);
}

$editar	= (int)$_POST['editar'];

$erro = false;

//echo $editar;die;
if ($editar) {
	try {
		$cod_lista_ok = $newsbo->editar($_POST, $_FILES);
	} catch (Exception $e) {
		$erro = true;
		$erro_mensagem = $e->getMessage();
	}
}

$resultado = $buscabo->getNewsletterUsuariosListasBusca($_GET, $inicial, 10);
//$paginacao = GlobalSiteUtil::paginacao($pagina, 10, $resultado['total'], 'newsletter_listar.kmf?buscar=1', ' ');
$paginacao = Util::paginacao($pagina, 10, $resultado['total'], 'home_newsletter_bloqueados.php?buscar=1', ' ');

$paginatitulo = 'Boletim';
$item_menu = "boletim";
$item_submenu = "bloqueados_boletim";
include('includes/topo.php');
?>
<script type="text/javascript" src="jscripts/funcoes.js"></script>
<script type="text/javascript" src="jscripts/home_newsletter.js"></script>

    <h2>Boletim</h2>
    
<?php if ($erro_mensagem): ?>
<div class="box box-alerta">
<h3>Erro! Preencha os campos obrigat&oacute;rios</h3><?=$erro_mensagem?>
</div>
<?php endif; ?>

<?php if ($cod_lista_ok): ?>
<div class="box box-dica">
<h3>Lista cadastrada com sucesso!</h3>
</div>
<?php endif; ?>
	
	<form  method="post" action="home_newsletter_bloqueados.php" id="aa">
      <h3 class="titulo">Bloquear email </h3>
      <div class="box">
		<input type="hidden" name="novo" value="1" />
        
        <label for="label3">Digite o email</label>:
            <br />
            <input type="text" class="txt" <?=$newsbo->verificaErroCampo("email")?> name="email" value="<?=$newsbo->getValorCampo('titulo')?>" id="label3"  />
            <input name="submit2" type="submit" class="bt-gravar" value="inserir" />

     </div>
    </form>
    
    </div>
  <hr />

<form action="home_newsletter_bloqueados.php" method="get" enctype="multipart/form-data" id="form-result" class="form-tooltip">
<input type="hidden" name="buscar" id="buscar" value="1" />
<input type="hidden" name="acao" id="acao" value="0" />
  
  <h3 class="titulo">Listas de emails bloqueados</h3>
  <div id="resultado" class="box">
   <div>
     <label for="label">Filtrar:</label>
     <br />
     <input type="text" name="titulo" class="txt" id="label"  />
     <input name="button" type="submit" onclick="this.form.submit();" class="bt-gravar" value="Filtrar" />
   </div>

   <div class="view">Exibindo
         <select name="mostrar" onchange="submeteBuscaCadastro();" id="select3">
          <option value="10" <?=($mostrar == 10) ? 'selected="selected"' : '';?>>10</option>
          <option value="20" <?=($mostrar == 20) ? 'selected="selected"' : '';?>>20</option>
          <option value="30" <?=($mostrar == 30) ? 'selected="selected"' : '';?>>30</option>
        </select>
        de <strong><?=$paginacao['num_total'];?></strong></div>
      <div class="nav">P&aacute;ginas: <?=($paginacao['anterior']['num'] ? "<a href=\"".$paginacao['anterior']['url']."\">&laquo; Anterior</a> " : " ");?> <?=$paginacao['page_string'];?> <?=($paginacao['proxima']['num'] ? " <a href=\"".$paginacao['proxima']['url']."\">Pr&oacute;xima &raquo;</a> " : " ");?> </div>
    
    <table width="100%" border="1" cellspacing="0" cellpadding="0" id="table-result">
      <thead>
        <tr>
          <th class="col-1"  scope="col"><input type="checkbox" id="check-all"  /></th>
          <th class="col-titulo"  scope="col">Email</th>
          <th class="col-conteudo" scope="col">Motivo</th>
          <th class="col-conteudo" scope="col">Lista</th>
          <th class="col-data" scope="col">Data</th>
          <th class="col-remover" scope="col">Desbloquear</th>
        </tr>
      </thead>
      <tbody>
<?php
//endif;
	if (count($resultado['resultado'])):
		foreach($resultado['resultado'] as $keya => $valuea):
?>
        <tr>
          <td class="col-1"><input type="checkbox" name="cod[]" value="<?=$valuea['cod_usuario'];?>" class="check" /></td>
          <td class="col-titulo"><span class="col-conteudo"><?=htmlentities($valuea['email']);?></span></td>
          <td class="col-conteudo"><?=$valuea['motivo'];?></td>
          <td class="col-conteudo"><?=htmlentities($valuea['lista']);?></td>
          <td class="col-data"><?=date("d/m/Y", strtotime($valuea['data_descadastro']));?></td>
          <td class="col-remover"><a href="home_newsletter_bloqueados.php?cod[]=<?=$valuea['cod_usuario'];?>&acao=1" title="Remover">Remover</a></td>
        </tr>
<?php
		endforeach;
	endif;
	//$lista = $value['lista'];
?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="6" class="selecionados"><strong>Selecionados:</strong> <a href="javascript:;" onclick="javascript:submeteAcoesGerenciar('form-result', 1, 0, 0);"><span class="col-remover">Desbloquear</span></a></td>
        </tr>
      </tfoot>
    </table>
    <div class="nav">P&aacute;ginas: <?=($paginacao['anterior']['num'] ? "<a href=\"".$paginacao['anterior']['url']."\">&laquo; Anterior</a> " : " ");?> <?=$paginacao['page_string'];?> <?=($paginacao['proxima']['num'] ? " <a href=\"".$paginacao['proxima']['url']."\">Pr&oacute;xima &raquo;</a> " : " ");?> </div>
      <hr class="both" />
  </div>
</form>

<?php include('includes/rodape.php'); ?>
