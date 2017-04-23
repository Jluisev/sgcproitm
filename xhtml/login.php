<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SGCPROITM</title>
<!-- CSS -->
<link href="style/css/transdmin.css" rel="stylesheet" type="text/css" media="screen" />   
<!-- JavaScripts-->
<script type="text/javascript" src="style/js/jquery.js"></script>
<script type="text/javascript" src="style/js/jNice.js"></script>
</head>
<body>
    <?php

    require_once('conexion.php');
    connectdb();
	if(loggedin())
		header("Location: index.php");
	else if($_SERVER["REQUEST_METHOD"] == "POST") {
        $username=test_input($_POST['usuario']);
		$username = mysql_real_escape_string($username);
        $password=test_input($_POST['password']);
		$password = mysql_real_escape_string($password);
        if(trim($username) === "" or trim($password) === "")
				header("Location: login.php?derror=1"); // empty entry
			else { //else trim
				// code to login the user and start a session
                
				connectdb();
                if($_POST['tipoUsuario']==='equipo'){
                $query = "SELECT usuario, contrasena FROM usuarios_equipos WHERE usuario='".$username."'";
                $_SESSION['tipoUsuario']='equipo';
                }else{
                   $query = "SELECT usuario, contrasena FROM usuarios_jurado WHERE usuario='".$username."'";
                $_SESSION['tipoUsuario']='juez'; 
                }
				
				$result = mysql_query($query);
				$fields = mysql_fetch_array($result);
				if($password === $fields['contrasena']) {
					$_SESSION['username'] = $username;
                    if($_SESSION['tipoUsuario']==='equipo'){
					setSessionVariables();
                    }
                    else{
                    setSessionJudge();
                    }
                    
                    echo "<script type='text/javascript'>window.parent.location.reload(true)</script>";
                } else{
                    //header("Location: login.php?error=1");
                    echo 'error'; 
                }
			}//else trim
    }
    ?>
    
    <br><br>
    <div id="main">
    <form action=login.php method="post" class="jNice">
<fieldset>
    <label>&nbsp;&nbsp;&nbsp;Seleccione el tipo de usuario:</label><br><br>
        <input type="radio" name="tipoUsuario" value="equipo" checked="checked">Equipo<br><br>
<input type="radio" name="tipoUsuario" value="juez">Juez<br><br>
    
        <label>&nbsp;&nbsp;&nbsp;Usuario:</label>
        <input type="text" class="text-medium" name="usuario"/><br><br>
        <label>Password:</label>
        <input type="password" class="text-medium" name="password"/>
    <br><br>
    <input type="submit" value="Login"/>
        </fieldset>
    </form>
    </div>
</body>
</html>