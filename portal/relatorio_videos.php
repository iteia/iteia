<?php
session_start();

if(!isset($_SESSION['login_rel']))
   $_SESSION['login_rel'] = $_POST['login'];

if(!isset($_SESSION['senha_rel']))
    $_SESSION['senha_rel'] = $_POST['senha'];

if(!($_SESSION['login_rel'] == 'diogoburgoskmf' && $_SESSION['senha_rel'] == 'iteiakmfsuporte')){
    ?>
    <form action="" method="post">
        <input type="text" name="login"><br>
        <input type="password" name="senha"><br>
        <input type="submit">
    </form>
    <?php
}else{
    ?>
    <form action="" method="post">
        <textarea rows="5" cols="70" name="sql"><?=stripslashes($sql);?></textarea>
        <input type="submit">
    </form>
    <?php
    $sql = $_POST['sql'];
    if(isset($_POST['sql'])){
        if(preg_match('/insert/',$sql) == 1 || preg_match('/update/',$sql) == 1 || preg_match('/delete/',$sql) == 1){
            echo utf8_decode('Operação não permitida!');
            exit;
        }
            
        $link = mysql_connect('192.168.0.159', 'iteia', 'iteia123');
        $db_selected = mysql_select_db('iteia', $link);
        //$link = mysql_connect('192.168.0.159', 'penc_videos', 'penc123');
        //$db_selected = mysql_select_db('penc_videos', $link);
        if ((!$link) || (!$db_selected)) {
            die('Could not connect: ' . mysql_error());
        }
        
        $resultado = mysql_query($sql,$link);
        $impar = false;
        $primeiravez = true;
        
        echo "Foram encontrados ".mysql_numrows($resultado)." registros<br>\n";
        echo"<table border='1' >\n";
        while($linha = mysql_fetch_array($resultado)){
            if($primeiravez){
                echo"   <tr style='background-color: lightgrey;'>\n";
                foreach($linha as $coluna => $valor){
                    if(!is_int($coluna))
                        echo"   <th>$coluna</th>\n";
                }
                $primeiravez = false;
                echo"</tr>\n";
            }
            
            if(!$impar){
                echo"<tr>\n";
                $impar = true;
            }
            else{
                echo"<tr style='background-color: lightgrey;'>\n";
                $impar = false;
            }
            foreach($linha as $coluna => $valor){
                if(!is_int($coluna))
                    echo"   <td>$valor</td>\n";
            }
        }
        echo"</table>";
    }
}
?>