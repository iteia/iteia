<?php
include_once('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz()."util/Util.php");
include_once("classes/bo/AlbumAudioEdicaoBO.php");

$editar = (int)$_POST['editar'];
$codaudio = (int)$_GET["cod"];
$edicaodados = (int)$_POST["edicaodados"];

if ($codaudio) $edicaodados = 1;
if (!$editar) $sessao_id = Util::geraRandomico('num');

$audiobo = new AlbumAudioEdicaoBO;
$exibir_form = true;

if (!isset($_SESSION["sess_conteudo_audios_album"]))
	$_SESSION["sess_conteudo_audios_album"] = array();
if (!isset($_SESSION["sess_conteudo_autores_ficha"]))
	$_SESSION["sess_conteudo_autores_ficha"] = array();

	
if ($editar) {
	try {
		$cod_conteudo = $audiobo->editar($_POST, $_FILES);
		$exibir_form = false;
		Header("Location: conteudo_publicado_audio.php?cod=".$cod_conteudo);
		exit();
	} catch (Exception $e) {
		$erro_mensagens = $e->getMessage();
	}
}

$codformato_class = 3;
$contbo = &$audiobo;

if (!$editar) $audiobo->setValorCampo('sessao_id', $sessao_id);
$sessao_id = $audiobo->getValorCampo('sessao_id');
if ($codaudio && !$editar) $audiobo->setDadosCamposEdicao($codaudio);
$permitir_comentarios = $audiobo->getValorCampo("permitir_comentarios");
if (!$codaudio) $permitir_comentarios = true;
$codaudio = (int)$audiobo->getValorCampo("codaudio");
if (count($_SESSION["sess_conteudo_audios_album"][$sessao_id])) $possui_audios = true;

$item_menu = 'conteudo';
$item_submenu = 'inserir';
$nao_carregar_thickbox = true;
$jquerynova = true;

include('includes/topo.php');
?>
<script language="javascript" type="text/javascript">
var cod_autor_pessoal = '<?=$_SESSION['logado_dados']['cod'];?>';
var sessao_id = '<?=$sessao_id;?>';
</script>
<script type="text/javascript" src="jscripts/conteudo.js"></script>
<script type="text/javascript" src="jscripts/audio.js"></script>
<script type="text/javascript" src="jscripts/jquery.autocomplete.js"></script>
<script type="text/javascript" src="jscripts/autocompletar.js"></script>
<script type="text/javascript" src="jscripts/ajax.js"></script>
<script type="text/javascript" src="jscripts/edicao.js"></script>
<script type="text/javascript" src="jscripts/conteudo_autores_wiki.js"></script>

<h2>Conte&uacute;do</h2>

<?php if ($erro_mensagens || $audiobo->verificaErroCampo("titulo") || $audiobo->verificaErroCampo("descricao")): ?>
<div class="box box-alerta">
<h3>Erro! Preencha os campos obrigat&oacute;rios</h3><?=$erro_mensagens?>
</div>
<?php endif; ?>

<?php if ($exibir_form): ?>
<form action="conteudo_edicao_audio.php" method="post" enctype="multipart/form-data" id="form-audio">
	<h3 class="titulo">Cadastro de &Aacute;udio</h3>
		<div class="box">
			<fieldset>

				<input type="hidden" name="editar" value="1" />
				<input type="hidden" name="edicaodados" value="<?=$edicaodados?>" />
				<input type="hidden" name="codaudio" value="<?=$codaudio?>" />
				<input type="hidden" name="imgtemp" id="imgtemp" value="<?=$audiobo->getValorCampo('imgtemp')?>" />
				<input type="hidden" name="sessao_id" id="sessao_id" value="<?=$audiobo->getValorCampo("sessao_id")?>" />
				<input type="hidden" name="possui_audios" id="possui_audios" value="<?=(int)$possui_audios?>" />
		
        <legend>Conte&uacute;do</legend>
        <label for="textfield">T&iacute;tulo<span>*</span></label>
        <br />

        <input type="text" class="txt" id="textfield" size="78" maxlength="60" name="titulo" <?=$audiobo->verificaErroCampo("titulo")?> value="<?=htmlentities(stripslashes($audiobo->getValorCampo("titulo")))?>" onkeyup="contarCaracteres(this, 'cont_titulo', 60)" />
        <input type="text" class="txt counter" value="60" size="4" disabled="disabled" id="cont_titulo" />

        <br />
        <label for="textarea">Descri&ccedil;&atilde;o<span>*</span></label>
        <br />
        <textarea name="descricao" cols="60" rows="10" class="mceSimple" id="textarea" <?=$audiobo->verificaErroCampo("descricao")?> onkeyup="contarCaracteres(this, 'cont_descricao', 2000);"><?=Util::clearText($audiobo->getValorCampo("descricao"));?></textarea>
        <input type="text" class="txt counter" value="2000" size="4" disabled="disabled" id="cont_descricao" />
        </fieldset>

        <div class="box-imagem">
    	<div class="visualizar-img" id="div_imagem_exibicao">
<?php
	if ($audiobo->getValorCampo('imgtemp')) {
?>
        	<img src="exibir_imagem_temp.php?img=<?=$audiobo->getValorCampo('imgtemp')?>" id="imagem_exibicao" width="124" height="124" alt="" />
<?php
	}
	else {
		if (!$audiobo->getValorCampo('imagem_visualizacao')) {
?>
        	<img src="img/imagens-padrao/audio.jpg" id="imagem_exibicao" width="124" height="124" alt="" />
<?php
		}
		else {
?>
	  		<input type="hidden" value="<?=$audiobo->getValorCampo('imagem_visualizacao')?>" name="imagem_visualizacao" />
	  		<img src="exibir_imagem.php?img=<?=$audiobo->getValorCampo('imagem_visualizacao')?>&amp;tipo=a&amp;s=6" width="124" height="124" alt="" id="imagem_exibicao" /><a href="javascript:void(0);" onclick="apagarImagem('<?=$codaudio;?>', 3);" title="Remover" class="remover">Remover imagem</a>
<?php
		}
	}
?>
		</div>
			<a href="trocar_imagem.php?tipo=conteudo&amp;cod=<?=$codaudio;?>&amp;height=180&amp;width=305" title="Imagem ilustrativa" class="thickbox">Inserir imagem</a>
    	</div>

      </div>
      
<?php include("includes/conteudo_box_categorias.php"); ?>

        <div class="box">
        <fieldset>
        <legend>&Aacute;udios</legend>
		<p>Você pode fazer upload de um ou mais arquivos MP3 (tamanho máximo de 20MB cada). Ou você pode compactá-los num arquivo ZIP.</p>
        <label for="fileField1">Procurar</label><br />
        <div id="div_adicionar_mais_faixas"></div>
        
		<!--p><a href="javascript:void(0);" onclick="adicionarMaisCampos();">Selecionar mais faixas</a></p-->
        
		<input type="button" onclick="enviaAudioAlbum();" class="bt-adicionar" value="Enviar" /><br /><br />
        
    </fieldset>
    			<div id="mostra_album_audios"></div>
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

      <div id="botoes" class="box"> <a href="conteudo.php" class="bt bt-cancelar">Cancelar</a>
        <input type="button" class="bt-gravar" onclick="javascript:submeteFormAudio();" value="Gravar" />
      </div>
    </form>
<?php endif; ?>
<script type="text/javascript">
<?php if ($exibir_form): ?>
$(document).ready(function() {
	adicionarMaisCampos();
	contarCaracteres(document.getElementById("textfield"), "cont_titulo", 60);
	contarCaracteres(document.getElementById("textarea"), "cont_descricao", 2000);
	$('#ficha-tecnica').show();
	$('#sou_autor_conteudo').show();
<?php if (count($_SESSION["sess_conteudo_audios_album"][$sessao_id])): ?>
	irPaginaBuscaAudios(1);
<?php endif; ?>
});
<?php
	if ($audiobo->getValorCampo('pertence_voce') == 1)
		echo '$(\'#ficha-tecnica\').show(); $(\'#sou_autor_conteudo\').show();';
	if ($_SESSION['logado_dados']['nivel'] == 2)
		echo '$(\'#sou_autor_conteudo\').show();';
	if ($audiobo->getValorCampo("codcanal") || $audiobo->getValorCampo("codsegmento")):
		echo '$(\'#box-classificar\').show();';
	endif;
endif;
?>
exibeListaAutoresFicha();
</script>
</div>
<hr />
<?php include('includes/rodape.php'); ?>