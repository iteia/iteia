<?php
include_once('verificalogin.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz().'util/Util.php');
include_once('classes/bo/ColaboradorEdicaoBO.php');

$codcolaborador = (int)$_GET['cod'];
$proprio 		= (int)$_GET['proprio'];

$colaboradorbo = new ColaboradorEdicaoBO;

if ($codcolaborador) {
	$colaboradorbo->setDadosCamposEdicao($codcolaborador);
	$comunicadores = $colaboradorbo->getComunicadoresColaborador($codcolaborador);
	$sitesrelacionados = $colaboradorbo->getSitesColaborador($codcolaborador);
	$arrayRedes = array(1 => 'Pontos de Cultura', 2 => 'Casas Brasil', 3 => 'Cultura Digital', 4 => 'Rede Ecofuturo');
	$autores_participantes = $colaboradorbo->getAutoresParticipantes($codcolaborador);

	$autores_participantes_responsaveis = array();
	$autores_participantes_participantes = array();
	if (count($autores_participantes)){
		foreach($autores_participantes as $key => $value){
			if($value['responsavel']==1)
				$autores_participantes_responsaveis[] = $value;
			else
				$autores_participantes_participantes[] = $value;
		}
	}
}
			
$item_menu = 'cadastro';
$item_submenu = Util::iif($proprio, 'meu_cadastro');
include('includes/topo.php');
if ($aguardando_aprovacao) {
	$codusuario = $codcolaborador;
	include('includes/cadastro_situacao_usuario.php');
}
?>
<h2>Usu&aacute;rios</h2>
<h3 class="titulo">Colaborador cadastrado</h3>
    <div class="box">
		<div class="separador">
			<p><strong>Nome da institui&ccedil;&atilde;o / Ponto de Cultura:</strong><br /><?=$colaboradorbo->getValorCampo('nomeinstituicao');?></p>
			<p><strong>Entidade:</strong><br /><?=$colaboradorbo->getValorCampo('entidade');?></p>
			<p><strong>Descri&ccedil;&atilde;o / Release sobre a institui&ccedil;&atilde;o:</strong><br /><?=nl2br(Util::autoLink($colaboradorbo->getValorCampo('descricao'), 'both', true));?></p>
		</div>
		<?php if($colaboradorbo->getValorCampo('imagem_visualizacao') || $proprio):?>
		<div class="separador">
			<strong>Imagem de exibi&ccedil;&atilde;o:</strong><br />
			<img src="<?=Util::iif($colaboradorbo->getValorCampo('imagem_visualizacao'), "exibir_imagem.php?img=".$colaboradorbo->getValorCampo('imagem_visualizacao')."&amp;tipo=a&amp;s=6", "img/imagens-padrao/colaborador.jpg");?>" width="124" height="124" />
		</div>
		<?php endif; ?>
		<div class="separador">
			<p>
				<?php if($colaboradorbo->getValorCampo('endereco')):?>
				<strong>Endere&ccedil;o:</strong> <?=$colaboradorbo->getValorCampo('endereco');?><br />
				<?php endif; ?>
				<?php if($colaboradorbo->getValorCampo('complemento')):?>
				<strong>Complemento:</strong> <?=$colaboradorbo->getValorCampo('complemento');?><br />
				<?php endif; ?>
				<?php if($colaboradorbo->getValorCampo('bairro')):?>
				<strong>Bairro:</strong> <?=$colaboradorbo->getValorCampo('bairro');?><br />
				<?php endif; ?>
				<strong>Cidade:</strong> <?=Util::iif($colaboradorbo->getValorCampo('codcidade'), $colaboradorbo->getCidade($colaboradorbo->getValorCampo('codcidade')), $colaboradorbo->getValorCampo('cidade'));?><br />
				<strong>Estado:</strong> <?=$colaboradorbo->getEstado($colaboradorbo->getValorCampo('codestado'));?><br />
				<strong>Pa&iacute;s:</strong> <?=$colaboradorbo->getPais($colaboradorbo->getValorCampo('codpais'));?>
			</p>
			
			<?php if(count($colaboradorbo->getValorCampo('rede')) > 1):?>
			<p>
				<strong>Redes:</strong>
				<?php
				if (count($colaboradorbo->getValorCampo('rede') > 2)):
					foreach($colaboradorbo->getValorCampo('rede') as $value):
						if ($value)
							echo $arrayRedes[$value].', ';
					endforeach;
				endif;
				?>
			</p>
			<?php endif; ?>
			<?php if($colaboradorbo->getValorCampo('telefone') || $colaboradorbo->getValorCampo('celular') || $colaboradorbo->getValorCampo('email') || $colaboradorbo->getValorCampo('site')):?>
			<p>
				<?php if($colaboradorbo->getValorCampo('telefone')):?>
				<strong>Telefone:</strong> <?=$colaboradorbo->getValorCampo('telefone');?><br />
				<?php endif; ?>
				<?php if($colaboradorbo->getValorCampo('celular')):?>
				<strong>Celular:</strong> <?=$colaboradorbo->getValorCampo('celular');?><br />
				<?php endif; ?>
				<?php if($colaboradorbo->getValorCampo('email')):?>
				<strong>E-mail:</strong> <?=$colaboradorbo->getValorCampo('email');?><br />
				<?php endif; ?>
				<?php if($colaboradorbo->getValorCampo('site')):?>
				<strong>Site oficial:</strong> <?=Util::iif($colaboradorbo->getValorCampo('site'), "<a href=\"http://".$colaboradorbo->getValorCampo('site')."\" target=\"_blank\" class=\"ext\" title=\"Este link ser&aacute; aberto numa nova janela\">http://".$colaboradorbo->getValorCampo('site')."</a>");?>
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
		<div class="separador">
			<strong>P&aacute;gina do colaborador no iTEIA:</strong> <a href="<?=ConfigVO::URL_SITE.$colaboradorbo->getValorCampo('finalendereco');?>" target="_blank" class="ext" title="Este link ser&aacute; aberto numa nova janela"><?=ConfigVO::URL_SITE.$colaboradorbo->getValorCampo('finalendereco');?></a>
		</div>
<?php if (count($autores_participantes_responsaveis)):?>
      	<div class="separador">
			<strong>Autor responsável:</strong><br />
			<?php foreach($autores_participantes_responsaveis as $key => $value): ?>
				<a href="<?=ConfigVO::URL_SITE."gerenciador/cadastro_autor_publicado.php?cod=".$value['cod_usuario'];?>"><?=$value['nome'];?></a> - <a href="<?=ConfigVO::URL_SITE.$value['url'];?>" target="_blank" class="ext" title="Este link ser&aacute; aberto numa nova janela"><?=ConfigVO::URL_SITE.$value['url'];?></a><br />
			<?php endforeach; ?>
		</div>
<?php endif; ?>
<?php if (count($autores_participantes_participantes)):?>
      	<div class="separador">
			<strong>Autores participantes:</strong><br />
			<?php foreach($autores_participantes_participantes as $key => $value): ?>
				<a href="<?=ConfigVO::URL_SITE."gerenciador/cadastro_autor_publicado.php?cod=".$value['cod_usuario'];?>"><?=$value['nome'];?></a> - <a href="<?=ConfigVO::URL_SITE.$value['url'];?>" target="_blank" class="ext" title="Este link ser&aacute; aberto numa nova janela"><?=ConfigVO::URL_SITE.$value['url'];?></a><br />
			<?php endforeach; ?>
		</div>
<?php endif; ?>
		<?php
		
		if($_SESSION['logado_dados']['nivel'] >= 7){
		?>
		<div class="separador">
			<a href="cadastro_alterar_senha.php?cod=<?=$codcolaborador;?>&amp;height=250&amp;width=305" class="thickbox" title="Alterar senha">Alterar a senha do cadastro</a>
		</div>
		<a href="cadastro_colaborador.php?cod=<?=$codcolaborador;?><?=Util::iif($proprio, '&amp;proprio=1');?>" title="Editar" class="bt">Editar</a>
		<?
		}else{
			foreach($_SESSION['sessao_autores_integrantes']	as $chave => $valor){
				if($chave == $_SESSION['logado_dados']['cod']){
					if((int)$valor['responsavel']==1){
		?>
		<div class="separador">
			<a href="cadastro_alterar_senha.php?cod=<?=$codcolaborador;?>&amp;height=250&amp;width=305" class="thickbox" title="Alterar senha">Alterar a senha do cadastro</a>
		</div>
		<a href="cadastro_colaborador.php?cod=<?=$codcolaborador;?><?=Util::iif($proprio, '&amp;proprio=1');?>" title="Editar" class="bt">Editar</a>
		<?php
					}
				}
			}
		}
		
		?>
	</div>
    <div class="box box-mais">
		<h3>Coisas que voc&ecirc; pode fazer a partir daqui:</h3>
		<ul>
			<li>Cadastrar novos <a href="grupo_edicao.php">grupos</a>, <a href="conteudo_tipo.php">conte&uacute;dos</a>, <a href="agenda_edicao.php">eventos</a> e <a href="banners_edicao.php">banners</a></li>
			<li>Aprovar novos autores, grupos e conteúdos</li>
			<li><a href="<?=ConfigVO::URL_SITE.$colaboradorbo->getValorCampo('finalendereco');?>" target="_blank" class="ext" title="Este link ser&aacute; aberto numa nova janela">Visualizar p&aacute;gina no portal</a></li>
		</ul>
    </div>
</div>
<hr />
<?php include('includes/rodape.php'); ?>