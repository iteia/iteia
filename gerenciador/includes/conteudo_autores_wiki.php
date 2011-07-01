<?php
include_once('classes/bo/TextoEdicaoBO.php');
include_once(ConfigGerenciadorVO::getDirClassesRaiz().'util/Util.php');
$contbo = new TextoEdicaoBO;
?>
<!--<script type="text/javascript" src="jscripts/autocompletar.js"></script>-->
<div style="display:none;">
<div class="box" id="ficha-tecnica">
    <fieldset>
        <input type="hidden" id="autor_selecionado" value="" />
        <input type="hidden" id="autor_selecionado_atualizar" value="" />
        <legend class="">Cadastro de Ficha t&eacute;cnica</legend>
        <p>O preenchimento da ficha t&eacute;cnica &eacute; obrigat&oacute;rio caso voc&ecirc; n&atilde;o seja o autor desta obra</p>
        <div>
            <p>Digite abaixo o nome da pessoa que voc&ecirc; deseja incluir como um dos autores desta   obra. Caso ele ainda n&atilde;o seja cadastrado, ser&aacute; necess&aacute;rio preencher todo o   formul&aacute;rio para adicion&aacute;-lo.</p>
            <div class="campos">
                <label for="textfield18">Nome art&iacute;stico* </label>
                <br />
                <input type="text" name="nome_autor_wiki" id="nome_autor_wiki" class="txt" size="60" />
            </div>
            <div class="campos">
                <label for="ficha_atividade">Atividade exercida*</label>
                <br />
                <select name="select" id="ficha_atividade">
                <option value="0">Selecione</option>
                <?php
                $list = $contbo->getListaAtividades();
                array_shift($list);
                foreach ($list as $key => $value): ?>
                <option value="<?=$value['cod'];?>"><?=htmlentities($value['atividade']);?></option>
                <?php endforeach; ?>
                </select>
            </div>
            
            <div class="campos">
                <label for="ficha_nome_completo">Nome completo</label>
                <br />
                <input type="text" class="txt" id="ficha_nome_completo" size="60" />
            </div>
            
            <div class="campos"><br />
                <input type="checkbox" id="ficha_falecido" name="ficha_falecido" value="1" class="checkbox" />
                <label for="checkbox10">Falecido</label>
            </div>
            
            <div class="both">
                <div class="campos">
                    <label for="select">Pa&iacute;s*</label>
                    <br />
                    <select name="codpais_autor_wiki" onchange="javascript:exibeEstadoCidade2();" id="pais">
                    <?php foreach ($contbo->getListaPaises() as $key => $value): ?>
                    <option value="<?=$value['cod_pais'];?>"<?=($value['cod_pais'] == 2)?' selected="selected"':''?>><?=htmlentities($value['pais']);?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="campos" id="mostraestado" style="display:inline;">
                    <label for="estado">Estado*</label>
                    <br />
                    <select name="codestado_autor_wiki" id="estado" onchange="obterCidades(this, <?=(int)$codcidade?>)">
                    <?php foreach ($contbo->getListaEstados() as $key => $value): ?>
                    <option value="<?=$value['cod_estado'];?>" <?=Util::iif($value['cod_estado'] == 17, 'selected="selected"');?>><?=htmlentities($value['estado']);?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="campos" id="selectcidade2">
                    <label for="selectcidade">Cidade*</label>
                    <br />
                    <select name="codcidade_autor_wiki" id="selectcidade">
                    </select>
                </div>
                
                <div class="campos" id="cidade" style="display:none;">
                    <label for="select6">Cidade*</label>
                    <br /><input type="text" class="txt" name="cidade" id="campocidade" size="45" value="" maxlength="100" />
                </div>
            </div>
        
            <div class="both">
                <div class="campos">
                    <label for="ficha_email">Email</label>
                    <br />
                    <input type="text" id="ficha_email" class="txt mail" />
                </div>
                <div class="campos">
                    <label for="ficha_telefone">Telefone</label>
                    <br />
                    <input type="text" id="ficha_telefone" class="txt phone" />
                </div>
            </div>
        
            <div class="both">
                <label for="textfield3">Biografia*</label>
                <br />
                <textarea cols="60" name="ficha_descricao" id="ficha_descricao" rows="7" onkeyup="contarCaracteres(this, 'count_ficha_descricao', 250);"></textarea>
                <input type="text" disabled="disabled" class="txt counter" id="count_ficha_descricao" value="250" size="4"  /><br />
                <strong><a href="javascript:void(0);" onclick="javascript:adicionarAutorFicha();" class="add bt">Adicionar</a></strong>
            </div>
            
        </div>
    </fieldset>
</div>
</div>
<script type="text/javascript">
var nao_adiciona_wiki = false;
obterCidades(document.getElementById('estado'), 6330);
<?php
	if ($_SESSION['logado_dados']['nivel'] == 2) {
?>
nao_adiciona_wiki = true;
<?php
	}
	//if (count($_SESSION['sess_conteudo_autores_ficha'])) {
?>
//exibeListaAutoresFicha();
<?php
	//}
?>
</script>
