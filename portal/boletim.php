<?php
include_once('classes/bo/NewsletterBO.php');
$newsbo = new NewsletterBO;

$editar = (int)$_POST['editar'];
//$codaudio = (int)$_GET["cod"];
	
if ($editar) {
	try {
		$form_sucesso = $newsbo->indicar($_POST);
		$form_sucesso = true;
		//Header("Location: conteudo_publicado_audio.php?cod=".$cod_conteudo);
		//exit();
	} catch (Exception $e) {
		//$erro_mensagens = $e->getMessage();
        $form_erro = true;
	}
}

$topo_class = 'boletim';
$titulopagina = 'boletim';
$ativa = '';
$js_sem_jquery = true;
include ('includes/topo.php');
?>
<div id="migalhas"><span class="localizador">Você está em:</span> <a href="/" title="Voltar para a página inicial" id="inicio">Início</a> <span class="marcador">&raquo;</span> <span class="atual">Boletim</span></div>
    <div id="conteudo">
      <div class="principal">
        <h2 class="midia">Boletim iTEIA</h2>
        <div class="texto-box">
        <h1 class="midia">Cadastrar e-mail</h1>
        <div>Por favor, adicione o email <strong>no-reply@iteia.org.br</strong> como um contato no seu gerenciador de e-mails se não estiver recebendo mensagens do iteia.</div>
        <?php if($form_sucesso): ?>
        <div class="aviso sucesso">Seu email foi cadastrado com sucesso.</div>
        <?php endif; ?>
        <?php if($outros): ?>
              <div class="aviso erro">Não foi possível cadastrar, por favor tente dentro de instantes.</div>
        <?php endif; ?>
        <?php if($form_erro): ?>
              <div class="aviso alerta">Adicione um <strong>e-mail</strong> válido</div>
        <?php endif; ?>
              <br />
          <form method="post" action="boletim.php" id="boletim">
            <input type="hidden" name="editar" value="1" />
            <fieldset>
              <label for="mail">Cadastre seu email e receba as novidades do portal iteia</label><br />
              <input type="text" id="mail" name="mail" class="txt" value='<?=$newsbo->getValorCampo('mail');?>' />
              <input class="btn" type="image" src="img/botoes/bt_enviar.gif">
            </fieldset>
          </form>
        </div>
        
        
      </div>
		<div class="lateral">
		<?php
            //Banners
            include('includes/banners_lateral.php');
            //Banners END
        ?>
        </div>
    </div>
<?php
include ('includes/rodape.php');
