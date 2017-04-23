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

$query = "Select * from problemas inner join problemas_concurso on id_problemas=id_problema where id_concurso='" . $_SESSION['concurso'] . "'";
$result = mysql_query($query);
$num = mysql_num_rows($result);
?>
<div id="main">
    <h3>Problemas</h3>
    <br>
    <table cellpadding="0" cellspacing="0">
        <tr class="odd">
            <th>Id Problema</th>
            <th>Nombre</th>
        </tr>
        <?php
        while ($fields = mysql_fetch_array($result, MYSQL_ASSOC)) {
            echo '<tr><td style="width:100px">' . $fields['id_problemas'] . '</td>
            <td class="action"><a href="descProblemas.php?idProblema=' . $fields['id_problemas'] . '" class="view">' . $fields['nombre_problema'] . '</a>
            </td></tr>';

        }
        ?>

    </table>
</div>
</body>
</html>