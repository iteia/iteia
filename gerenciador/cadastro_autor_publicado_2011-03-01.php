<?php
include_once('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz().'util/Util.php');
include_once('classes/bo/AutorEdicaoBO.php');

$codautor 	= (int)$_GET['cod'];
$proprio 	= (int)$_GET['proprio'];
$visual 	= (int)$_GET['visual'];

$autorbo = new AutorEdicaoBO;

if ($codautor) {
	$autorbo->setDadosCamposEdicao($codautor);
	$comunicadores = $autorbo->getComunicadoresAutor($codautor);
	$sitesrelacionados = $autorbo->getSitesAutor($codautor);
	$colaboradores_responsaveis = $autorbo->getColaboradoresRepresentantes($codautor);
	$colaboradores_participantes = $autorbo->getColaboradoresParticipantes($codautor);
}

$wiki = $autorbo->getAutorWiki($codautor);

if ((($_SESSION['logado_dados']['nivel'] <= 5) && ($_SESSION['logado_cod'] != $codautor) && !$wiki)) {
        header('location: '.ConfigVO::getUrlSite().'gerenciador/cadastro_autor_publicado.php?cod='.$_SESSION['logado_cod']);
        exit();
}

if (!$item_menu) $item_menu = Util::iif(!$visual, 'cadastro');
if (!$item_submenu) $item_submenu = Util::iif($proprio, 'meu_cadastro');
include('includes/topo.php');
if ($aguardando_aprovacao) {
	$codusuario = $codautor;
	include('includes/cadastro_situacao_usuario.php');
}
?>
<h2><?=Util::iif($visual, 'Meu cadastro', 'Usu&aacute;rios');?></h2>
<h3 class="titulo"><?=Util::iif($visual, 'Meu cadastro', 'Autor cadastrado');?></h3>
    <div class="box">
		<div class="separador">
			<p>
				<strong>Nome completo:</strong> <?=$autorbo->getValorCampo('nomecompleto');?><br />
				<strong>Nome artistico: </strong> <?=$autorbo->getValorCampo('nomeartistico');?>
				<?php if ($autorbo->getValorCampo('datanascimento') != '16/11/1977'): ?>
				<?=Util::iif($autorbo->getValorCampo('datanascimento'), '<br /><strong>Nascido em:</strong> ' . $autorbo->getValorCampo('datanascimento'), '');?>
				<?php endif; ?>
				<?php if ($autorbo->getValorCampo('datafalecimento') != '16/11/1977'): ?>
				<?=Util::iif($autorbo->getValorCampo('datafalecimento'), '<br /><strong>Falecido em:</strong> ' . $autorbo->getValorCampo('datafalecimento'), '');?>
				<?php endif; ?>
			</p>
			<p><strong>Biografia:</strong><br /><?=nl2br(Util::autoLink($autorbo->getValorCampo('biografia'), 'both', true));?></p>
		</div>
		<?php if($autorbo->getValorCampo('imagem_visualizacao') || $proprio):?>
		<div class="separador">
			<strong>Imagem de exibi&ccedil;&atilde;o:</strong><br /><img src="<?=Util::iif($autorbo->getValorCampo('imagem_visualizacao'), "exibir_imagem.php?img=".$autorbo->getValorCampo('imagem_visualizacao')."&amp;tipo=a&amp;s=6", "img/imagens-padrao/autor.jpg");?>" id="img_exibicao" width="124" height="124" />
			<?php if ($proprio): ?>
				<br /><a href="trocar_imagem_index.php?cod=<?=$codautor;?>&amp;height=210&amp;width=305" title="Imagem ilustrativa" class="thickbox">Alterar imagem</a>
			<?php endif; ?>
		</div>
		<?php endif;?>
		<div class="separador">
			<p>
				<?php if($autorbo->getValorCampo('endereco')):?>
				<strong>Endere&ccedil;o:</strong> <?=$autorbo->getValorCampo('endereco');?><br />
				<?php endif; ?>
				<?php if($autorbo->getValorCampo('complemento')):?>
				<strong>Complemento:</strong> <?=$autorbo->getValorCampo('complemento');?><br />
				<?php endif; ?>
				<?php if($autorbo->getValorCampo('bairro')):?>
				<strong>Bairro:</strong> <?=$autorbo->getValorCampo('bairro');?><br />
				<?php endif; ?>
				<strong>Cidade:</strong> <?=Util::iif($autorbo->getValorCampo('codcidade'), $autorbo->getCidade($autorbo->getValorCampo('codcidade')), $autorbo->getValorCampo('cidade'));?><br />
				<strong>Estado:</strong> <?=$autorbo->getEstado($autorbo->getValorCampo('codestado'));?><br />
				<strong>Pa&iacute;s:</strong> <?=$autorbo->getPais($autorbo->getValorCampo('codpais'));?>
			</p>
			<?php if($autorbo->getValorCampo('telefone') || $autorbo->getValorCampo('celular') || $autorbo->getValorCampo('email') || $autorbo->getValorCampo('site')):?>
			<p>
				<?php if($autorbo->getValorCampo('telefone')):?>
				<strong>Telefone:</strong> <?=$autorbo->getValorCampo('telefone');?><br />
				<?php endif; ?>
				<?php if($autorbo->getValorCampo('celular')):?>
				<strong>Celular:</strong> <?=$autorbo->getValorCampo('celular');?><br />
				<?php endif; ?>
				<?php if($autorbo->getValorCampo('email')):?>
				<strong>E-mail:</strong> <?=$autorbo->getValorCampo('email');?><br />
				<?php endif; ?>
				<?php if($autorbo->getValorCampo('site')):?>
				<strong>Site oficial:</strong> <?=Util::iif($autorbo->getValorCampo('site'), "<a href=\"http://".$autorbo->getValorCampo('site')."\" target=\"_blank\" class=\"ext\" title=\"Este link ser&aacute; aberto numa nova janela\">http://".$autorbo->getValorCampo('site')."</a>");?>
				<?php endif; ?>
			</p>
			<?php endif; ?>
			<?php if(sizeof($comunicadores)>0):?>
			<p>
				<strong>Comunicadores:</strong><br />
				<?php foreach ($comunicadores as $key => $value): ?>
					<?=$value['comunicador'];?> - <?=$value['nome_usuario'];?><br />
				<?php endforeach; ?>
			</p>
			<?php endif; ?>
			<?php if(sizeof($sitesrelacionados)>0):?>
			<p>
				<strong>Sites relacionados:</strong><br />
				<?php foreach ($sitesrelacionados as $key => $value): ?>
					<?=$value['site'];?> - <a href="<?=$value['url'];?>" target="_blank" class="ext" title="Este link ser&aacute; aberto numa nova janela"><?=$value['url'];?></a><br />
				<?php endforeach; ?>
			</p>
			<?php endif; ?>
		</div>
<?php if (!$nao_mostrar_dados_edicao): ?>
	<?php if ($_SESSION['logado_dados']['nivel'] > 6 || $proprio): ?>
		<div class="separador">
			<strong>P&aacute;gina do autor no iTEIA:</strong> <a href="<?=ConfigVO::URL_SITE.$autorbo->getValorCampo('finalendereco');?>" target="_blank" class="ext" title="Este link ser&aacute; aberto numa nova janela"><?=ConfigVO::URL_SITE.$autorbo->getValorCampo('finalendereco');?></a><br />
		</div>

		<?php if (count($colaboradores_responsaveis)): ?>
		<div class="separador">
			<strong>Colaboradores que represento:</strong><br />
			<?php foreach($colaboradores_responsaveis as $key => $value): ?>
				<a href="<?=ConfigVO::URL_SITE."gerenciador/cadastro_colaborador_publicado.php?cod=".$value['cod_usuario'];?>"><?=$value['nome'];?></a> - <a href="<?=ConfigVO::URL_SITE.$value['url'];?>" target="_blank" class="ext" title="Este link ser&aacute; aberto numa nova janela"><?=ConfigVO::URL_SITE.$value['url'];?></a><br />
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<?php if (count($colaboradores_participantes)): ?>
		<div class="separador">
			<strong>Colaboradores que participo:</strong><br />
			<?php foreach($colaboradores_participantes as $key => $value): ?>
				<a href="<?=ConfigVO::URL_SITE."gerenciador/cadastro_colaborador_publicado.php?cod=".$value['cod_usuario'];?>"><?=$value['nome'];?></a> - <a href="<?=ConfigVO::URL_SITE.$value['url'];?>" target="_blank" class="ext" title="Este link ser&aacute; aberto numa nova janela"><?=ConfigVO::URL_SITE.$value['url'];?></a><br />
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
		
		<div class="separador">
			<a href="cadastro_alterar_senha.php?cod=<?=$autorbo->getValorCampo('codautor');?>&amp;height=250&amp;width=305&amp;TB_iframe=true" class="thickbox" title="Alterar senha">Alterar a senha do cadastro</a>
		</div>
	<?php endif; ?>
	<?php if ($_SESSION['logado_dados']['nivel'] > 6 || $proprio || $wiki): ?>
		<a href="cadastro_autor.php?cod=<?=$codautor;?><?=Util::iif($proprio, "&amp;proprio=$proprio");?>" title="Editar" class="bt">Editar</a>
	<?php endif; ?>
<?php endif; ?>
	</div>
<?php if (!$nao_mostrar_dados_edicao): ?>
    <div class="box box-mais">
		<h3>Coisas que voc&ecirc; pode fazer a partir daqui:</h3>
		<ul>
		<?php if ($_SESSION['logado_dados']['nivel'] > 6 || $proprio): ?>
			<li>Cadastrar novos <?php // <a href="grupo_edicao.php">grupos</a>, ?><a href="conteudo_tipo.php">conte&uacute;dos</a>, <a href="noticia_edicao.php">not&iacute;cias</a><?php if ($_SESSION['logado_dados']['nivel'] > 2): ?>, <a href="banners_edicao.php">an&uacute;ncios</a><?php endif; ?> e <a href="agenda_edicao.php">eventos</a>
			</li>
		<?php endif; ?>
			<li><a href="<?=ConfigVO::URL_SITE.$autorbo->getValorCampo('finalendereco');?>" target="_blank" class="ext" title="Este link ser&aacute; aberto numa nova janela">Visualizar p&aacute;gina no portal</a></li>
		</ul>
    </div>
<?php endif; ?>
</div>
<hr />
<?php include('includes/rodape.php'); ?>