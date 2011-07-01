<?php
$topo_class = 'cat-eventos';
$titulo = htmlentities($conteudo['titulo']);
$titulopagina = $titulo.' | Eventos';
$ativa = 7;
$jsthickbox = 1;
$jsconteudo = 1;
$js_bookmarks = 0 ;
include ('includes/topo.php');
?>
<script type="text/javascript">
function mudaCalendario() {
	window.location = 'eventos.php?mes=' + $('#controle-ano').val() + $('#controle-mes').val();
}
</script>
	<div id="migalhas"><span class="localizador">Você está em:</span> <a href="/" title="Voltar para a página inicial" id="inicio">Início</a><span class="marcador">&raquo;</span> <a href="/eventos">Eventos</a> <span class="marcador">&raquo;</span> <span class="atual"><?=$titulo;?></span></div>
    <div id="conteudo">
      <div class="principal">
      <h2 class="midia">Eventos</h2>
      <div class="vevent">
          <h1 class="summary midia"><?=$titulo;?></h1>
          <div class="description">
			<?php if ($conteudo['imagem']): ?>
			<div class="box-imagem" style="width:250px;">
			<a href="/exibir_imagem.php?img=<?=$conteudo['imagem'];?>&amp;tipo=1&amp;s=41&amp;TB_iframe=true&amp;height=300&amp;width=300" class="thickbox ampliar-imagem">ampliar</a><br/>
			<a class="thickbox" href="/exibir_imagem.php?img=<?=$conteudo['imagem'];?>&amp;tipo=1&amp;s=41&amp;TB_iframe=true&amp;height=300&amp;width=300"><img src="/exibir_imagem.php?img=<?=$conteudo['imagem'];?>&amp;tipo=1&amp;s=42" /></a><a href="/salvar.php?c=<?=$conteudo['cod_conteudo'];?>&amp;f=<?=intval($conteudo['cod_formato']);?>">baixe esta imagem</a></div>
			<?php endif; ?>
            <p><?=nl2br(Util::autoLink($conteudo['descricao']));?></p>
          </div>
          <ul>
            <li><strong>Data:</strong> <span class="dtstart"><?=date('d/m/Y', strtotime($conteudo['data_inicial']));?></span>
			<?php if ($conteudo['data_final'] != '0000-00-00'): ?>
			até <span class="dtend"><?=date('d/m/Y', strtotime($conteudo['data_final']));?></span>
			<?php endif; ?>
			</li>
            <li><strong>Horário:</strong> <?=date('H:i', strtotime($conteudo['hora_inicial']));?>
                    <?php if (strstr($conteudo['hora_final'], '00:00')): ?>
                    às <?=date('H:i', strtotime($conteudo['hora_final']));?>
                    <?php endif; ?>
			</li>
            <li><strong>Local:</strong> <span class="location"><?=$conteudo['local'];?></span></li>
			<?php if ($conteudo['endereco']): ?>
			<li><strong>Endereço:</strong> <?=$conteudo['endereco'];?></li>
			<?php endif; ?>
			<?php if ($conteudo['cidade']): ?>
			<li><strong>Cidade:</strong> <?=$conteudo['cidade'];?></li>
			<?php endif; ?>
			<?php if ($conteudo['site']): ?>
			<li><strong>Site/Hotsite:</strong> <span class="url"><a href="http://<?=$conteudo['site'];?>">http://<?=$conteudo['site'];?></a></span></li>
			<?php endif; ?>
			<?php if ($conteudo['valor']): ?>
			<li><strong>Valor:</strong> <?=$conteudo['valor'];?></li>
			<?php endif; ?>
			<?php if ($conteudo['telefone']): ?>
			<li><strong>Informa&ccedil;&otilde;es:</strong> <?=$conteudo['telefone'];?></li>
			<?php endif; ?>
          </ul>
        </div>
        <div id="texto-ficha">
			<strong>Publicado por:</strong> <a href="/<?=$conteudo['publicado']['url'];?>" title="Ir para página deste autor"><?=strip_tags($conteudo['publicado']['nome']);?></a> em <?=date('d.m.Y', strtotime($conteudo['data_cadastro'])).' &agrave;s '.date('H\\hs', strtotime($conteudo['data_cadastro']))?><br />
