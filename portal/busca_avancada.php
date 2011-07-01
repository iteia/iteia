<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once('classes/bo/BuscaBO.php');
include_once(ConfigPortalVO::getDirClassesRaiz().'util/Util.php');
$js_busca = true;
$topo_class = 'iteia avancada';
$titulopagina = 'Busca avan�ada';
include ('includes/topo.php');
$buscabo = new BuscaBO();
?>
<script type="text/javascript" src="/js/ajax.js"></script>
<script type="text/javascript" src="/js/edicao.js"></script>
<script type="text/javascript">
function executaBusca() {
	var url = 'busca_action.php?buscar=1';
	if ($('#pchave').val()) url += '&palavra=' + $('#pchave').val();
	if ($('#dFrom').val()) url += '&data1=' + $('#dFrom').val();
	if ($('#dTo').val()) url += '&data2=' + $('#dTo').val();
	if ($('#estado').val()) url += '&estados=' + $('#estado').val();
	if ($('#selectcidade').val()) url += '&cidades=' + $('#selectcidade').val();
	canal = ""
	if ($('#channel').val() != 0)
		canal = '&canal=' + $('#channel').val();
	if ($('#subchannel').val() != 0)
		canal = '&canal=' + $('#subchannel').val();
	url += canal;
	
	if ($('#periodo').val()) url += '&periodo=' + $('#periodo').val();
	
	url += '&formatos=';
	var formatos = new Array();
	
	if ($('#audios:checked').val()) formatos.push(2);
	if ($('#videos:checked').val()) formatos.push(3);
	if ($('#textos:checked').val()) formatos.push(4);
	if ($('#imagens:checked').val()) formatos.push(5);
	if ($('#noticias:checked').val()) formatos.push(6);
	if ($('#eventos:checked').val()) formatos.push(7);
	if ($('#canal:checked').val()) formatos.push(8);
	if ($('#autores:checked').val()) formatos.push(9);
	if ($('#colaboradores:checked').val()) formatos.push(10);
	url += formatos.join(',');
	
	if ($('#licenca').val()) url += '&direito=' + $('#licenca').val();
	document.location = url;
}
</script>
    <div id="migalhas"><span class="localizador">Voc� est� em:</span> <a href="/" title="Voltar para a p�gina inicial" id="inicio">In�cio</a> <span class="marcador">&raquo;</span> <span class="atual">Busca avan�ada</span></div>
    <div id="conteudo">
		
