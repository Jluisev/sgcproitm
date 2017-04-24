<?php
require_once('conexion.php');
connectdb();
if(!loggedin()){
    header("Location: login.php");
}
function actualizarPosiciones()
{

    $i = 0;
    $anterior = 0;

    $query = "Select * from equipo_concurso inner join equipos using (id_equipo) where id_concurso='" . $_SESSION['concurso'] . "' order by puntuacion desc";
    $result = mysql_query($query);

    while ($fields = mysql_fetch_array($result, MYSQL_ASSOC)) {
        $i = $i + 1;

        //Si hay empate regresar al valor de i anterior

        if ($fields['puntuacion'] == $anterior) {
            $i = $i - 1;
        }

        if ($fields['posicion'] != $i) {

            $queryActualizarPosicion = "Update equipo_concurso set posicion='" . $i . "' where id_equipo_concurso='" . $fields['id_equipo_concurso'] . "'";
            mysql_query($queryActualizarPosicion);

        }

        $anterior = $fields['puntuacion'];

    }
}

function archivarSolucion($fields)
{

    $query = "INSERT INTO `solucionesArchivadas`(`id_solucion`, `id_equipos_concurso`, `id_problemas`, `id_concurso`, `codigo`, `salida_ejecucion`, `hora`, `evaluacion`, `evaluacionCompilador`, `id_juez`, `comentarios`, `lenguaje`, `nenvios`, `tiempoEjecucion`, `puntosObtenidos`) SELECT * from solucion where id_solucion='" . $fields['id_solucion'] . "'";
    mysql_query($query);

}

function correccionDisminuirPuntos($fieldsEquipo, $fields)
{

    echo 'si entra';

    $queryCorregirEquipo = "Update equipo_concurso set resueltas='" . ($fieldsEquipo['resueltas'] - 1) . "', puntuacion='" . ($fieldsEquipo['puntuacion'] - $fields['puntosObtenidos']) . "' where id_equipo_concurso='" . $fieldsEquipo['id_equipo_concurso'] . "'";
    mysql_query($queryCorregirEquipo);

    actualizarPosiciones();
}

$puntos = 110;//Agregando el sistema de puntuación
$reduccion = 0;

$query = "Select * from solucion where id_concurso='" . $_SESSION['concurso'] . "' and id_solucion='" . $_GET['idSolucion'] . "'";
$result = mysql_query($query);
$fields = mysql_fetch_array($result);

if ($fields['evaluacion'] == 'Pendiente') {
    $queryUpdatePendiente = "Update solucion set evaluacion='Evaluando' where id_solucion='" . $_GET['idSolucion'] . "'";
    mysql_query($queryUpdatePendiente);
}

$queryProblema = "Select * from problemas where id_problemas='" . $fields['id_problemas'] . "'";
$resultProblema = mysql_query($queryProblema);
$fieldsProblema = mysql_fetch_array($resultProblema);

$queryEquipo = "Select * from equipos inner join equipo_concurso using(id_equipo) where id_equipo=(Select id_equipo from equipo_concurso where id_equipo_concurso=" . $fields['id_equipos_concurso'] . ")";
$resultEquipo = mysql_query($queryEquipo);
$fieldsEquipo = mysql_fetch_array($resultEquipo);

if (isset($_POST['evaluacion'])) {

    if ($_POST['activado'] == "false") {

        if ($fields['id_juez'] != null) {
            archivarSolucion($fields);
            if ($fields['evaluacion'] == 'Aceptado' && ($fields['evaluacionCompilador'] == 'Salida incorrecta' || $fields['evaluacionCompilador'] == 'Error de compilacion')) {
                correccionDisminuirPuntos($fieldsEquipo, $fields);
            }
        }

        $queryUpdate = "Update solucion set evaluacion='" . $fields['evaluacionCompilador'] . "', id_juez='" . $_SESSION['idJurado'] . "', puntosObtenidos='0'  where id_solucion='" . $_GET['idSolucion'] . "'";
        mysql_query($queryUpdate);

        if ($fields['evaluacionCompilador'] == 'Aceptado') {

            //Ajustando puntos a sumarle al equipo
            if ($fields['nenvios'] > 4) {
                $reduccion = 50;
            } else {
                $reduccion = ($fields['nenvios']) * 10;
            }

            $puntos = $puntos - $reduccion;
            $puntosObtenidos = $puntos;
            $puntos = $puntos + $fieldsEquipo['puntuacion'];

            $queryCorrecta = "Update equipo_concurso set resueltas='" . ($fieldsEquipo['resueltas'] + 1) . "', puntuacion=" . $puntos . " where id_equipo_concurso='" . $fields['id_equipos_concurso'] . "'";
            mysql_query($queryCorrecta);

            $queryUpdatePuntos = "Update solucion set puntosObtenidos='" . $puntosObtenidos . "' where id_solucion='" . $_GET['idSolucion'] . "'";
            mysql_query($queryUpdatePuntos);

            actualizarPosiciones();
        }

        header("Location: evaluaciones.php?evaluado=1");

        /*echo("<br><div class=\"alert alert-warning\">\nEl problema ha sido evaluado satisfactoriamente.\n</div>");*/

    } else {

        if ($fields['id_juez'] != null) {
            archivarSolucion($fields);
            if ($fields['evaluacion'] == 'Aceptado' && $_POST['solucionAceptada'] == 'Salida incorrecta') {
                correccionDisminuirPuntos($fieldsEquipo, $fields);
            }
        }

        $queryUpdate = "Update solucion set evaluacion='" . $_POST['solucionAceptada'] . "', id_juez='" . $_SESSION['idJurado'] . "', comentarios='" . $_POST['aclaracionEvaluacion'] . "', puntosObtenidos='0' where id_solucion='" . $_GET['idSolucion'] . "'";
        mysql_query($queryUpdate);

        //Anterior

        /*if($_POST['solucionAceptada']=='Aceptado'){
        $queryCorrecta="Update equipo_concurso set resueltas='".($fieldsEquipo['resueltas']+1)."' where id_equipo_concurso='".$fields['id_equipos_concurso']."'";
        mysql_query($queryCorrecta);
        }*/

        if ($_POST['solucionAceptada'] == 'Aceptado') {

            //Ajustando puntos a sumarle al equipo
            if ($fields['nenvios'] > 4) {
                $reduccion = 50;
            } else {
                $reduccion = ($fields['nenvios']) * 10;
            }

            $puntos = $puntos - $reduccion;
            $puntosObtenidos = $puntos;
            $puntos = $puntos + $fieldsEquipo['puntuacion'];

            $queryCorrecta = "Update equipo_concurso set resueltas='" . ($fieldsEquipo['resueltas'] + 1) . "', puntuacion=" . $puntos . " where id_equipo_concurso='" . $fields['id_equipos_concurso'] . "'";
            mysql_query($queryCorrecta);

            $queryUpdatePuntos = "Update solucion set puntosObtenidos='" . $puntosObtenidos . "' where id_solucion='" . $_GET['idSolucion'] . "'";
            mysql_query($queryUpdatePuntos);

            actualizarPosiciones();
        }


        header("Location: evaluaciones.php?evaluado=1");

        /*echo("<br><div class=\"alert alert-warning\">\nSu evaluación ha sido registrada correctamente.\n</div>");*/

    }
}

?>