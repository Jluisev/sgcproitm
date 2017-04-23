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
?>
    
<div id="main">
    
    <?php //Leyenda enviado

    if(isset($_GET['enviado'])){
        
        if($_GET['enviado']==1){
          echo("<br><div class=\"alert alert-warning\">\nTu programa ha sido enviado a los jueces para ser evaluado. Haz click en 'Evaluaciones' para ver el resultado.\n</div>");
        }else{
            if($_GET['enviado']==0){
        echo("<br><div class=\"alert alert-warning\">\n Tu solución para este problema ya había sido aceptada y no se ha modificado para proteger tu puntuación. Si intentabas solucionar otro problema, asegúrate de seleccionarlo correctamente en la casilla de problemas. \n</div>");
        }else{
            echo("<br><div class=\"alert alert-warning\">\n Tu solución para este problema se encuentra en evaluación actualmente. Favor de esperar a tener el resultado de los jueces o corregir el campo de selección del problema si te equivocaste. \n</div>");
            }
        }
        
    }

    ?>
    
    <form action="evaluador.php" method="post" class="jNice">
    <br><br>
    <fieldset>
        
         <p><label>Selecciona el problema a resolver: </label>
            <br><br><!--<input type="text" class="text-small" name="idProblema" />-->
             <?php
            $query="Select * from problemas inner join problemas_concurso on id_problemas=id_problema where id_concurso='".$_SESSION['concurso']."'";
$result=mysql_query($query);

            echo'<select name="idProblema">';
            while($fields = mysql_fetch_array($result, MYSQL_ASSOC)){
            echo '<option value="'.$fields['id_problemas'].'">'.$fields['nombre_problema'].'</option>';
            }
           echo '</select>';

                ?>
        <br> <br>
        
    <p><label>Selecciona el lenguaje de tu programa: </label>    
        <br> <br> 
        <select name="lang">
                            	<option>C</option>
                            	<option>C++</option>
                            	<option>Java</option>
                            	<option>C#</option>
                            	<option>Python</option>
                            	
                            </select>
        
    <p><label>Introduce tu código: </label>
        
        <br><br>    
        
        <textarea style="text-align: left; font-family: mono; height:400px;"  name="soln" id="text"><?php echo "//Escribe tu programa aquí";?></textarea></p>
        
                            <input type="submit" value="Enviar" />
        
        <input type="submit" value="Borrar"/>

	<script src="style/js/codemirror.js"></script>
	<script src="style/js/clike.js"></script>
	<script src="style/js/python.js"></script>
	<script src="style/js/matchbrackets.js"></script>
	<script src="style/js/closebrackets.js"></script>
    <script>
	var editor = CodeMirror.fromTextArea(document.getElementById("text"), {
            lineNumbers: true,
            matchBrackets: true,
	        indentUnit: 4,
	        autoCloseBrackets: true,
            mode: "text/x-csrc"
          });
    </script>
        
    </fieldset>
</form>
    </div>
					</body>
</html>