<!-- Bloco Novo In�cio -->
     <div class="principal">
        <h2 class="midia">Busca avan�ada</h2>
        <p class="caption">A busca pode ser feita com apenas um campo preenchido.<br />
          Para buscar conte�dos contendo mais de uma palavra, digite-as separadas por um espa�o.</p>
		<form action="/busca_action.php" id="form-busca-avancada" onsubmit="return false;">
			<input type="hidden" name="buscar" value="1" />
          <label for="pchave" class="titulo">Palavra-chave:</label>
          <br />
          <input type="text" id="pchave" class="txt" />
          
          <div id="filtro-usuario" class="opcoes-busca">
            <h3 class="sub">Por usu�rio</h3>
			<ul>
			  <li>
				<label>
				<input type="checkbox" name="autores" id="autores" class="chk" value="1" />
				Autores</label>
			  </li>
			  <li>
				<label>
				<input type="checkbox" name="colaboradores" id="colaboradores" class="chk" value="1" />
				Colaboradores</label>
			  </li>
			</ul>
            <br />
          <label for="state">Estado:</label>
		  <br />
          <span style="display: inline;">
          <select name="estado[]" id="estado" onchange="obterCidades(this, this.value)" class="slc">
          <?php
		echo "<option value=\"0\"";
		if (!$codestado)
			echo " selected=\"selected\"";
		echo ">Selecione o Estado</option>\n";
		$lista_estados = $buscabo->getListaEstados();
				foreach ($lista_estados as $estado) {
			echo "<option value=\"".$estado["cod_estado"]."\"";
			if ($estado["cod_estado"] == 0)
				echo " selected=\"selected\"";
			echo ">".$estado["sigla"]."</option>\n";
		}
		?>
          </select>  
          </span>          <br/>
          <label for="city">Cidade:</label><br />
          <select name="cidade[]" id="selectcidade" class="slc">
				<option value="0">Todas</option>
			</select>
			
          </div>
          <div id="filtro-conteudo" class="opcoes-busca">
		  
            <h3 class="sub">Por conte�do</h3>
            <ul>
				<li>
				  <label>
				  <input type="checkbox" name="audios" id="audios" class="chk" value="1" />
				  �udios</label>
				</li>
				<li>
				  <label>
				  <input type="checkbox" name="videos" id="videos" class="chk" value="1" />
				  V�deos</label>
				</li>
				<li>
				  <label>
				  <input type="checkbox" name="textos" id="textos" class="chk" value="1" />
				  Textos</label>
				</li>
				<li>
				  <label>
				  <input type="checkbox" name="imagens" id="imagens" class="chk" value="1" />
				  Imagens</label>
				</li>
				<li>
				  <label>
				  <input type="checkbox" name="noticias" id="noticias" class="chk" value="1" />
				  Not�cias</label>
				</li>
				<li>
				  <label>
				  <input type="checkbox" name="agenda" id="eventos" class="chk" value="1" />
				  Eventos</label>
				</li>
				<li>
				  <label>
				  <input type="checkbox" name="canal" id="canal" class="chk" value="1" />
				  Canais</label>
				</li> 
              <li> 
              <br /> 
             <label for="channel">Canal:</label>
            <br />
            <select id="channel" name="channel[]" onchange="obterSubcanais(this)" class="slc">
			<?php
				echo "<option value=\"0\"";
				if (!$codestado)
					echo " selected=\"selected\"";
				echo ">Qualquer canal</option>\n";
					
				$lista_canais = $buscabo->getListaTodosCanais();
				foreach ($lista_canais as $canal) {
					echo "<option value=\"".$canal["cod_segmento"]."\"";
					if ($canal["cod_segmento"] == 0)
						echo " selected=\"selected\"";
					echo ">".$canal["nome"]."</option>\n";
				}
			?>
            </select>
            </li><li>
            <label for="subchannel">Subcanal:</label>
            <br />
            <select id="subchannel" name="subchannel" class="slc">
              <option value="0">Qualquer subcanal</option>
            </select>
            </li> 
            <li>
            <label for="licenca">Licen�a</label>
            <br />
            <select id="licenca" name="licenca" class="slc">
              <option value="0">Qualquer licen�a</option>
              <option value="1">Para uso comercial</option>
              <option value="2">Para adapta��o e modifica��o</option>
            </select> 
            </li>
            </ul>
          </div>
          <div id="filtro-periodo" class="opcoes-busca">
            <h3 class="sub">Por per�odo</h3>
            <label for="periodo">Cadastros feitos:</label>
            <br />
            <select id="periodo" name="periodo" class="slc">
              <option value="0">Em qualquer data</option>
              <option value="1">Nas �ltimas 24 horas</option>
              <option value="2">Na �ltima semana</option>
              <option value="3">No �ltimo m�s</option>
              <option value="4">No �ltimo ano</option>
            </select>
          </div>
          <input class="btn" type="image" onclick="javascript:executaBusca();" src="/img/botoes/bt_buscar.gif" />
        </form>
      </div>

<!-- Bloco Novo fim -->




