<?php
set_time_limit(0);
$arquivo = '/tmp/conteudo/iteia_news_lock';
//unlink($arquivo); // local

if (file_exists($arquivo)) {
    die ("The file $arquivo exists");
} else {
    include_once(dirname(__FILE__).'/classes/bo/Newsletter_EnvioBO.php');
    file_put_contents($arquivo, date('Y-m-d H:i:s'));
    $newsbo = new Newsletter_EnvioBO();
    $newsbo->envioNewsletter();
    unlink($arquivo);    
}