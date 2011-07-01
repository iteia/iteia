<?php
//echo "teste";
$titulo = htmlentities($conteudo['conteudo']['titulo']);
$pagina = ($_GET['pagina'] ? (int)$_GET['pagina'] : 1);
$mostrar = 2;
$inicial = ($pagina - 1) * $mostrar;

$noticias_datas = $this->getGroupNoticias($inicial, $mostrar, $conteudo['conteudo']['cod_conteudo']);
$paginacao = Util::paginacao($pagina, $mostrar, $noticias_datas['total'], 'noticias.php?a=1');

$topo_exibir_csspapel = true;
$topo_exibir_csstamanho = true;
$topo_menu_noticias = true;

$topo_class = 'cat-noticias';
$titulopagina = $conteudo['conteudo']['titulo'].' | Jornal iTEIA';
$ativa = 6;
$jsthickbox = true;
$js_bookmarks = 0;
include('topo.php');

?>
<script type="text/javascript" src="/js/conteudo.js"></script>

      <div id="migalhas"><span class="localizador">Você está em:</span> <a href="/index.php" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <a href="/jornal">Jornal iTEIA</a> <span class="marcador">&raquo;</span> <span class="atual"><?=$conteudo['conteudo']['titulo']?></span></div>
      <div class="principal">
       <h2 class="midia">Jornal <span class="azul">i</span>T<span class="verde">E</span><span class="amarelo">I</span><span class="preto">A</span></h2>
      <small><?=date('d.m.Y - H\\hi', strtotime($conteudo['conteudo']['datahora']));?></small>
        <h1 class="midia"><?=$conteudo['conteudo']['titulo'];?></h1>
        <p class="subtitulo"><?=$conteudo['noticia']['subtitulo'];?></p>
        <p class="assinatura"><?=$conteudo['noticia']['assinatura'];?></p>
        <div class="texto-box">
<?php if ($conteudo['conteudo']['imagem']): ?>
          <div class="box-imagem" style="width:200px;">
        <a href="/exibir_imagem.php?img=<?=$conteudo['conteudo']['imagem']?>&amp;tipo=5&amp;s=11" class="thickbox ampliar-imagem">ampliar</a> <small class="credito"><?=$conteudo['noticia']['foto_credito'];?></small><br/>
        <a class="thickbox" href="/exibir_imagem.php?img=<?=$conteudo['conteudo']['imagem']?>&amp;tipo=5&amp;s=11"><img src="/exibir_imagem.php?img=<?=$conteudo['conteudo']['imagem']?>&amp;tipo=5&amp;s=8" /></a><small><?=$conteudo['noticia']['foto_legenda'];?></small> </div>
<?php endif; ?>
          <p><?=stripslashes(nl2br(Util::autoLink($conteudo['conteudo']['descricao'])))?></p>
        </div>
        
        <div id="texto-ficha"><strong>Publicado por:</strong>
            
            <?php if($conteudo['conteudo']['colaborador']): ?>
                <a href="/<?=$conteudo['conteudo']['url_colaborador'];?>" title="Ir para página deste colaborador"><?=strip_tags($conteudo['conteudo']['colaborador']);?></a> em <?=date('d.m.Y', strtotime($conteudo['conteudo']['data_cadastro'])).' &agrave;s '.date('H\\hs', strtotime($conteudo['conteudo']['data_cadastro']))?><br />
            <?php else: ?>
                <a href="/<?=$conteudo['autor']['url'];?>" title="Ir para página deste colaborador"><?=strip_tags($conteudo['autor']['nome']);?></a> em <?=date('d.m.Y', strtotime($conteudo['conteudo']['data_cadastro'])).' &agrave;s '.date('H\\hs', strtotime($conteudo['conteudo']['data_cadastro']))?><br />
            <?php endif; ?>

          <strong>Tags:</strong> <?=substr(str_replace('</a>', '</a>, ', $conteudo['tags']), 0, -2);?><br />
          <strong>Canais:</strong> <?=$conteudo['canal'];?></div>
        <div id="controles">
          <ul id="opcoes">
            <li id="imprimir"><a href="javascript:window.print();">Imprimir</a></li>
            <li id="baixe"><a href="/pdf.php?cod=<?=$conteudo['conteudo']['cod_conteudo'];?>&amp;baixar=noticia">Baixe em PDF</a></li>
            <li id="comente"><a href="#comentar">Comente</a> (<?=$conteudo['comentarios'];?>)</li>
			<?php //<li id="compartilhe"><a href="#bookmark">Compartilhe</a></li>?>
            <li id="denuncie" class="no-border"><a href="/denuncie.php?conteudo=<?=$conteudo['conteudo']['cod_conteudo'];?>">Denuncie</a></li>
          </ul>
          <?php include('includes/bookmarks.php'); ?>
        </div>
