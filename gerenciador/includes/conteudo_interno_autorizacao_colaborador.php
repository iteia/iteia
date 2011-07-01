<?php if(sizeof($_SESSION['colab_lista']) > 1):?>
	  <div class="box">
        <fieldset>
        <legend>Autoriza&ccedil;&atilde;o</legend>
        <p>Para ser publicado, esse conte&uacute;do ainda precisa d&aacute; autoriza&ccedil;&atilde;o de um  dos colaboradores que fazem parte do portal iTEIA: </p>
        <label for="contas">Escolha entre os colaboradores que voc&ecirc; representa</label>
        <br />
        <select name="colaborador_aprov" id="contas" <?=$contbo->verificaErroCampo("colaborador_aprov")?>>
          <option value="0">Selecione</option>
		<?php
		foreach($_SESSION['colab_lista'] as $colab):
		?>
		  <option <?=Util::iif($contbo->getValorCampo("colaborador_aprov") == $colab['cod_usuario'], 'selected="selected"')?>value="<?=$colab['cod_usuario']?>" ><?=$colab['nome']?></option>
		<?php
		endforeach;
		?>
        </select>
        </fieldset>
      </div>
<?php elseif(sizeof($_SESSION['colab_lista']) == 1):
        foreach($_SESSION['colab_lista'] as $id=>$dados):?>
    <input type="hidden" name="colaborador_aprov" value="<?=$id?>" />
<?php   endforeach;
      endif;?>