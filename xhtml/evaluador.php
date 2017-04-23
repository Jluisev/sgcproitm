<?php
require_once('conexion.php');
connectdb();
$compilerhost="localhost";
$compilerport=3029;
$username=$_SESSION['username'];
$idConcurso=$_SESSION['concurso'];
//$idConcurso=1;//temporal

$soln = mysql_real_escape_string($_POST['soln']);//codigo de  la solucion
$soln2=mysql_real_escape_string($_POST['soln']);//auxiliar
$lang = mysql_real_escape_string($_POST['lang']);
$idProblema= mysql_real_escape_string($_POST['idProblema']);
$time=5000;
$contents = "";

function treat($text) {
	$s1 = str_replace("\n\r", "\n", $text);
	return str_replace("\r", "", $s1);
}

        //Obtiene la solución enviada, si la hay
        
          $query = "SELECT * FROM solucion WHERE id_problemas='".$_POST['idProblema']."' AND id_equipos_concurso='".$_SESSION['idEquipoConcurso']."'";
          $result = mysql_query($query);
          $num = mysql_num_rows($result);
          $fields = mysql_fetch_array($result);

if($num==0){
$query="INSERT into solucion values(NULL,'".$_SESSION['idEquipoConcurso']."','".$_POST['idProblema']."','".$_SESSION['concurso']."','".$soln."', NULL, curTime( ),'Pendiente', NULL, NULL, NULL, '".$_POST['lang']."', '0', '0', '0')";
$rv=mysql_query($query);
    
    if($rv==false){
        echo mysql_errno();
    }
    
}//else{

echo $soln2;

    if($fields['evaluacion']=='Aceptado'){
        header("Location: subir solucion.php?enviado=0&id=".$_POST['idProblema']);
    }else{
        if($fields['evaluacion']=='Evaluando'){
         header("Location: subir solucion.php?enviado=2&id=".$_POST['idProblema']);
        }else{
    switch($lang) {
			    case 'C': $ext='c'; break;
			    case 'C++': $ext='cpp'; break;
			    case 'Java': $ext='java'; break;
			    case 'Python': $ext='py'; break;
                case 'C#': $ext='cs'; break;
			}
            $envios=$fields['nenvios']+1;
$socket = fsockopen($compilerhost, $compilerport);
			if($socket) {
				fwrite($socket, 'Solution.'.$ext."\n");
				$query = "SELECT texto_entrada, texto_salida FROM problemas WHERE id_problemas='".$_POST['idProblema']."'";
				$result = mysql_query($query);
				$fields = mysql_fetch_array($result);
				fwrite($socket, $time."\n");
				$soln = str_replace("\n", '$_n_$', treat($_POST['soln']));
				fwrite($socket, $soln."\n");
				$input = str_replace("\n", '$_n_$', treat($fields['texto_entrada']));
				fwrite($socket, $input."\n");
				fwrite($socket, $ext."\n");
				$status = fgets($socket);
				
				while(!feof($socket))
					$contents = $contents.fgets($socket);
				if($status == 0) {
					// Error de compilación
					$query = "UPDATE solucion SET evaluacionCompilador='Error de compilacion', evaluacion='Pendiente', nenvios='".$envios."', lenguaje='".$_POST['lang']."', hora=curTime( ), codigo='".$soln2."', salida_ejecucion='".$contents."' WHERE (id_equipos_concurso='".$_SESSION['idEquipoConcurso']."' AND id_problemas='".$_POST['idProblema']."')";
					mysql_query($query);
					/*$_SESSION['cerror'] = trim($contents);*/
					header("Location: subir solucion.php?enviado=1&id=".$_POST['idProblema']);
                    
				} else if($status == 1) {
					if(trim($contents) == trim(treat($fields['texto_salida']))) {
						//La solucion fue correcta
                        $query = "UPDATE solucion SET evaluacionCompilador='Aceptado', evaluacion='Pendiente', nenvios='".$envios."', lenguaje='".$_POST['lang']."',codigo='".$soln2."', hora=curTime( ), salida_ejecucion='".$contents."' WHERE (id_equipos_concurso='".$_SESSION['idEquipoConcurso']."' AND id_problemas='".$_POST['idProblema']."')";
					mysql_query($query);
					/*$_SESSION['cerror'] = trim($contents);*/
					header("Location: subir solucion.php?enviado=1&id=".$_POST['idProblema']);
                    
					} else {
						// Salida incorrecta
						
                        $query = "UPDATE solucion SET evaluacionCompilador='Salida incorrecta', evaluacion='Pendiente', nenvios='".$envios."', lenguaje='".$_POST['lang']."',codigo='".$soln2."', hora=curTime( ), salida_ejecucion='".$contents."' WHERE (id_equipos_concurso='".$_SESSION['idEquipoConcurso']."' AND id_problemas='".$_POST['idProblema']."')";
					mysql_query($query);
					/*$_SESSION['cerror'] = trim($contents);*/
					header("Location: subir solucion.php?enviado=1&id=".$_POST['idProblema']);                
					}
				} 
            } else{
                $query = "UPDATE solucion SET evaluacionCompilador='Compilador Offline', evaluacion='Pendiente', nenvios='".$envios."', lenguaje='".$_POST['lang']."',codigo='".$soln2."', hora=curTime( ), salida_ejecucion='".$contents."' WHERE (id_equipos_concurso='".$_SESSION['idEquipoConcurso']."' AND id_problemas='".$_POST['idProblema']."')";
					mysql_query($query);
				header("Location: subir solucion.php?serror=1&id=".$_POST['idProblema']); // compiler server not running
		}
    }
    }
        
