<?php
//if($autor){
//    $aut = $autor['autor'];
//    //print_r($autor);
//    array_push($codigos,$aut['cod_usuario']);
//    foreach($aut['colaboradores'] as $mais){
//        array_push($codigos,$mais['cod_usuario']);
//    }
//    foreach($autor['autor']['mais_acessados'] as $mais){
//        array_push($codigos,$mais['cod_colaborador']);
//    }
//}
//if($colaborador){
//    //print_r($colaborador);
//    if($colaborador['colaborador']){
//        $colab = $colaborador['colaborador'];
//        array_push($codigos,$colab['cod_usuario']);
//        foreach($colab['autores'] as $mais){
//            array_push($codigos,$mais['cod_usuario']);
//        }
//        if($colab['mais_acessados'])
//            foreach($colab['mais_acessados'] as $mais){
//                array_push($codigos,$mais['cod_colaborador']);
//            }
//    }else{
//        array_push($codigos,$colaborador['cod_usuario']);
//    }
//}
//if($chomebo){
//    //print_r($chomebo);
//}

$codigos = array();

switch($topo_class){
    ///////////////////// HOME
    case 'index':
    ///////////////////// NOTICIAS
    case 'cat-noticias':
    ///////////////////// EVENTOS
    case 'cat-eventos':
    ///////////////////// Boletim
    case 'boletim':
        break;
    ///////////////////// AUDIOS
    case 'cat-audios':
    ///////////////////// VIDEOS
    case 'cat-videos':
    ///////////////////// TEXTOS
    case 'cat-textos':
    ///////////////////// IMAGENS
    case 'cat-imagens':
        $codigos = $chomebo->getListaUltimosAutores();
        break;
    ///////////////////// AUTORES
    case 'cat-autores':
        $aut = $autor['autor'];
        array_push($codigos,$aut['cod_usuario']);
        foreach($aut['colaboradores'] as $mais){
            array_push($codigos,$mais['cod_usuario']);
        }
        break;
    ///////////////////// COLABORADORES
    case 'cat-colaboradores':
        $colab = $colaborador['colaborador'];
        array_push($codigos,$colab['cod_usuario']);
        foreach($colab['autores'] as $mais){
            array_push($codigos,$mais['cod_usuario']);
        }
        break;  
}

$dados_banners = array("codigos" => array_unique($codigos),"pagina" => $topo_class);
//print_r($dados_banners);

include_once('classes/bo/BannerExibicaoBO.php');
$banbo = new BannerExibicaoBO;

if($banbo->temBanners($dados_banners)){
?>
<div id="banners" class="vertical"><a href="/publicidade" title="Saiba mais sobre a publicidade no iTEIA">Publicidade Colaborativa</a>
    <?php echo $banbo->getHtmlBannersLaterais($dados_banners); ?>
</div>
<?php
}

//exibir banners padrão
else{
    $dados_banners = array("codigos" => array(5418),"pagina" => 'cat-noticias');
?>
<div id="banners" class="vertical"><a href="/publicidade" title="Saiba mais sobre a publicidade no iTEIA">Publicidade Colaborativa</a>
    <?php echo $banbo->getHtmlBannersLaterais($dados_banners); ?>
</div>
<?php
}
?>
