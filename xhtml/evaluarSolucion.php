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

function actualizarPosiciones(){

    $i=0;
    $anterior=0;

$query="Select * from equipo_concurso inner join equipos using (id_equipo) where id_concurso='".$_SESSION['concurso']."' order by puntuacion desc";
$result=mysql_query($query);
    
    while($fields = mysql_fetch_array($result, MYSQL_ASSOC)){
    $i=$i+1;
    
    //Si hay empate regresar al valor de i anterior
    
    if($fields['puntuacion']==$anterior){
    $i=$i-1;
    }
        
    if($fields['posicion']!=$i){
    
      $queryActualizarPosicion="Update equipo_concurso set posicion='".$i."' where id_equipo_concurso='".$fields['id_equipo_concurso']."'";  
    mysql_query($queryActualizarPosicion); 
        
    }
        
    $anterior=$fields['puntuacion'];
    
  }
}

function archivarSolucion($fields){

$query="INSERT INTO `solucionesArchivadas`(`id_solucion`, `id_equipos_concurso`, `id_problemas`, `id_concurso`, `codigo`, `salida_ejecucion`, `hora`, `evaluacion`, `evaluacionCompilador`, `id_juez`, `comentarios`, `lenguaje`, `nenvios`, `tiempoEjecucion`, `puntosObtenidos`) SELECT * from solucion where id_solucion='".$fields['id_solucion']."'";
mysql_query($query);

}

function correccionDisminuirPuntos($fieldsEquipo, $fields){

    echo 'si entra';
    
    $queryCorregirEquipo="Update equipo_concurso set resueltas='".($fieldsEquipo['resueltas']-1)."', puntuacion='".($fieldsEquipo['puntuacion']-$fields['puntosObtenidos'])."' where id_equipo_concurso='".$fieldsEquipo['id_equipo_concurso']."'";
mysql_query($queryCorregirEquipo);
    
    actualizarPosiciones();
}

$puntos=110;//Agregando el sistema de puntuación
$reduccion=0;

require_once('conexion.php');
connectdb();

$query="Select * from solucion where id_concurso='".$_SESSION['concurso']."' and id_solucion='".$_GET['idSolucion']."'";
$result=mysql_query($query);
$fields = mysql_fetch_array($result);

if($fields['evaluacion']=='Pendiente'){
$queryUpdatePendiente="Update solucion set evaluacion='Evaluando' where id_solucion='".$_GET['idSolucion']."'";
    mysql_query($queryUpdatePendiente);
}

$queryProblema="Select * from problemas where id_problemas='".$fields['id_problemas']."'";
$resultProblema=mysql_query($queryProblema);
$fieldsProblema=mysql_fetch_array($resultProblema);

$queryEquipo="Select * from equipos inner join equipo_concurso using(id_equipo) where id_equipo=(Select id_equipo from equipo_concurso where id_equipo_concurso=".$fields['id_equipos_concurso'].")";
    $resultEquipo=mysql_query($queryEquipo);
    $fieldsEquipo=mysql_fetch_array($resultEquipo);

