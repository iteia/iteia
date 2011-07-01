<strong>Situa&ccedil;&atilde;o:</strong>
<div class="box box-<?php if ((int)$contbo->getValorCampo('publicado') == 0) echo 'alerta'; elseif ((int)$contbo->getValorCampo('publicado') == 2) echo 'erro'; else echo 'dica';?>">
	<h3><?=Util::iif(!(int)$contbo->getValorCampo('publicado'), 'Aguardando aprovação', Util::iif((int)$contbo->getValorCampo('publicado') == 1, 'Conte&uacute;do publicado.', 'Este conteúdo não foi aprovado.'));?></h3>
<?php
if ($contbo->getValorCampo('publicado') == 0):
	if ($lista_colaborador_aprovacao):
?>
	<p>Esta obra foi enviada para aprovação dos <strong>colaboradores</strong></p>
<?php
	else:
?>
	<p>Esta obra foi enviada para <strong>lista pública de aprovação</strong></p>
<?php
	endif;
elseif ((int)$contbo->getValorCampo('publicado') == 1):
?>
	<p>Essa obra foi aprovada por <strong><?=$nome_colaborador_aprovacao;?></strong> <a href="<?=ConfigVO::URL_SITE.$contbo->getValorCampo('url');?>" target="_blank" class="ext" title="Este link ser&aacute; aberto numa nova janela">(Veja aqui)</a><br /></p>
<?php
endif;
?>
</div>