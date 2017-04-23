<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SGCPROITM</title>

<!-- CSS -->
<link href="style/css/transdmin.css" rel="stylesheet" type="text/css" media="screen" />
<link rel="stylesheet" href="style/css/codemirror.css">
<!--[if IE 6]><link rel="stylesheet" type="text/css" media="screen" href="style/css/ie6.css" /><![endif]-->
<!--[if IE 7]><link rel="stylesheet" type="text/css" media="screen" href="style/css/ie7.css" /><![endif]-->

<!-- JavaScripts-->
<script type="text/javascript" src="style/js/jquery.js"></script>
<script type="text/javascript" src="style/js/jNice.js"></script>
</head>
<body>
    <div id="main">
    <?php
require_once('conexion.php');
connectdb();

$query="Select * from problemas inner join problemas_concurso on id_problemas=id_problema where id_concurso='".$_SESSION['concurso']."' and id_problemas='".$_GET['idProblema']."'";
$result=mysql_query($query);
$fields = mysql_fetch_array($result);
    ?>
        <br><br>
    <fieldset>
        <?php

        $text = nl2br($fields['texto_archivo']);
        $breaks = array("<br />","<br>","<br/>");  
        $text = str_ireplace($breaks, "\r\n", $text);

        echo '<h3>'.$fields['id_problemas'].' -  '.$fields['nombre_problema'].'</h3>';

        echo '<h3>Texto del problema</h3>
        <textarea disabled>'.$text.'</textarea>
        <h3>Entrada de muestra</h3>
        <p>'.nl2br($fields['texto_entrada']).'</p>
        <h3>Salida de muestra</h3>
        <p>'.nl2br($fields['texto_salida']).'</p>';

        ?>
        
        
    </fieldset>
    </div>
</body>
</html>