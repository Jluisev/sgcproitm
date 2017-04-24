<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>SGCPROITM</title>

    <!-- CSS -->
    <link href="style/css/transdmin.css" rel="stylesheet" type="text/css" media="screen"/>
    <link rel="stylesheet" href="style/css/codemirror.css">
    <!--[if IE 6]>
    <link rel="stylesheet" type="text/css" media="screen" href="style/css/ie6.css"/><![endif]-->
    <!--[if IE 7]>
    <link rel="stylesheet" type="text/css" media="screen" href="style/css/ie7.css"/><![endif]-->
</head>
<body> <!---->
<div id="main">

    <br><br>

    <?php
    require_once('./controladores/evaluador-soluciones.php');
    if (!loggedin()) {
        header("Location: login.php");
    }
    if ($fields['evaluacion'] == 'Evaluando') {
        echo('<br><div class=\"alert alert-warning\">La solución que estás viendo tiene estado de "Evaluando", favor de checar que otro juez no esté revisando el mismo problema.<br><br></div>');
    } else {
        if ($fields['evaluacion'] == 'Aceptado' || $fields['evaluacion'] == 'Salida Incorrecta') {
            echo('<br><div class=\"alert alert-warning\">La solución que estás viendo ya tiene veredicto. Si decides editarlo, favor de escribir en los comentarios que lo has hecho y tus razones.<br><br></div>');
        }
    }
    ?>
    <fieldset>

        <?php
        echo '<h3>' . $fieldsProblema['nombre_problema'] . ' -  ' . $fieldsEquipo['nombre'] . '</h3>';

        $text = nl2br($fieldsProblema['texto_archivo']);
        $breaks = array("<br />", "<br>", "<br/>");
        $text = str_ireplace($breaks, "\r\n", $text);

        $textCodigo = nl2br($fields['codigo']);
        $breaksCodigo = array("<br />", "<br>", "<br/>");
        $textCodigo = str_ireplace($breaksCodigo, "\r\n", $textCodigo);

        echo '<h3>Texto del problema</h3>
        <textarea disabled>' . $text . '</textarea>
       <h3>Entrada de muestra</h3>
        <p>' . nl2br($fieldsProblema['texto_entrada']) . '</p>
        <h3>Salida de muestra</h3>
        <p>' . nl2br($fieldsProblema['texto_salida']) . '</p>';

        echo '<h3>Código de la solución enviada</h3>
        <textarea disabled class="codigo">' . $textCodigo . '</textarea>
         <h3>Lenguaje</h3>
        <p>' . $fields['lenguaje'] . '</p>
        <h3>Resultado de compilador</h3>
        <p>' . $fields['evaluacionCompilador'] . '</p>
        <h3>Salida en consola</h3>
        <textarea disabled class="comentarios">' . $fields['salida_ejecucion'] . '</textarea><br><br><br><br><br><br>
       ';

        ?>

        <h3>Evaluación Manual</h3>

        <input type="checkbox" name="activar" id="grupoCorregir"/> Activar evaluación directa
        <br>
        <!--class="jNice"  -->
        <form action="evaluarSolucion.php?idSolucion=<?php echo $_GET['idSolucion']; ?>" method="post" class="jNice">

            <input type="hidden" name="evaluacion" value=<?php
            echo '"' . $fields['evaluacionCompilador'] . '"';
            ?>><br>
            <input type="hidden" name="activado" value="false" id="act"><br>
            <div id="inputs-evaluacion-manual">
                <input type=radio name="solucionAceptada" id="input1" value="Aceptado"/> <label for="input1">Solución aceptada</label><br><br>
                <input type=radio name="solucionAceptada" id="input2" value="Salida incorrecta"/> <label
                    for="input2">Solución
                    incorrecta</label><br><br>
                <textarea name="aclaracionEvaluacion" rows="4" cols="50" id="input3"
                          placeholder="Escriba la razón por la cual decidió evaluar directamente."
                          class="comentarios"></textarea><br><br><br><br><br><br><br><br>
            </div>
            <input type="submit" value="Confirmar evaluación">

        </form>

    </fieldset>
</div>
<!-- JavaScripts-->
<script type="text/javascript" src="style/js/jquery.js"></script>
<script type="text/javascript" src="style/js/jNice.js"></script>
<script type="text/javascript" src="style/js/evaluar-solucion.js"></script>
</body>
</html>