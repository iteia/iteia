<?php
include_once('classes/bo/UsuarioContatoBO.php');
include_once('classes/bo/UsuarioBO.php');
$contatobo = new UsuarioContatoBO;
$usuariobo = new UsuarioBO;

$codusuario = (int)$_GET['cod'];
$dados = $contatobo->getDadosUsuario($codusuario);

$cidade_param = $cidade = $estado_param = $estado ='';

if($dados['cod_pais'] == '2'){
	$cidade_param = "cidades=".$dados['cod_cidade'];
	$cidade = $dados['cidade'];
	$estado_param = "estados=".$dados['cod_estado'];
	$estado = $dados['sigla'];
}else{
	$cidade = $dados[12];
	$cidade_param = "palavra=".$cidade;
	$estado = $usuariobo->getPais($dados['cod_pais']);
	$estado_param = 'paises='.$dados['cod_pais'];
}

switch($dados['cod_tipo']) {
	case 1:
		$bread = '<a href="/colaboradores.php">Colaboradores</a> <span class="marcador">»</span> <a href="/'.$dados['url'].'">'.$dados['nome'].'</a>';
		//$mensagem = 'Esta é a página de contato do(a) colaborador(a) <a href="/'.$dados['url'].'">'.$dados['nome'].'</a> de <a href="/busca_resultado.php?colaboradores=1&amp;'.$cidade_param.'" title="Listar autores por cidade">'.$cidade.'</a> - <a href="/busca_resultado.php?colaboradores=1&amp;'.$estado_param.'" title="Listar autores por estado">'.$estado.'</a>';
		$mensagem = 'Esta é a página de contato do(a) colaborador(a) <a href="/'.$dados['url'].'">'.$dados['nome'].'</a> de <a href="/busca_action.php?buscar=1&amp;formatos=10&amp;'.$cidade_param.'" title="Listar colaboradoes por cidade">'.$cidade.'</a> - <a href="/busca_action.php?buscar=1&amp;formatos=10&amp;'.$estado_param.'" title="Listar colaboradores por estado">'.$estado.'</a>';
		break;
	case 2:
		$bread = '<a href="/autores.php">Autores</a> <span class="marcador">»</span> <a href="/'.$dados['url'].'">'.$dados['nome'].'</a>';
		//$mensagem = 'Esta é a página de contato do(a) autor(a) <a href="/'.$dados['url'].'">'.$dados['nome'].'</a> de <a href="/busca_resultado.php?autores=1&amp;'.$cidade_param.'" title="Listar autores por cidade">'.$cidade.'</a> - <a href="/busca_resultado.php?autores=1&amp;'.$estado_param.'" title="Listar autores por estado">'.$estado.'</a>';
		$mensagem = 'Esta é a página de contato do(a) autor(a) <a href="/'.$dados['url'].'">'.$dados['nome'].'</a> de <a href="/busca_action.php?buscar=1&amp;formatos=9&amp;'.$cidade_param.'" title="Listar autores por cidade">'.$cidade.'</a> - <a href="/busca_action.php?buscar=1&amp;formatos=9&amp;'.$estado_param.'" title="Listar autores por estado">'.$estado.'</a>';
		break;
}

$topo_class = 'cat-contato iteia';
$titulopagina = 'Contato';
$js_usuariocontato = 1;
include ('includes/topo.php');
?>



    <div id="migalhas"><span class="localizador">Você está em:</span> <a href="/index.php" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <?=$bread?> <span class="atual">Contato</span></div>
    <div id="conteudo">
      
      <div class="principal">
      <h2 class="midia">Contato</h2>
        <p class="caption"><?=$mensagem;?></p>
       
      <div id="comentar">
        <form action="javascript:;" id="form-contato" name="formcontato">
            <div id="resposta_contato"></div>
				<fieldset>
				<input type="hidden" value="<?=$codusuario?>" name="codusuario" />
                <label for="mensagem">Mensagem:</label><br />
                <textarea id="mensagem" name="mensagem" cols="30" rows="5"></textarea><br />
                <label for="seu-nome">Seu nome:</label><br />
                <input type="text" id="seu-nome" name="nome" class="txt" /><br />
                <label for="seu-email">Seu e-mail:</label><br />
                <input type="text" id="seu-email" name="email" class="txt" /><br />
                <input class="btn" id="enviarcontato-btn" type="image" onclick="javascript:enviarContato();" src="/img/botoes/bt_enviar.gif" />
            </fieldset>
          </form>
      </div>
      </div>
      <div class="lateral">
        <?php include('includes/banners_lateral.php');?>
      </div>
	</div>
<?php
include ('includes/rodape.php');