if(isset($_POST['evaluacion'])){
    
    if($_POST['activado']=="false"){
        
        if($fields['id_juez']!=null){
        archivarSolucion($fields);
            if($fields['evaluacion']=='Aceptado'&&($fields['evaluacionCompilador']=='Salida incorrecta'||$fields['evaluacionCompilador']=='Error de compilacion')){
     correccionDisminuirPuntos($fieldsEquipo, $fields);       
            }
        }
    
    $queryUpdate="Update solucion set evaluacion='".$fields['evaluacionCompilador']."', id_juez='".$_SESSION['idJurado']."', puntosObtenidos='0'  where id_solucion='".$_GET['idSolucion']."'";
    mysql_query($queryUpdate);
    
    if($fields['evaluacionCompilador']=='Aceptado'){
    
    //Ajustando puntos a sumarle al equipo
    if($fields['nenvios']>4){
    $reduccion=50;
    }else{
    $reduccion=($fields['nenvios'])*10;
    }
    
    $puntos=$puntos-$reduccion;
    $puntosObtenidos=$puntos;
    $puntos=$puntos+$fieldsEquipo['puntuacion'];
        
    $queryCorrecta="Update equipo_concurso set resueltas='".($fieldsEquipo['resueltas']+1)."', puntuacion=".$puntos." where id_equipo_concurso='".$fields['id_equipos_concurso']."'";
    mysql_query($queryCorrecta);
    
    $queryUpdatePuntos="Update solucion set puntosObtenidos='".$puntosObtenidos."' where id_solucion='".$_GET['idSolucion']."'";
    mysql_query($queryUpdatePuntos);
    
    actualizarPosiciones();
    }

header("Location: evaluaciones.php?evaluado=1");
        
/*echo("<br><div class=\"alert alert-warning\">\nEl problema ha sido evaluado satisfactoriamente.\n</div>");*/
    
 }else{
        
if($fields['id_juez']!=null){
        archivarSolucion($fields);
    if($fields['evaluacion']=='Aceptado'&&$_POST['solucionAceptada']=='Salida incorrecta'){
     correccionDisminuirPuntos($fieldsEquipo, $fields);
    }
        }
        
$queryUpdate="Update solucion set evaluacion='".$_POST['solucionAceptada']."', id_juez='".$_SESSION['idJurado']."', comentarios='".$_POST['aclaracionEvaluacion']."', puntosObtenidos='0' where id_solucion='".$_GET['idSolucion']."'";
    mysql_query($queryUpdate);
    
    //Anterior
        
    /*if($_POST['solucionAceptada']=='Aceptado'){
    $queryCorrecta="Update equipo_concurso set resueltas='".($fieldsEquipo['resueltas']+1)."' where id_equipo_concurso='".$fields['id_equipos_concurso']."'";
    mysql_query($queryCorrecta);  
    }*/
        
    if($_POST['solucionAceptada']=='Aceptado'){
    
    //Ajustando puntos a sumarle al equipo
    if($fields['nenvios']>4){
    $reduccion=50;
    }else{
    $reduccion=($fields['nenvios'])*10;
    }
    
    $puntos=$puntos-$reduccion;
    $puntosObtenidos=$puntos;
    $puntos=$puntos+$fieldsEquipo['puntuacion'];
        
    $queryCorrecta="Update equipo_concurso set resueltas='".($fieldsEquipo['resueltas']+1)."', puntuacion=".$puntos." where id_equipo_concurso='".$fields['id_equipos_concurso']."'";
    mysql_query($queryCorrecta);  
        
    $queryUpdatePuntos="Update solucion set puntosObtenidos='".$puntosObtenidos."' where id_solucion='".$_GET['idSolucion']."'";
    mysql_query($queryUpdatePuntos);
        
    actualizarPosiciones();
    }


        
header("Location: evaluaciones.php?evaluado=1");
        
/*echo("<br><div class=\"alert alert-warning\">\nSu evaluación ha sido registrada correctamente.\n</div>");*/
    
    }
}

    ?>
        
        <script language='javascript'>

function alertaSalida() {
    return 'Alerta: si está dejando esta página por otro medio que no sea dando click en el botón "Confirmar evaluación", el estado de esta solución se mostrará como "Evaluando" en la tabla de evaluaciones. Favor de terminar de evaluarla o avisar de esto a los otros jueces.';
}

</script>
        
        <br><br>
        
        <?php
if($fields['evaluacion']=='Evaluando'){
echo('<br><div class=\"alert alert-warning\">La solución que estás viendo tiene estado de "Evaluando", favor de checar que otro juez no esté revisando el mismo problema.<br><br></div>');
}else{
 if($fields['evaluacion']=='Aceptado'||$fields['evaluacion']=='Salida Incorrecta'){
echo('<br><div class=\"alert alert-warning\">La solución que estás viendo ya tiene veredicto. Si decides editarlo, favor de escribir en los comentarios que lo has hecho y tus razones.<br><br></div>');
}   
}
?>
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
        <h3>Resultado de compilador</h3>
        <p>'.$fields['evaluacionCompilador'].'</p>
        <h3>Salida en consola</h3>
        <textarea disabled class="comentarios">'.$fields['salida_ejecucion'].'</textarea><br><br><br><br><br><br>
       ';

        ?>
    
        <h3>Evaluación directa(en caso de que la evaluación automática sea errónea)</h3>
        
        <input type="checkbox" name="activar" id="grupoCorregir"/> Activar evaluación directa
        <br>
        <script type="text/javascript">
$(document).ready(function(){
    $("#inputs").hide();
    //$("#input3").val($("#act").val());
$(function() {
  //enable_cb();
  $("#grupoCorregir").click(enable_cb);
});

function enable_cb() {
    
  if (this.checked) {
      $("#act").val("true");
      //$("#input3").val($("#act").val());
      $("#inputs").show();
  } else {
      $("#act").val("false");
      $("#inputs").hide();
  }
}
    
});
        </script>
        <!--class="jNice"  -->
        <form action="evaluarSolucion.php?idSolucion=<?php echo $_GET['idSolucion'];?>" method="post" class="jNice">
        
        <input type="hidden" name="evaluacion" value=<?php
               echo '"'.$fields['evaluacionCompilador'].'"';
               ?>><br>
        <input type="hidden" name="activado" value="false" id="act"><br>
        <div id=inputs>
        <input type=radio name="solucionAceptada" id="input1" value="Aceptado"/> Solución aceptada<br><br>
        <input type=radio name="solucionAceptada" id="input2" value="Salida incorrecta"/> Solución incorrecta<br><br>
        <textarea name="aclaracionEvaluacion" rows="4" cols="50" id="input3" placeholder="Escriba la razón por la cual decidió evaluar directamente." class="comentarios"></textarea><br><br><br><br><br><br><br><br>
            </div>
        <input type="submit" value="Confirmar evaluación"> 
        
        </form>
        
    </fieldset>
    </div>
</body>
</html>