<?php if ($conteudo['tags']): ?>
<strong>Tags:</strong> <?=substr(str_replace('</a>', '</a>, ', $conteudo['tags']), 0, -2);?><br />
<?php endif; ?>
<?php if ($conteudo['canal']): ?>
<strong>Canais:</strong> <?=$conteudo['canal'];?>
<?php endif; ?>
</div>
        <div id="controles">
          <ul id="opcoes">
          	<li id="imprimir"><a href="javascript:window.print();">Imprimir</a></li>
            <li id="baixe"><a href="/pdf.php?cod=<?=$cod;?>&amp;baixar=evento">Baixe em PDF</a></li>
            <li id="comente"><a href="#comentar">Comente</a> (<?=$conteudo['comentarios'];?>)</li>
			<?php //<li id="compartilhe"><a href="#bookmark">Compartilhe</a></li>?>
            <li id="denuncie" class="no-border"><a href="/denuncie.php?conteudo=<?=$conteudo['cod_conteudo'];?>">Denuncie</a></li>
          </ul>
          <div id="bookmarks"> <a href="/bookmarks" class="link-oq">O que é isso?</a>
            <ul>
             <li id="b-twitter"><a href="http://twitter.com/home/?status=<?=urlencode(Util::bitly($conteudo['compartilhar']).' '.$titulo.' #iteia')?>">twitter</a></li>
               <li id="b-delicious"><a href="http://del.icio.us/post?url=<?=urlencode(ConfigVO::URL_SITE.'evento.php?cod='.$conteudo['cod_conteudo'].' '.$titulo.' #iteia');?>">delicious</a></li>
			   <li id="b-facebook"><a href="http://www.facebook.com/share.php?u=<?=ConfigVO::URL_SITE.'evento.php?cod='.$conteudo['cod_conteudo'];?>" name="fb_share" type="icon_link" share_url="<?=ConfigVO::URL_SITE.'evento.php?cod='.$conteudo['cod_conteudo'];?>">facebook</a></li>
			  <?php /* 
			  <li id="b-facebook"><a href="http://www.facebook.com/share.php?u=<?=$conteudo['compartilhar'];?>">facebook</a></li>
			  <li id="b-yahoo"><a href="http://buzz.yahoo.com/buzz?targetUrl=<?=Util::bitly($conteudo['compartilhar']);?>">Yahoo buzz</a></li>
              <li id="b-digg"><a href="http://digg.com/submit?phase=2&amp;url=<?=Util::bitly($conteudo['compartilhar']);?>">digg it </a></li>
				   */?>
            </ul>
          </div>
       </div>
		</div>

       <div class="lateral">
	   <?=$this->getHtmlCalendario($mes, $dia); ?>
		<?php include('includes/banners_lateral.php');?>
    </div>
<?php if (count($conteudo['relacionado'])): ?>
	<div id="relacionados" class="principal">
	<h3 class="mais"><span>Lista dos</span> Conteúdos relacionados</h3>
<?php
$temcount = 1;
$colspan = 3;
$cont = 0;
foreach ($conteudo['relacionado'] as $key => $acessado):
	$temul = false;
	if ($temcount == 1)
		echo '<ul class="coluna'.($cont == 1 ? ' no-margin-r' : '').'">';
