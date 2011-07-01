	<div id="resultado" class="box">
		<div class="view">Exibindo
			<select name="mostrar" onchange="submeteBuscaCadastro('pag_cadastro');" id="select3">
				<option value="10"<?=Util::iif($mostrar == 10, ' selected="selected"');?>>10</option>
				<option value="20"<?=Util::iif($mostrar == 20, ' selected="selected"');?>>20</option>
				<option value="30"<?=Util::iif($mostrar == 30, ' selected="selected"');?>>30</option>
			</select> de <strong><?=$paginacao['num_total'];?></strong>
		</div>
		<div class="nav">P&aacute;ginas: <?=Util::iif($paginacao['anterior']['num'], "<a href=\"".$paginacao['anterior']['url']."\">&laquo; Anterior</a>");?> <?=$paginacao['page_string'];?> <?=Util::iif($paginacao['proxima']['num'], "<a href=\"".$paginacao['proxima']['url']."\">Pr&oacute;xima &raquo;</a>");?></div>

		<table width="100%" border="1" cellspacing="0" cellpadding="0" id="table-cadastro">
			<thead>
				<tr>
					<th class="col-1" scope="col"><input name="checkbox" type="checkbox" id="check-all" /></th>
					<th class="col-titulo" scope="col">Nome</th>
					<th class="col-tipo" scope="col">Tipo</th>
					<th class="col-uf" scope="col">Estado</th>
					<th class="col-situacao" scope="col">Situa&ccedil;&atilde;o</th>
					<th class="col-tipo" scope="col">Desde</th>
					<th class="col-editar" scope="col">Editar</th>
			  </tr>
			</thead>
			<tbody>
			<?php
            //print_r($cadastros);
			foreach ($cadastros as $key => $value):
				if (intval($value['cod'])):
			?>
				<tr>
					<td class="col-1">
						<input name="codusuario[]" type="checkbox" class="check" value="<?=$value['cod'];?>" />
						<input name="tipoautor_<?=$value['cod'];?>" id="tipoautor_<?=$value['cod'];?>" type="hidden" value="<?=Util::iif($value['autor_wiki'], '5', $value['tipo_autor']);?>" />
					</td>
					<td class="col-titulo"><a href="<?=$value['url'];?>" title="Clique para visualizar"><?=$value['nome'];?></a></td>
					<td class="col-tipo"><?=Util::iif($value['autor_wiki'], 'Wiki', $value['tipo']);?></td>
					<td class="col-uf"><?=$value['estado'];?></td>
					<td class="col-situacao"><?=$value['situacao'];?></td>
					<td class="col-tipo"><?=$value['data_cadastro'];?></td>
					<td class="col-editar">
					<?php if ($_SESSION['logado_dados']['nivel'] > 6 || $value['autor_wiki']): ?> 
						<a href="<?=$value['url_editar'];?>" title="Editar">Editar</a>
					<?php else: ?>
						&nbsp;
					<?php endif; ?>
					</td>
				</tr>
			<?php
				endif;
			endforeach;
			?>
			</tbody>
			<tfoot>
						<?php if ($_SESSION['logado_dados']['nivel'] > 6): ?>
				<tr>
					<td colspan="7" class="selecionados">
                        <strong>Selecionados:</strong>
                        <a href="javascript:submeteAcoesCadastro('1');">Apagar</a> | 
                        <a href="javascript:submeteAcoesCadastro('2');">Ativar</a> | <a href="javascript:submeteAcoesCadastro('3');">Desativar</a>
						| <a href="javascript:submeteUnificacao();">Unificar</a>
						| <a href="javascript:enviaLembrete();">Lembrete de senha</a>
					</td>
				</tr>
						<?php endif; ?>
			</tfoot>
		</table>
		<div class="nav">P&aacute;ginas: <?=Util::iif($paginacao['anterior']['num'], "<a href=\"".$paginacao['anterior']['url']."\">&laquo; Anterior</a>");?> <?=$paginacao['page_string'];?> <?=Util::iif($paginacao['proxima']['num'], "<a href=\"".$paginacao['proxima']['url']."\">Pr&oacute;xima &raquo;</a>");?></div>
        <hr class="both" />
    </div> 
