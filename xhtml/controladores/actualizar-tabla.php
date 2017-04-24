<?php
require_once('../conexion.php');
connectdb();

$i = 0;

$query = "Select * from equipo_concurso inner join equipos using (id_equipo) where id_concurso='1' order by puntuacion desc";
$result = mysql_query($query);
$num = mysql_num_rows($result);

$rows = '';

while ($fields = mysql_fetch_array($result, MYSQL_ASSOC)) {

    $rows.= '<tr><td>' . $fields['id_equipo'] . '</td><td>' . $fields['nombre'] . '</td><td>' . $fields['resueltas'] . '</td>
<td>' . $fields['puntuacion'] . '</td>
<td>' . $fields['posicion'] . '</td></tr>';

}

echo $rows;