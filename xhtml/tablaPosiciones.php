<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="./style/css/tabla-posiciones.css">
    <title>Conrepro</title>
</head>
<body>
<?php
require_once('conexion.php');
connectdb();

$i = 0;

$query = "Select * from equipo_concurso inner join equipos using (id_equipo) where id_concurso='1' order by puntuacion desc";
$result = mysql_query($query);
$num = mysql_num_rows($result);
?>
<div class="container">
    <div class="row">
        <div class="col-md-12 header-wrapper">
            <div class="header">
                <img src="./style/img/banner_web.927f4be5.jpeg" alt="logo">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 main-wrapper">
            <div id="main">
                <h3>Tabla de posiciones</h3>
                <hr>
                <table class="table table-striped table-hover" id="tabla-de-posiciones" cellpadding="0" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Preparatoria</th>
                        <th>Equipo</th>
                        <th># Resueltos</th>
                        <th>Puntos</th>
                        <th>Posicion</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
/*
                    while ($fields = mysql_fetch_array($result, MYSQL_ASSOC)) {

                        echo '<tr><td>' . $fields['id_equipo'] . '</td>             <td>' . $fields['nombre'] . '</td><td>' . $fields['resueltas'] . '</td>
<td>' . $fields['puntuacion'] . '</td>
<td>' . $fields['posicion'] . '</td></tr>';

                    }
                    */?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script
    src="https://code.jquery.com/jquery-3.2.1.min.js"
    integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
    crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
<script src="style/js/tabla-de-posiciones.js"></script>
</body>
</html>