<!-- Bloco anterior In�cio
      <div class="principal">
        <h2 class="midia">Busca avan�ada</h2>
        <p class="caption">A busca pode ser feita com apenas um campo preenchido.<br />
          Para buscar textos contendo mais de uma palavra, digite-as separadas por um espa�o.</p>
		<form action="/busca_action.php" id="form-busca-avancada" onsubmit="return false;">
		<input type="hidden" name="buscar" value="1" />
          <label for="pchave" class="titulo">Palavra-chave:</label>
          <br />
          <input type="text" id="pchave" name="palavra" class="txt" />
          <fieldset id="periodo">
          <legend>Por Per�odo</legend>
		   <p><small>A busca pode ser feita em per�odos de at� 12 meses</small></p>
          <label for="dFrom" class="none">Data inicial</label>
          <br />
          <input type="text" name="de" id="dFrom" class="txt date small"/><small> ex. 12/05/2010</small>
          <br />
          <label for="dTo" class="none">Data final</label>
          <br />
          <input type="text" name="ate" id="dTo" class="txt date small"/><small> ex. 12/05/2010</small>
         
          </fieldset><fieldset id="local">
          <legend>Por local</legend>
          <label for="state">Estado:</label><br />
          <span style="display: inline;">
          <select name="estado[]" id="estado" onchange="obterCidades(this, this.value)" class="slc">
          <?php
		echo "<option value=\"0\"";
		if (!$codestado)
			echo " selected=\"selected\"";
		echo ">Selecione o Estado</option>\n";
		$lista_estados = $buscabo->getListaEstados();
				foreach ($lista_estados as $estado) {
			echo "<option value=\"".$estado["cod_estado"]."\"";
			if ($estado["cod_estado"] == 0)
				echo " selected=\"selected\"";
			echo ">".$estado["sigla"]."</option>\n";
		}
		?>
          </select>  
          </span>          <br/>
          <label for="city">Cidade:</label><br />
          <select name="cidade[]" id="selectcidade" class="slc">
				<option value="0">Todas</option>
			</select>
			
          </fieldset>
          <fieldset id="tipo">
          <legend>Por tipo de conte�do</legend>
          <ul>
            <li>
              <label>
              <input type="checkbox" name="audios" id="audios" class="chk" value="1" />
              �udios</label>
            </li>
            <li>
              <label>
              <input type="checkbox" name="videos" id="videos" class="chk" value="1" />
              V�deos</label>
            </li>
            <li>
              <label>
              <input type="checkbox" name="textos" id="textos" class="chk" value="1" />
              Textos</label>
            </li>
            <li>
              <label>
              <input type="checkbox" name="imagens" id="imagens" class="chk" value="1" />
              Imagens</label>
            </li>
            <li>
              <label>
              <input type="checkbox" name="noticias" id="noticias" class="chk" value="1" />
              Not�cias</label>
            </li>
            <li>
              <label>
              <input type="checkbox" name="agenda" id="eventos" class="chk" value="1" />
              Eventos</label>
            </li>
          </ul>
          </fieldset>
               <fieldset id="licenca">
          <legend>Por licen�a</legend>
          <ul>
            <li>
              <label>
              <input type="radio" name="licenca" class="radio" value="0" />
              Todas</label>
            </li>
            <li>
              <label>
              <input type="radio" class="radio" name="licenca" value="1" />
              Para uso comercial</label>
            </li>
            <li>
              <label>
              <input type="radio" class="radio" name="licenca" value="2" />
              Para adapta��o e modifica��o</label>
            </li>
          </ul>
          </fieldset>
          <fieldset id="usuarios">
          <legend>Por tipo de usu�rio</legend>
          <ul>
            <li>
              <label>
              <input type="checkbox" name="autores" id="autores" class="chk" value="1" />
              Autores</label>
            </li>
            <li>
              <label>
              <input type="checkbox" name="colaboradores" id="colaboradores" class="chk" value="1" />
              Colaboradores</label>
            </li>
          </ul>
          </fieldset>
          <br />
          <input class="btn" type="image" onclick="javascript:executaBusca();" src="/img/botoes/bt_buscar.gif" />
        </form>
      </div>
Bloco anterior Fim -->
    </div>
      <div class="lateral">
        <?php include('includes/banners_lateral.php');?>
      </div>
<?php
include ('includes/rodape.php');
