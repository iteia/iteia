<?php
include_once('classes/bo/NewsletterBO.php');
$newsbo = new NewsletterBO;

$mail = $_GET['mail'];
$editar = (int)$_POST['editar'];
//$codaudio = (int)$_GET["cod"];

if ($editar) {
	try {
		$form_sucesso = $newsbo->remover($_POST);
		$form_sucesso = true;
		//Header("Location: conteudo_publicado_audio.php?cod=".$cod_conteudo);
		//exit();
	} catch (Exception $e) {
		//$erro_mensagens = $e->getMessage();
        if($newsbo->verificaErro('invalido'))
            $form_erro = true;
        if($newsbo->verificaErro('naoexiste'))
            $form_naoexiste = true;
        if($newsbo->verificaErro('motivo'))
            $form_motivo = true;
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
        <h1 class="midia">Descadastrar e-mail</h1>
        <?php if($form_sucesso): ?>
        <div class="aviso sucesso">Seu email foi descadastrado com sucesso.</div>
        <?php endif; ?>
        <?php if($outros): ?>
              <div class="aviso erro">Não foi possível descadastrar, por favor tente dentro de instantes.</div>
        <?php endif; ?>
        <?php if($form_erro): ?>
              <div class="aviso alerta">Adicione um <strong>e-mail</strong> válido</div>
        <?php endif; ?>
        <?php if($form_naoexiste): ?>
              <div class="aviso alerta">O <strong>e-mail</strong> informado não está cadastrado</div>
        <?php endif; ?>
        <?php if($form_motivo): ?>
              <div class="aviso alerta">Selecione pelo menos um <strong>motivo</strong></div>
        <?php endif; ?>
              <br />
          <form method="post" action="boletim_descadastrar.php" id="boletim">
            <input type="hidden" name="editar" value="1" />
            <fieldset>
              <label for="mail">Digite seu email</label><br />
              <input type="text" id="mail" name="mail" class="txt" value='<?=$mail;?><?=$newsbo->getValorCampo('mail');?>' /><br />
              <strong>Por que você não gostaria de receber os nosso boletins?</strong><br />
              <?php $a = $newsbo->getValorCampoArray('motivo');?>
              <label><input type="checkbox" name="motivo[]" value="1" <?=(in_array(1,$a) ? 'checked="checked"' : '')?> />Frequencia de e-mails é muito alta.</label><br />
              <label><input type="checkbox" name="motivo[]" value="2" <?=(in_array(2,$a) ? 'checked="checked"' : '')?> />Não autorizei nem me recordo de ter autorizado o envio de mensagens para meu e-mail.</label><br />
              <label><input type="checkbox" name="motivo[]" value="3" <?=(in_array(3,$a) ? 'checked="checked"' : '')?> />O conteúdo não me interessa.</label><br />

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