?>
		<li<?=((!isset($conteudo['autor']['relacionado'][$key + 1]) || $temcount == $colspan ) ? ' class="no-border"' : '')?>>
			<?php if ($acessado['imagem']): ?>
             <div class="capa"><span class="<?=Util::getIconeConteudo($acessado['cod_formato']);?>"><a href="/<?=Util::getFormatoConteudoBusca($acessado['cod_formato']);?>" title="Ir para página do conteudo">Textos</a></span> <a href="/<?=$acessado['url'];?>" title="Ir para página deste conteúdo"><img src="/exibir_imagem.php?img=<?=$acessado['imagem']?>&amp;tipo=<?=$acessado['cod_formato']?>&amp;s=27" alt="Descrição da imagem" width="60" /></a></div>
            <?php elseif ($acessado['imagem_album']): ?>
             <div class="capa"><span class="<?=Util::getIconeConteudo($acessado['cod_formato']);?>"><a href="/<?=Util::getFormatoConteudoBusca($acessado['cod_formato']);?>" title="Ir para página do conteudo">Textos</a></span> <a href="/<?=$acessado['url'];?>" title="Ir para página deste conteúdo"><img src="/exibir_imagem.php?img=<?=$acessado['imagem_album']?>&amp;tipo=2&amp;s=27" alt="Descrição da imagem" width="60" /></a></div>
            <?php else: ?>
			<div class="capa"><span class="<?=Util::getIconeConteudo($acessado['cod_formato']);?> no-image"><a href="/<?=Util::getFormatoConteudoBusca($acessado['cod_formato']);?>.php" title="Ir para página do conteudo">Textos</a></span></div>
			<?php endif; ?>
			<?php if ($acessado['cod_segmento']): ?>
			  <div class="tag-canal"><?=Util::getHtmlCanal($acessado['cod_segmento']);?></div>
            <?php endif; ?>
			<strong><a href="/<?=$acessado['url'];?>" title="Ir para página deste conteúdo"><?=Util::cortaTexto($acessado['titulo'], 60);?></a></strong><br />
			<?=Util::getHtmlListaAutores($acessado['cod_conteudo']);?>
            <div class="hr"><hr /></div>
        </li>
<?php
	if ($temcount == $colspan):
		$temcount -= $colspan;
		echo '</ul>';
		$temul = true;
		$cont++;
	endif;
	$temcount++;
endforeach;
if (!$temul)
	echo '</ul>';
?>
        <div class="todos"><a href="/busca_action.php?buscar=1&amp;formatos=2,3,4,5&amp;relacionado=<?=$conteudo['conteudo']['cod_conteudo']?>" title="Listar conteúdos relacionados"><strong>Ver todos</strong></a></div>
    </div>
<?php endif; ?>

    <div id="comentarios" class="principal">
		<div id="carrega_comentarios"><?php include('comentarios_carregar.php');?></div>
    </div>
    <div id="comentar" class="principal">
        <form action="#comentar" id="formcomentario" name="formcomentario" method="post" onsubmit="return false;">
			<fieldset>
				<legend>Deixe um comentário</legend>
				<div id="resposta_comentario"><?php include('comentarios_enviar.php');?></div>
				<input type="hidden" value="<?=$conteudo['cod_conteudo']?>" name="cod_conteudo" id="cod1" />
                <input type="hidden" value="enviar" name="acao" id="acao" />
				<label for="comentario">Comentário:</label><br />
				<textarea id="comentario" name="comentario" cols="30" rows="5"><?php echo $campo = (isset($_POST['site'])) ? ($_POST['comentario']) : (''); ?></textarea><br />
				<label for="seu-nome">Seu nome:</label><br />
				<input type="text" id="seu-nome" name="nome" class="txt" <?php echo $campo = (isset($_POST['site'])) ? ("value=\"{$_POST['nome']}\"") : (''); ?>/><br />
				<label for="seu-email">Seu e-mail (não será publicado):</label><br />
				<input type="text" id="seu-email" name="email" class="txt" <?php echo $campo = (isset($_POST['site'])) ? ("value=\"{$_POST['email']}\"") : (''); ?>/><br />
				<label for="seu-site">Site / Url (opcional):</label><br />
				<input type="text" id="seu-site" name="site" class="txt" <?php echo $campo = (isset($_POST['site'])) ? ("value=\"{$_POST['site']}\"") : (''); ?>/><br />
                <input class="btn" type="image" onclick="javascript:enviarComentario(this);" src="/img/botoes/bt_enviar.gif" />
			</fieldset>
        </form>
    </div>
</div>
<?php
include ('includes/rodape.php');