//}

/*switch($lang) {
			    case 'C': $ext='c'; break;
			    case 'C++': $ext='cpp'; break;
			    case 'Java': $ext='java'; break;
			    case 'Python': $ext='py'; break;
                case 'C#': $ext='cs'; break;
			}

$socket = fsockopen($compilerhost, $compilerport);
			if($socket) {
                $envios=$fields['nenvios']+1;
				fwrite($socket, 'Solution.'.$ext."\n");
				$query = "SELECT texto_entrada, texto_salida FROM problemas WHERE id_problemas='".$_POST['idProblema']."'";
				$result = mysql_query($query);
				$fields = mysql_fetch_array($result);
				fwrite($socket, $time."\n");
				$soln = str_replace("\n", '$_n_$', treat($_POST['soln']));
				fwrite($socket, $soln."\n");
				$input = str_replace("\n", '$_n_$', treat($fields['texto_entrada']));
				fwrite($socket, $input."\n");
				fwrite($socket, $ext."\n");
				$status = fgets($socket);
				$contents = "";
				while(!feof($socket))
					$contents = $contents.fgets($socket);
				if($status == 0) {
					// oops! compile error
					$query = "UPDATE solucion SET evaluacionCompilador='Error de compilacion', nenvios='".$envios."', lenguaje='".$_POST['lang']."', hora=curTime( ), codigo='".$_POST['soln']."' WHERE (id_equipos_concurso='".$_SESSION['idEquipoConcurso']."' AND id_problemas='".$_POST['idProblema']."')";
					mysql_query($query);
					//$_SESSION['cerror'] = trim($contents);
					header("Location: subir solucion.php?enviado=1&id=".$_POST['idProblema']);
                    
				} else if($status == 1) {
					if(trim($contents) == trim(treat($fields['texto_salida']))) {
						//La solucion fue correcta
                        $query = "UPDATE solucion SET evaluacionCompilador='Aceptado', nenvios='".$envios."', lenguaje='".$_POST['lang']."',codigo='".$_POST['soln']."', hora=curTime( ) WHERE (id_equipos_concurso='".$_SESSION['idEquipoConcurso']."' AND id_problemas='".$_POST['idProblema']."')";
					mysql_query($query);
					//$_SESSION['cerror'] = trim($contents);
					header("Location: subir solucion.php?enviado=1&id=".$_POST['idProblema']);
                    
					} else {
						// Salida incorrecta
						
                        $query = "UPDATE solucion SET evaluacionCompilador='Salida incorrecta', nenvios='".$envios."', lenguaje='".$_POST['lang']."',codigo='".$_POST['soln']."', hora=curTime( ) WHERE (id_equipos_concurso='".$_SESSION['idEquipoConcurso']."' AND id_problemas='".$_POST['idProblema']."')";
					mysql_query($query);
					//$_SESSION['cerror'] = trim($contents);
					header("Location: subir solucion.php?enviado=1&id=".$_POST['idProblema']);                   
					}
				} 
            } else{
				header("Location: subir solucion.php?serror=1&id=".$_POST['idProblema']); // compiler server not running
		}
	*/


?>