<?php
$noticiadao = new ConteudoDAO;
$relacionados=$noticiadao->getConteudoRelacionadoConteudo($conteudo['conteudo']['cod_conteudo']);
if (count($relacionados)):
?>
	<div id="relacionados" class="principal">
	<h3 class="mais"><span>Lista dos</span> Conteúdos relacionados</h3>
<?php
$temcount = 1;
$colspan = 3;
$cont = 0;
foreach ($relacionados as $key => $acessado):
 $url = '/'.$acessado['url'];
   if ($acessado['cod_formato'] == 6)
	$url = '/evento.php?cod='.$acessado['cod_conteudo'];
	$temul = false;
	if ($temcount == 1)
		echo '<ul class="coluna'.($cont == 1 ? ' no-margin-r' : '').'">';
?>
		<li<?=((!isset($autor['autor']['mais_acessados'][$key + 1]) || $temcount == $colspan ) ? ' class="no-border"' : '')?>>
			<?php if ($acessado['imagem']): ?>
             <div class="capa"><span class="<?=Util::getIconeConteudo($acessado['cod_formato']);?>"><a href="/<?=Util::getFormatoConteudoBusca($acessado['cod_formato']);?>.php" title="Ir para página do conteudo">Textos</a></span> <a href="<?=$url;?>" title="Ir para página deste conteúdo"><img src="/exibir_imagem.php?img=<?=$acessado['imagem']?>&amp;tipo=<?=$acessado['cod_formato']?>&amp;s=27" alt="Descrição da imagem" width="60" /></a></div>
            <?php elseif ($acessado['imagem_album']): ?>
             <div class="capa"><span class="<?=Util::getIconeConteudo($acessado['cod_formato']);?>"><a href="/<?=Util::getFormatoConteudoBusca($acessado['cod_formato']);?>.php" title="Ir para página do conteudo">Textos</a></span> <a href="<?=$url;?>" title="Ir para página deste conteúdo"><img src="/exibir_imagem.php?img=<?=$acessado['imagem_album']?>&amp;tipo=2&amp;s=27" alt="Descrição da imagem" width="60" /></a></div>
            <?php else: ?>
			<div class="capa"><span class="<?=Util::getIconeConteudo($acessado['cod_formato']);?> no-image"><a href="/<?=Util::getFormatoConteudoBusca($acessado['cod_formato']);?>.php" title="Ir para página do conteudo">Textos</a></span></div>
			<?php endif; ?>
			<?php if ($acessado['cod_segmento']): ?>
			  <div class="tag-canal"><?=Util::getHtmlCanal($acessado['cod_segmento']);?></div>
            <?php endif; ?>
            <strong><a href="<?=$url;?>" title="Ir para página deste conteúdo"><?=Util::cortaTexto($acessado['titulo'], 60);?></a></strong><br />
			<?php if ($acessado['cod_formato'] < 5): ?>
			<?=Util::getHtmlListaAutores($acessado['cod_conteudo']);?>
			<?php endif; ?>
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
				<input type="hidden" value="<?=$conteudo['conteudo']['cod_conteudo']?>" name="cod_conteudo" id="cod1" />
                <input type="hidden" value="enviar" name="acao" id="acao" />
				<label for="comentario">Comentário:</label><br />
				<textarea id="comentario" name="comentario" cols="30" rows="5"><?=$_POST['comentario']?></textarea><br />
				<label for="seu-nome">Seu nome:</label><br />
				<input type="text" id="seu-nome" name="nome" class="txt" value="<?=$_POST['nome']?>" /><br />
				<label for="seu-email">Seu e-mail (não será publicado):</label><br />
				<input type="text" id="seu-email" name="email" class="txt" value="<?=$_POST['email']?>" /><br />
				<label for="seu-site">Site / Url (opcional):</label><br />
				<input type="text" id="seu-site" name="site" class="txt" value="<?=$_POST['site']?>" /><br />
				<input class="btn" type="image" onclick="javascript:enviarComentario(this);" src="/img/botoes/bt_enviar.gif" />
			</fieldset>
        </form>
      </div>
      </div>
      <div class="lateral">
<?php include ('includes/noticias_lateral.php'); ?>
<?php include('includes/banners_lateral.php');?>
</div>
<?php
include ('includes/rodape.php');