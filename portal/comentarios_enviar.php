<?php
include_once('classes/vo/ConfigPortalVO.php');
include_once('classes/bo/ComentariosBO.php');

$comentariobo = new ComentariosBO;
$acao = trim($_POST['acao']);

if($_GET['ajax']){
    $ajax = trim($_GET['ajax']);
    $_POST['ajax'] = $ajax;
}

if ($acao == 'enviar') {
    try {
        $retorno=$comentariobo->inserirComentario($_POST);
        if($retorno!=1){
            echo '<div class="aviso sucesso">'.Util::iif($ajax,utf8_encode('Seu coment�rio foi enviado e est� aguardando aprova��o.'),'Seu coment�rio foi enviado e est� aguardando aprova��o.').'</div>';
            unset($_POST);
        } else {
            echo Util::iif($ajax,utf8_encode('<div class="aviso erro">N�o foi poss�vel enviar, por favor tente dentro de instantes.</div>'),'<div class="aviso erro">N�o foi poss�vel enviar, por favor tente dentro de instantes.</div>');
        }
        unset($_POST);
    } catch(Exception $e) {
        //echo utf8_encode('<div class="aviso erro">N�o foi poss�vel enviar, por favor tente dentro de instantes.</div>');
        echo Util::iif($ajax,utf8_encode('<script type="text/javascript">$(\'.aviso a\').click(function() {var target = $(this).attr("href");$(target).focus();return false;});</script><div class="aviso alerta">Por favor, preencha  os campos obrigat�rios.<ul>'.implode('</li>', $comentariobo->getCamposErros()).'</ul></div>'),'<script type="text/javascript">$(\'.aviso a\').click(function() {var target = $(this).attr("href");$(target).focus();return false;});</script><div class="aviso alerta">Por favor, preencha  os campos obrigat�rios.<ul>'.implode('</li>', $comentariobo->getCamposErros()).'</ul></div>');
    }
    
    if($ajax)
        die;
}