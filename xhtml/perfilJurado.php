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

$query="Select * from jurado inner join juez_concurso using(id_jurado) where id_jurado='".$_SESSION['idJurado']."'";
$result=mysql_query($query);
$fields = mysql_fetch_array($result);
    ?>
        <br><br>
    <fieldset>
        <?php
        //echo $_SESSION['entra'];
        echo '<h3>Nombre del Juez:</h3> <p>'.$fields['nombre'].' '.$fields['apellido_paterno'].' '.$fields['apellido_materno'].'</p>';
        echo '<h3>Campos:</h3>
        <p>Título: '.$fields['titulo'].'</p>
        <p>Correo: '.$fields['correo'].'</p>'
            ;
        ?>        
        
    </fieldset>
    </div>
</body>
</html>