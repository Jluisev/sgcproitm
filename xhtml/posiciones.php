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
<?php
require_once('conexion.php');
connectdb();

$i=0;

$query="Select * from equipo_concurso inner join equipos using (id_equipo) where id_concurso='".$_SESSION['concurso']."' order by puntuacion desc";
$result=mysql_query($query);
$num = mysql_num_rows($result);
    ?>
    <div id="main">
    <h3>Tabla de posiciones</h3>
        <br>
                    	<table cellpadding="0" cellspacing="0">
                            <tr class="odd"><th>IdEquipo</th><th>Equipo</th><th># Resueltos</th><th>Puntos</th><th>Posicion</th></tr>
							<?php

while($fields = mysql_fetch_array($result, MYSQL_ASSOC)){
    
    echo '
                            <tr>
                                <td>'.$fields['id_equipo'].'</td>             <td>'.$fields['nombre'].'</td><td>'.$fields['resueltas'].'</td>
<td>'.$fields['puntuacion'].'</td>
<td>'.$fields['posicion'].'</td></tr>';
    
}
							 ?>                   
							                      
                        </table>
    </div>
    </body>
</html>