<?php
include_once('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz().'util/Util.php');
include_once('classes/bo/TextoEdicaoBO.php');

$codtexto = (int)$_GET['cod'];
$textobo = new TextoEdicaoBO;

if ($codtexto) {
	$textobo->setDadosCamposEdicao($codtexto);
	$postador = $textobo->getPostadorConteudo($textobo->getValorCampo('codautor'));
	$autores_ficha = $textobo->getAutoresFichaConteudo($codtexto);
	$colaborador = $textobo->getColaboradorConteudo($textobo->getValorCampo('codcolaborador'));
	$categoria = $textobo->getCategoriaConteudo($codtexto);
	$segmento = $textobo->getSegmentoConteudo($codtexto);
	$subarea = $textobo->getSubAreaConteudo($codtexto);
	$conteudo_relacionado = $textobo->getConteudoRelacionado($codtexto);
	$grupo_relacionado = $textobo->getGrupoRelacionado($codtexto);
	$licenca = $textobo->getLicenca($textobo->getValorCampo('codlicenca'));
	$nome_colaborador_aprovacao = $textobo->getColaboradorConteudoAprovado($textobo->getValorCampo('codcolaborador'));
	$lista_colaborador_aprovacao = $textobo->getListaColaboradoresAprovacao($codtexto);
}

$contbo = &$textobo;

if (!$item_menu) $item_menu = 'conteudo';
if (!$nao_carregar) $nao_carregar = 'conteudo';
if (!$chapeu) $chapeu = 'Conte&uacute;do';
include('includes/topo.php');
?>
    <h2><?=$chapeu;?></h2>
<?php
if (!$nao_mostrar_situacao_acao):
	include('includes/conteudo_situacao_acao.php');
elseif ($mostrar_aguardando_aprovacao):
	include('includes/conteudo_situacao_aguardando_aprovacao.php');
elseif ($mostrar_lista_publica):
	include('includes/conteudo_situacao_lista_publica.php');
elseif ($mostrar_aprovado):
	include('includes/conteudo_situacao_aprovado.php');
elseif ($mostrar_reprovado):
	include('includes/conteudo_situacao_reprovado.php');
elseif ($mostrar_notificacao):
	include('includes/conteudo_situacao_notificacao.php');
elseif ($mostrar_recente):
	include('includes/conteudo_situacao_recente.php');
endif;

if ($_SESSION['logado_dados']['nivel'] == 2 || $_SESSION['logado_dados']['nivel'] == 5 || $_SESSION['logado_dados']['nivel'] == 6)
	if ($textobo->getValorCampo('codautor') != $_SESSION['logado_dados']['cod']) $nao_exibir_adicionais = true;
?>
<script type="text/javascript" src="jscripts/conteudo.js"></script>
<h3 class="titulo">Texto cadastrado</h3>
    <div class="box">
		<div id="exibe_conteudo" class="separador">
			<h3><?=$textobo->getValorCampo('titulo');?></h3>
			<p><?=nl2br(Util::autoLink($textobo->getValorCampo('descricao'), 'both', true));?></p>
		</div>
		<?php if ($textobo->getValorCampo('arquivo')):
		//print_r($textobo->getValorCampo('arquivo'));die;?>
		
			<div class="separador">
				<strong>Arquivo(s) anexado:</strong>
				<?php foreach($textobo->getValorCampo('arquivo') as $arquivo):?>
				<br /><a href="salvar.php?c=<?=$arquivo['cod'];?>"><?=$arquivo['nome_original'];?></a>
				<?php endforeach;?>
			</div>
		<?php endif; ?>
		<div class="separador">
			<strong>Imagem de exibi&ccedil;&atilde;o:</strong><br />
			<img src="<?=Util::iif($textobo->getValorCampo('imagem_visualizacao'), "exibir_imagem.php?img=".$textobo->getValorCampo('imagem_visualizacao')."&amp;tipo=a&amp;s=6", "img/imagens-padrao/texto.jpg");?>" width="124" height="124" />
		</div>
		<?php if (count($autores_ficha)): ?>
		<div id="autores2" class="separador">
			<strong>Autores deste conte&uacute;do:</strong>
	    	<ul>
	    	<?php foreach ($autores_ficha as $value): ?>
				<li><a href="<?=Util::iif($value['url'], ConfigVO::URL_SITE.$value['url'], 'javascript:void(0);');?>" target="_blank" class="ext" title="Visite a p&aacute;gina deste autor"><?=$value['nome'];?></a> <?=$value['atividade'];?></li>
			<?php endforeach; ?>
			</ul>
	    </div>
		<?php endif;?>
		<div id="autores" class="separador">
			<strong>Postado por:</strong> <a href="<?=ConfigVO::URL_SITE.$postador['url'];?>" target="_blank" class="ext" title="Visite a p&aacute;gina deste autor"><?=$postador['nome'];?></a><br />
			<strong>Autorizado por:</strong>
			<?php if ($colaborador['nome']): ?>
			<a href="<?=ConfigVO::URL_SITE.$colaborador['url'];?>" target="_blank" class="ext" title="Visite a p&aacute;gina deste colaborador"><?=$colaborador['nome'];?></a><br />
			<?php else: ?>
			<i>Ainda n�o autorizado</i><br />
			<?php endif;?>
			<strong>Data de publica&ccedil;&atilde;o: </strong><?=date('d/m/Y - H:i', strtotime($textobo->getValorCampo('datahora')));?>
		</div>
		<div class="separador">
			<strong>Tipo da obra</strong>: <?=$categoria['nome'];?><br />
			<strong>Canal: </strong> <?=$segmento['nome'];?><br />
			<strong>Sub-canal: </strong> <?=$subarea['nome'];?><br />
	        <strong>Tags: </strong> <?=str_replace(';', ',', $textobo->getValorCampo('tags'));?>
		</div>
		<div id="licensas" class="separador">
			<strong>Licen&ccedil;a:</strong><br />
			<?=Util::getTipoLicenca($licenca);?>
		</div>
		<div class="separador">
			<strong>Conte&uacute;dos relacionados: </strong>
			<?php if (!$nao_exibir_link_add_relacionados): ?>
			<a href="conteudo_relacionar.php?cod=<?=$codtexto;?>">clique aqui para vincular outros conte&uacute;dos</a>
			<?php endif; ?>
			<?php if (count($conteudo_relacionado)): ?>
			<ul>
			<?php foreach ($conteudo_relacionado as $value): ?>
				<li><a href="<?=ConfigVO::URL_SITE.$value['url'];?>" target="_blank" class="ext" title="Visite a p&aacute;gina deste conte&uacute;do"><?=$value['titulo'];?></a></li>
			<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</div>
		<?php if (!$nao_exibir_adicionais): ?>
		<a href="conteudo_edicao_texto.php?cod=<?=$codtexto;?>" title="Editar" class="bt">Editar</a>
		<?php endif; ?>
	</div>
<?php if (!$nao_exibir_adicionais): ?>
    <div class="box box-mais">
		<h3>Coisas que voc&ecirc; pode fazer a partir daqui:</h3>
		<ul>
			<li><a href="conteudo_relacionar.php?cod=<?=$codtexto;?>">Relacionar a outros conte&uacute;dos</a></li>
			<li><a href="conteudo_adicionar_autores.php?cod=<?=$codtexto;?>">Adicionar  autores</a></li>
			<li><a href="comentarios.php?cod=<?=$codtexto;?>">Gerenciar coment&aacute;rios</a></li>
			<?php if ($textobo->getValorCampo('publicado')): ?>
				<li><a href="<?=ConfigVO::URL_SITE.$textobo->getValorCampo('url');?>" target="_blank" class="ext" title="Este link ser&aacute; aberto numa nova janela">Visualizar p&aacute;gina no portal</a></li>
			<?php endif; ?>
		</ul>
    </div>
<?php endif; ?>
</div>
<hr />
<?php include('includes/rodape.php'); ?>