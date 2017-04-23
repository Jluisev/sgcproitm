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
<body onbeforeunload="return alertaSalida()"> <!---->
    <div id="main">
    <?php

require_once('conexion.php');
connectdb();

$query="Select * from solucion where id_concurso='".$_SESSION['concurso']."' and id_solucion='".$_GET['idSolucion']."'";
$result=mysql_query($query);
$fields = mysql_fetch_array($result);

$queryProblema="Select * from problemas where id_problemas='".$fields['id_problemas']."'";
$resultProblema=mysql_query($queryProblema);
$fieldsProblema=mysql_fetch_array($resultProblema);

$queryEquipo="Select * from equipos inner join equipo_concurso using(id_equipo) where id_equipo=(Select id_equipo from equipo_concurso where id_equipo_concurso=".$fields['id_equipos_concurso'].")";
    $resultEquipo=mysql_query($queryEquipo);
    $fieldsEquipo=mysql_fetch_array($resultEquipo);

    ?>
        
        
        <br><br>
        
     
    <fieldset>
        
        <?php
        echo '<h3>'.$fieldsProblema['nombre_problema'].' -  '.$fieldsEquipo['nombre'].'</h3>';

        $text = nl2br($fieldsProblema['texto_archivo']);
        $breaks = array("<br />","<br>","<br/>");  
        $text = str_ireplace($breaks, "\r\n", $text);

        $textCodigo = nl2br($fields['codigo']);
        $breaksCodigo = array("<br />","<br>","<br/>");  
        $textCodigo = str_ireplace($breaksCodigo, "\r\n", $textCodigo);    

        echo'<h3>Texto del problema</h3>
        <textarea disabled>'.$text.'</textarea>
       <h3>Entrada de muestra</h3>
        <p>'.nl2br($fieldsProblema['texto_entrada']).'</p>
        <h3>Salida de muestra</h3>
        <p>'.nl2br($fieldsProblema['texto_salida']).'</p>';

        echo '<h3>Código de la solución enviada</h3>
        <textarea disabled class="codigo">'.$textCodigo.'</textarea>
         <h3>Lenguaje</h3>
        <p>'.$fields['lenguaje'].'</p>
        <h3>Salida en consola</h3>
        <textarea disabled class="comentarios">'.$fields['salida_ejecucion'].'</textarea><br><br><br><br><br><br>
        <h3>Resultado del compilador automático</h3>
        <p>'.$fields['evaluacionCompilador'].'</p>
        <h3>Veredicto del jurado</h3>
        <p>'.$fields['evaluacion'].'</p>
        <h3>Puntos otorgados</h3>
        <p>'.$fields['puntosObtenidos'].'</p>
        
       ';

        ?>
        
    </fieldset>
    </div>
</body>
</html>