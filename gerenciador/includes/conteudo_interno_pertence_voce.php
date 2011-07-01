      <div class="box">
        <fieldset>
        <legend>Esse conte&uacute;do pertence a voc&ecirc;?<!--<span>*</span>--></legend>
          <p>Caso afirmativo, qual a atividade exercida por voc&ecirc; na realiza&ccedil;&atilde;o deste conte&uacute;do? <br />Caso negativo, ou precise adicionar mais pessoas preencha o <strong><a class="thickbox" href="includes/conteudo_autores_wiki.php#TB_inline?height=400&width=630&inlineId=ficha-tecnica">cadastro de ficha t&eacute;cnica</a>.</strong></p>
                <label for="ficha_atividade_pessoal">Escolha sua atividade neste conte&uacute;do e clique em confirmar</label><br />

            	<select name="select" id="ficha_atividade_pessoal">
            	<option value="0">Selecione</option>
                
              <?php
                $lista_atividades = $contbo->getListaAtividades();
                array_shift($lista_atividades);
                foreach ($lista_atividades as $key => $value): ?>
            		<option value="<?=$value['cod'];?>"><?=$value['atividade'];?></option>
              <?php endforeach; ?>
            </select>
         <strong><a href="javascript:void(0);" onclick="javascript:adicionarAutorFichaPessoal();" class="add bt">Confirmar</a></strong>
        </fieldset>
      </div>
