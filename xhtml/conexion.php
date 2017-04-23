<?php


function connectdb() {
$host="localhost";
$user="root";
$password="";
$database="SGCPROITM";
$compilerhost="localhost";
$compilerport=3029;
    
  mysql_connect($host,$user,$password);
  mysql_select_db($database) or die('Error connecting to database.');
}

session_start();

function loggedin() {
  return isset($_SESSION['username']);
}

function test_input($data){
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function setSessionVariables(){
    connectdb();
    $query="SELECT * from equipos where id_equipo=(select id_equipo from usuarios_equipos where usuario='".$_SESSION['username']."')";
    $result = mysql_query($query);
    $fields = mysql_fetch_array($result);
    $_SESSION['nombreEquipo']=$fields['nombre'];
    $_SESSION['idEquipo']=$fields['id_equipo'];
    
    $query="SELECT * from equipo_concurso where id_equipo=(select id_equipo from usuarios_equipos where usuario='".$_SESSION['username']."') and id_concurso='".$_SESSION['concurso']."'";
    $result = mysql_query($query);
    $fields = mysql_fetch_array($result);         
    $_SESSION['idEquipoConcurso']=$fields['id_equipo_concurso']; 
}

function setSessionJudge(){
    connectdb();
    
     $query="SELECT * from jurado where id_jurado=(select id_jurado from usuarios_jurado where usuario='".$_SESSION['username']."')";
    $result = mysql_query($query);
    $fields = mysql_fetch_array($result);
    
    $_SESSION['idJurado']=$fields['id_jurado'];
}

?>