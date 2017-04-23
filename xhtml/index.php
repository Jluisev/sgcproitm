<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>SGCPROITM</title>

    <!-- CSS -->
    <link href="style/css/transdmin.css" rel="stylesheet" type="text/css" media="screen"/>
    <link rel="stylesheet" href="style/css/codemirror.css">

    <!-- JavaScripts-->
    <script type="text/javascript" src="style/js/jquery.js"></script>
    <script type="text/javascript" src="style/js/jNice.js"></script>
</head>

<body>
<div id="wrapper">
    <!-- h1 tag stays for the logo, you can use the a tag for linking the index page -->
    <h1><a href="#"><span>Transdmin Light</span></a></h1>

    <!-- You can name the links with lowercase, they will be transformed to uppercase by CSS, we prefered to name them with uppercase to have the same effect with disabled stylesheet -->
    <ul id="mainNav">
        <li><a id="linkini2" href="inicio.php" class="active" target="frame_main">Inicio</a></li>
        <!-- Use the "active" class for the active menu item  -->
        <li><a id="link1" href="instrucciones.php" target="frame_main">Instrucciones</a></li>
        <!--<li><a id="link2" href="#">Preguntas Frecuentes</a></li>
       <li><a id="link3" href="#">Links</a></li>-->

        <?php

        include('conexion.php');
        $_SESSION['concurso'] = 0;//Temporal. Hay que hacer la consulta del concurso corriendo, según la fecha y la hora actual.
        /*connectdb();
        $query="select id_concurso from concurso where fecha_concurso=curdate()";
        $result=mysql_query($query);
        $fields=mysql_fetch_array($result);

        $_SESSION['concurso'] = $fields['id_concurso'];*/


        if (loggedin()) {
            echo '<li class="logout"><a href="logout.php">Logout</a></li>';
        } else {
            echo '<li class="logout"><a id="link4"  href="login.php" target="frame_main">Login</a></li>';
        }
        ?>

    </ul>
    <!-- // #end mainNav -->

    <div id="containerHolder">
        <div id="container">
            <div id="sidebar">
                <ul class="sideNav">
                    <li><a id="link5" href=<?php
                        if (loggedin() && $_SESSION['tipoUsuario'] == 'equipo') {
                            echo '"perfil.php"';
                        } else {
                            if (loggedin() && $_SESSION['tipoUsuario'] == 'juez') {
                                echo '"perfilJurado.php"';
                            } else {
                                echo '"login.php"';
                            }
                        }
                        ?> target="frame_main">Perfil</a></li>
                    <li><a id="link6" href="problemas.php" target="frame_main">Problemas</a></li>

                    <?php
                    if (loggedin() && $_SESSION['tipoUsuario'] == 'equipo') {
                        echo '<li><a id="link7" href="subir%20solucion.php" target="frame_main">Subir Soluci&oacute;n</a></li>';
                    }
                    ?>
                    <li><a id="link8" href="evaluaciones.php" target="frame_main">Evaluaciones</a></li>
                    <li><a id="link9" href="posiciones.php" target="frame_main">Tabla de Posiciones</a></li>

                </ul>
                <!-- // .sideNav -->
            </div>
            <!-- // #sidebar -->

            <!-- h2 stays for breadcrumbs -->
            <h2><a id="linkini" href="inicio.php" target="frame_main">Inicio</a> &raquo; <a id="actual" href="#"
                                                                                            class="active"></a></h2>

            <!-- //Esconde el texto in el link actual -->
            <script type="text/javascript">
                $(document).ready(function () {
                    $("#linkini, #linkini2").click(function () {
                        $("#actual").hide();
                        $('#sidebar .sideNav li a').removeClass("active");
                        $('#mainNav li a').removeClass("active");
                        $("#linkini2").addClass("active");
                    });
                });

                $(document).ready(function () {
                    $("#link1, #link2, #link3, #link4, #link5, #link6, #link7, #link8, #link9").click(function () {
                        $('#sidebar .sideNav li a').removeClass("active");
                        $('#mainNav li a').removeClass("active");
                        $("#actual").html($(this).html());
                        var a_href = $(this).attr('href');
                        var a_target = $(this).attr('target');
                        $("#actual").attr('href', a_href);
                        $("#actual").attr('target', a_target);
                        $("#actual").show();
                    });
                });

                $(document).ready(function () {
                    $("#link1, #link2, #link3, #link4, #link5, #link6, #link7, #link8, #link9").click(function () {

                        $(this).addClass("active");
                    });
                });
            </script>

            <div id="main2">
                <iframe src="inicio.php" name="frame_main" width=100% height=700></iframe>
            </div>
            <!-- // #main -->

            <div class="clear"></div>
        </div>
        <!-- // #container -->
    </div>
    <!-- // #containerHolder -->

    <p id="footer">SGCPROITM <br></p>
</div>
<!-- // #wrapper -->
</body>
</html>
