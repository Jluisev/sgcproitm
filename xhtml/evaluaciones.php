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
<?php
require_once('conexion.php');
connectdb();

$query = "Select * from solucion inner join problemas using (id_problemas) where id_concurso='" . $_SESSION['concurso'] . "' order by hora desc";
$result = mysql_query($query);
$num = mysql_num_rows($result);

if (isset($_GET['evaluado'])) {
    echo("<br><div class=\"alert alert-warning\">\nEl problema ha sido evaluado satisfactoriamente.\n</div>");
}

?>
<div id="main">
    <h3>Programas en evaluaci&oacute;n</h3>
    <br>
    <table cellpadding="0" cellspacing="0">
        <tr class="odd">
            <th>Equipo</th>
            <th>#Problema</th>
            <th>Nombre</th>
            <th>Dificultad</th>
            <th>Hora</th>
            <th>Estado</th>
            <th>Lenguaje</th><?php //if(loggedin()&&$_SESSION['tipoUsuario']=='juez'){
            echo '<th>Acción</th>';
            //  }
            ?></tr>
        <?php
        while ($fields = mysql_fetch_array($result, MYSQL_ASSOC)) {

            $queryEquipo = "Select nombre from equipos where id_equipo=(Select id_equipo from equipo_concurso where id_equipo_concurso=" . $fields['id_equipos_concurso'] . ")";
            $resultEquipo = mysql_query($queryEquipo);
            $fieldsEquipo = mysql_fetch_array($resultEquipo);

            echo '<tr><td>' . $fieldsEquipo['nombre'] .'
<td>' . $fields['id_problemas'] . '</td><td>' . $fields['nombre_problema'] . '</td><td>' . $fields['dificultad'] . '</td><td>' . $fields['hora'] . '</td><td>' . $fields['evaluacion'] . '</td><td>' . $fields['lenguaje'] . '</td>';

            if (loggedin() && $_SESSION['tipoUsuario'] == 'juez') {
                echo '<td class="action"><a href="evaluarSolucion.php?idSolucion=' . $fields['id_solucion'] . '" class="view">Evaluar</a></td>';
            } else {
                if (loggedin() && $_SESSION['idEquipoConcurso'] == $fields['id_equipos_concurso'] && $fields['evaluacion'] != 'Evaluando' && $fields['evaluacion'] != 'Pendiente') {
                    echo '<td class="action"><a href="detalleSolucionEnviada.php?idSolucion=' . $fields['id_solucion'] . '" class="view">Ver detalle</a></td>';
                } else {
                    echo '<td>N/A</td>';
                }
            }

            echo '</tr>';

        }
        ?>

    </table>
</div>
</body>
</html>