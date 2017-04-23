<?php
session_start();
	$_SESSION=array();
	if(isset($_COOKIE[session_name()])) {
		setcookie(session_name(),"",time()-42000,'/');
	}
	session_destroy();
    echo '<script type="text/javascript">alert("Se ha cerrado tu sesión.");</script>';
    sleep(2);
	header("Location: index.php?logout=1");
?>