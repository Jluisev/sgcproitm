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

    <!-- JavaScripts-->
    <script type="text/javascript" src="style/js/jquery.js"></script>
    <script type="text/javascript" src="style/js/jNice.js"></script>
</head>
<body>
<div id="main">
    <?php
    require_once('conexion.php');
    connectdb();

    if (!loggedin()) {
        header("Location: login.php");
    }

    $query = "Select * from equipos inner join equipo_concurso using(id_equipo) where id_equipo_concurso='" . $_SESSION['idEquipoConcurso'] . "'";
    $result = mysql_query($query);
    $fields = mysql_fetch_array($result);

    $queryAsesor = "Select * from asesores where id_asesor='" . $fields['id_asesor'] . "'";
    $resultAsesor = mysql_query($queryAsesor);
    $fieldsAsesor = mysql_fetch_array($resultAsesor);

    $queryLenguaje = "Select * from lenguajes where id_lenguaje='" . $fields['id_lenguaje'] . "'";
    $resultLenguaje = mysql_query($queryLenguaje);
    $fieldsLenguaje = mysql_fetch_array($resultLenguaje);

    $queryIntegrantes = "Select * from estudiantes inner join equipos_estudiantes using(id_estudiante) where id_equipo='" . $fields['id_equipo'] . "'";
    $resultIntegrantes = mysql_query($queryIntegrantes);
    ?>
    <br><br>
    <fieldset>
        <?php
        echo '<h3>Nombre del equipo:</h3> <p>' . $fields['nombre'] . '</p>';

        //echo $_SESSION['tipoUsuario'];

        echo '<h3>Informaci&oacute;n:</h3>
        <p>Asesor: ' . $fieldsAsesor['nombre'] . ' ' . $fieldsAsesor['apellido_paterno'] . ' ' . $fieldsAsesor['apellido_materno'] . '</p>
        <p>Lenguaje preferido: ' . $fieldsLenguaje['nombre'] . '</p>
        <p>Problemas resueltos: ' . $fields['resueltas'] . '</p>
        <p>Puntuación: ' . $fields['puntuacion'] . '</p>
        <p>Posición: ' . $fields['posicion'] . '</p><br><br>';
        ?>
        <h3>Integrantes:</h3><br>
        <table cellpadding="0" cellspacing="0" style="width:450px">
            <tr class="odd">
                <th>IdEstudiante</th>
                <th>Nombre</th>
            </tr>
            <?php
            while ($fieldsIntegrantes = mysql_fetch_array($resultIntegrantes, MYSQL_ASSOC)) {
                echo '<tr>
<td style="width:100px">' . $fieldsIntegrantes['id_estudiante'] . '</td><td>' . $fieldsIntegrantes['nombre'] . ' ' . $fieldsIntegrantes['apellido_paterno'] . ' ' . $fieldsIntegrantes['apellido_materno'] . '</td></tr>';

            }
            ?>

        </table>


    </fieldset>
</div>
</body>
</html>