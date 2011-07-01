<?php
    include(dirname(__FILE__)."/../classes/dao/ConexaoDB.php");
    
    $arquivos = array();
    
    $db = ConexaoDB::singleton();
    $db->sql_select("*","Textos","","","");
    
    while($linha = $db->fetchObject()){
        $arquivos[] = $linha;
    }
    
    foreach($arquivos as $arquivo){
        $db->sql_insert("Textos_Anexos",
                        array(
                              "cod_conteudo" => $arquivo->cod_conteudo,
                              "arquivo" => $arquivo->arquivo,
                              "nome_arquivo_original" => $arquivo->nome_arquivo_original,
                              "tamanho" => $arquivo->tamanho
                              )
                        );
    }
?>