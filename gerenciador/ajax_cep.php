<?php
$xml = simplexml_load_file('http://cep.republicavirtual.com.br/web_cep.php?formato=xml&cep='.$_GET['cep']);
if ($xml) echo $xml->uf.','.$xml->cidade.','.$xml->bairro.','.$xml->tipo_logradouro.','.$xml->logradouro; die;
