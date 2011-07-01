<?php
set_time_limit(0);

define(SERVIDOR, 'localhost');
define(USUARIO, 'eduardo');
define(SENHA, 'eduardo');
define(DATABASE, 'iteia_geral');
$conect1 = mysql_pconnect(SERVIDOR, USUARIO, SENHA);
mysql_select_db(DATABASE, $conect1);

$dir_videos_originais = "/home/rui/iteia4/conteudo/videos/originais/";
$dir_videos_convertidos = "/home/rui/iteia4/conteudo/videos/convertidos/";
$dir_script_conversao = "/home/iteia/stuff/";

$sql_result = mysql_query("select cod_video, arquivo_original, arquivo_convertido from Videos_Conversao where status = 0;");
while ($sql_row = mysql_fetch_array($sql_result)) { echo $sql_row["cod_video"];
        if (file_exists($dir_videos_originais.$sql_row["arquivo_original"])) {
                $data_inicio = date("Y-m-d H:i:s");
                //$resultado = exec($dir_script_conversao."mencoder_convert.sh ".$dir_videos_originais.$sql_row["arquivo_original"]." ".$dir_videos_convertidos.$sql_row["arquivo_convertido"]);
                
                if (!filesize($dir_videos_convertidos.$sql_row["arquivo_convertido"]))
                    $resultado = exec($dir_script_conversao."ffmpeg_convert.sh ".$dir_videos_originais.$sql_row["arquivo_original"]." ".$dir_videos_convertidos.$sql_row["arquivo_convertido"]);
                if (!filesize($dir_videos_convertidos.$sql_row["arquivo_convertido"]))
                    exec($dir_script_conversao."ffmpeg_convert1.sh ".$dir_videos_originais.$sql_row["arquivo_original"]." ".$dir_videos_convertidos.$sql_row["arquivo_convertido"]);
                if (!filesize($dir_videos_convertidos.$sql_row["arquivo_convertido"]))
                    exec($dir_script_conversao."ffmpeg_convert2.sh ".$dir_videos_originais.$sql_row["arquivo_original"]." ".$dir_videos_convertidos.$sql_row["arquivo_convertido"]);
                exec($dir_script_conversao."ffmpeg_convert3.sh ".$dir_videos_originais.$sql_row["arquivo_original"]." ".$dir_videos_convertidos.$sql_row["arquivo_convertido"]);

                //if (file_exists($dir_videos_convertidos.$sql_row["arquivo_convertido"]))
                //        $sql_result2 = mysql_query("update Videos_Conversao set data_entrada = '".$data_inicio."', data_saida = '".date("Y-m-d H:i:s")."', status = 1 where cod_video = '".$sql_row["cod_video"]."';");
                //else
                //        $sql_result2 = mysql_query("update Videos_Conversao set data_entrada = '".$data_inicio."', data_saida = '".date("Y-m-d H:i:s")."', status = 1, erro = '".$resultado."' where cod_video = '".$sql_row["cod_video"]."';");
        }
}
//function getScript($dir_script_conversao,$arquivo_original,$arquivo_convertido){
//    $arquivo = explode(".",$arquivo_original);
//    $extensao = $arquivo[count($arquivo)-1];
//    $extensao = strtolower($extensao);
//    $txt = '';
//    switch($extensao){
//        case 'asf':
//        case 'wmv':
//            $txt .= $dir_script_conversao."ffmpeg -i ".$arquivo_original." -ar 22050 -ab 32000 -f flv -pass 1 -passlogfile logffmpeg -b 500kbs ".$arquivo_original.".temp;";
//            $txt .= $dir_script_conversao."ffmpeg -i ".$arquivo_original.".temp -ar 22050 -ab 32000 -f flv -pass 2 -passlogfile logffmpeg -b 500kbs ".$arquivo_convertido." && rm ".$arquivo_original.".temp;";
//            break;
//        case 'mpg':
//        case 'mpeg':
//            $txt .= $dir_script_conversao."ffmpeg -i ".$arquivo_original." -f flv -ar 44100 -ac 1 ".$arquivo_convertido.";";
//            break;
//        case 'avi':
//            $txt .= $dir_script_conversao."ffmpeg -i ".$arquivo_original." -f flv ".$arquivo_convertido.";";
//            break;
//    }
//    $txt .= $dir_script_conversao."ffmpeg -itsoffset -30  -i ".$arquivo_convertido." -vcodec png -vframes 1 -an -f rawvideo -s 320x240 ".$arquivo_convertido.".png; ";
//    $txt .= "flvtool2 -U ".$arquivo_convertido.";";
//    return $txt;
//}
//set_time_limit(0);
//
//define(SERVIDOR, 'localhost');
//define(USUARIO, 'eduardo');
//define(SENHA, 'eduardo');
//define(DATABASE, 'iteia_geral');
//$conect1 = mysql_pconnect(SERVIDOR, USUARIO, SENHA);
//mysql_select_db(DATABASE, $conect1);
//
//$conectiteia = mysql_pconnect("localhost", 'eduardo', 'eduardo');
//mysql_select_db('iteia_geral', $conectiteia);
//
//$dir_videos_originais = "/home/rui/iteia4/conteudo/videos/originais/";
//$dir_videos_convertidos = "/home/rui/iteia4/conteudo/videos/convertidos/";
//$dir_script_conversao = "/home/iteia/stuff/";
//
//$sql_result = mysql_query("select cod_video, arquivo_original, arquivo_convertido from Videos_Conversao where status = 0;",$conect1);
//while ($sql_row = mysql_fetch_array($sql_result)) {
//    echo $sql_row["cod_video"];
//        if (file_exists($dir_videos_originais.$sql_row["arquivo_original"])) {
//            $executar = getScript("/usr/local/bin/",$dir_videos_originais.$sql_row["arquivo_original"],$dir_videos_convertidos.$sql_row["arquivo_convertido"]);
//            echo $executar;
//        
//            $data_inicio = date("Y-m-d H:i:s");
//            //$resultado = exec($dir_script_conversao."mencoder_convert.sh ".$dir_videos_originais.$sql_row["arquivo_original"]." ".$dir_videos_convertidos.$sql_row["arquivo_convertido"]);
//            //$resultado = exec($dir_script_conversao."ffmpeg_convert.sh ".$dir_videos_originais.$sql_row["arquivo_original"]." ".$dir_videos_convertidos.$sql_row["arquivo_convertido"]);
//            $resultado = exec($executar);
//            mysql_query("insert into Videos_Conversao_Log(cod_video,text_log,retorno_exec) values(".$sql_row["cod_video"].",'".$executar."','".$resultado."');",$conectiteia);
//            
//            //if (file_exists($dir_videos_convertidos.$sql_row["arquivo_convertido"]))
//            //    $sql_result2 = mysql_query("update Videos_Conversao set data_entrada = '".$data_inicio."', data_saida = '".date("Y-m-d H:i:s")."', status = 1 where cod_video = '".$sql_row["cod_video"]."';",$conect1);
//            //else
//            //    $sql_result2 = mysql_query("update Videos_Conversao set data_entrada = '".$data_inicio."', data_saida = '".date("Y-m-d H:i:s")."', status = 1, erro = '".$resultado."' where cod_video = '".$sql_row["cod_video"]."';",$conect1);
//    }
//}