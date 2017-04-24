<?php
require_once('../conexion.php');
connectdb();

$i = 0;

$query = "Select instituciones.nombre as nombreInstitucion, equipos.nombre as nombreEquipo, equipo_concurso.resueltas, equipo_concurso.puntuacion, equipo_concurso.posicion 
from (equipo_concurso inner join equipos using (id_equipo)) 
inner join instituciones using (id_institucion) 
where id_concurso='1' order by puntuacion desc";
$result = mysql_query($query);
$num = mysql_num_rows($result);

$rows = '';

while ($fields = mysql_fetch_array($result, MYSQL_ASSOC)) {

    $rows .= '<tr><td>' . $fields['nombreInstitucion'] . '</td><td>' . $fields['nombreEquipo'] . '</td><td>' . $fields['resueltas'] . '</td>
<td>' . $fields['puntuacion'] . '</td>
<td>' . $fields['posicion'] . '</td></tr>';

}

echo $rows;