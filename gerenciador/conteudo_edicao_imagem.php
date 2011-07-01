<?php
include_once('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz().'util/Util.php');
include_once('classes/bo/AlbumImagemEdicaoBO.php');

$editar = (int)$_POST['editar'];
$codalbum = (int)$_GET['cod'];
$edicaodados = (int)$_POST['edicaodados'];

if ($codalbum) $edicaodados = 1;
if (!$editar) $sessao_id = Util::geraRandomico('num');

$albumbo = new AlbumImagemEdicaoBO;
$exibir_form = true;

if (!isset($_SESSION["sess_conteudo_imagens_album"]))
	$_SESSION["sess_conteudo_imagens_album"] = array();
if (!isset($_SESSION["sess_conteudo_autores_ficha"]))
	$_SESSION["sess_conteudo_autores_ficha"] = array();

if ($editar) {
	try {
		$cod_conteudo = $albumbo->editar($_POST, $_FILES);
		$exibir_form = false;
		Header('Location: conteudo_publicado_imagem.php?cod='.$cod_conteudo);
		die;
	} catch (Exception $e) {
		$erro_mensagens = $e->getMessage();
	}
}

$contbo = &$albumbo;
$codformato_class = 2;

if (!$editar) $albumbo->setValorCampo('sessao_id', $sessao_id);
if ($codalbum && !$editar) $albumbo->setDadosCamposEdicao($codalbum);
$permitir_comentarios = $albumbo->getValorCampo('permitir_comentarios');
if (!$codalbum) $permitir_comentarios = true;
$codalbum = (int)$albumbo->getValorCampo('codalbum');
$sessao_id = $albumbo->getValorCampo('sessao_id');

$item_menu = 'conteudo';
$item_submenu = 'inserir';
$nao_carregar_thickbox = true;
include('includes/topo.php');
?>
<script type="text/javascript">
var cod_autor_pessoal = '<?=$_SESSION['logado_dados']['cod'];?>';
var sessao_id = '<?=$sessao_id;?>';
</script>
<script type="text/javascript" src="jscripts/jquery.autocomplete.js"></script>
<script type="text/javascript" src="jscripts/autocompletar.js"></script>
<script type="text/javascript" src="jscripts/ajax.js"></script>
<script type="text/javascript" src="jscripts/edicao.js"></script>
<script type="text/javascript" src="jscripts/conteudo_autores_wiki.js"></script>
<script type="text/javascript" src="jscripts/conteudo.js"></script>
<script type="text/javascript" src="jscripts/imagem.js"></script>

<h2>Conte&uacute;do</h2>

<?php if ($erro_mensagens || $albumbo->verificaErroCampo("titulo") || $albumbo->verificaErroCampo("descricao")): ?>
<div class="box box-alerta">
	<h3>Erro! Preencha os campos obrigat&oacute;rios</h3><?=$erro_mensagens?>
</div>
<?php endif; ?>

<?php if ($exibir_form): ?>
<form action="conteudo_edicao_imagem.php" method="post" enctype="multipart/form-data" name="form-imagem" id="form-imagem">
    <h3 class="titulo">Cadastro de &aacute;lbum</h3>
    <div class="box">
        <fieldset>
			<input type="hidden" name="editar" value="1" />
			<input type="hidden" name="edicaodados" value="<?=$edicaodados?>" />
			<input type="hidden" name="codalbum" value="<?=$codalbum?>" />
			<input type="hidden" name="sessao_id" id="sessao_id" value="<?=$albumbo->getValorCampo("sessao_id")?>" />
			<input type="hidden" id="capa" name="capa" value="<?=$albumbo->getValorCampo("capa")?>" />
			
			<legend>Conte&uacute;do</legend>
			<label for="textfield">T&iacute;tulo<span>*</span></label><br />
	
			<input type="text" name="titulo" class="txt" <?=$albumbo->verificaErroCampo("titulo")?> id="textfield" size="78" maxlength="60" value="<?=htmlentities(stripslashes($albumbo->getValorCampo("titulo")))?>" onkeyup="contarCaracteres(this, 'cont_titulo', 60)" />
			<input type="text" class="txt counter" size="4" disabled="disabled" value="60" id="cont_titulo" /><br />
			
			<label for="textarea">Descri&ccedil;&atilde;o<span>*</span></label><br />
			<textarea name="descricao" cols="60" rows="10" class="mceSimple" id="textarea" <?=$albumbo->verificaErroCampo("descricao")?> onkeyup="contarCaracteres(this, 'cont_descricao', 2000);"><?=Util::clearText($albumbo->getValorCampo("descricao"));?></textarea>
			<input type="text" class="txt counter" value="2000" size="4" disabled="disabled" id="cont_descricao" /><br />
        </fieldset>
    </div>  
	<?php include("includes/conteudo_box_categorias.php"); ?>
    <div class="box">
        <fieldset>
			<legend>Imagens</legend>
			<p>Você pode fazer upload de um ou mais arquivos JPG, GIF ou PNG (tamanho máximo de 5MB cada). Ou você pode compactá-las num arquivo ZIP.</p>
			<label for="fileField1">Procurar</label><br />
			<div id="div_adicionar_mais_imagens"></div>
			<!--<p><a href="javascript:void(0);" onclick="adicionarMaisCampos();">Selecionar mais imagens</a></p>-->
			<input type="button" onclick="enviaImagemGaleria();" class="bt-adicionar" value="Enviar" /><br /><br />
			<div id="mostra_galeria_imagens"></div>
        </fieldset>
    </div>
<?php
include("includes/conteudo_direitos_autorais.php");
if ($_SESSION['logado_dados']['nivel'] == 2)
	include("includes/conteudo_interno_autorizacao.php");
if (($_SESSION['logado_dados']['nivel'] >= 5) || count($_SESSION['logado_dados']['cod_grupo']))
	include("includes/conteudo_interno_autorizacao_colaborador.php");
include("includes/conteudo_interno_pertence_voce.php");
include("includes/conteudo_ficha_tecnica.php");
include("includes/conteudo_autores_wiki.php");
?>
	<div class="box" id="classificar2">
        <fieldset>
			<legend class="">Coment&aacute;rios</legend>
			<div class="" id="box-comentarios">
				<p>Voc&ecirc; pode permitir que os visitantes do portal iTEIA deixem ou n&atilde;o coment&aacute;rios neste conte&uacute;do. Caso voc&ecirc; autorize,  vale ressaltar que eles ser&atilde;o publicados automaticamente na p&aacute;gina. No  entanto, voc&ecirc; e os colaboradores do sistema poder&atilde;o gerenciar todos os  coment&aacute;rios (apagando ou suspendendo os mesmos) atrav&eacute;s do <a href="comentarios.php" target="_blank" class="ext" title="Este link ser&aacute; aberto numa nova janela">menu  principal</a>.</p>
				<p>
					<input type="checkbox" id="checkbox" name="permitir_comentarios" value="1" <?=Util::iif($permitir_comentarios, 'checked="checked"');?> />
					<label for="checkbox">Permitir que sejam publicados coment&aacute;rios.</label>
				</p>
			</div>
        </fieldset>
    </div>
    <div id="botoes" class="box">
		<a href="conteudo.php" class="bt bt-cancelar">Cancelar</a>
		<input type="submit" class="bt-gravar" onclick="javascript:salvaLegendas();" value="Gravar" />
    </div>
</form>
<?php endif; ?>
<script type="text/javascript">
<?php if ($exibir_form): ?>
	adicionarMaisCampos();
	contarCaracteres(document.getElementById("textfield"), "cont_titulo", 60);
	contarCaracteres(document.getElementById("textarea"), "cont_descricao", 2000);
	$('#ficha-tecnica').show();
	$('#sou_autor_conteudo').show();
	<?php if (count($_SESSION["sess_conteudo_imagens_album"][$sessao_id])): ?>
	irPaginaBuscaImagens(1);
<?php endif;
	if ($albumbo->getValorCampo('pertence_voce') == 1)
		echo '$(\'#ficha-tecnica\').show(); $(\'#sou_autor_conteudo\').show();';
	if ($_SESSION['logado_dados']['nivel'] == 2)
		echo '$(\'#sou_autor_conteudo\').show();';
	if ($albumbo->getValorCampo("codcanal") || $albumbo->getValorCampo("codsegmento"))
		echo '$(\'#box-classificar\').show();';
endif;
?>
exibeListaAutoresFicha();
</script>
</div>
<hr />
<?php include('includes/rodape.php'); ?>