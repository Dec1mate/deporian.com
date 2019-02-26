<?php
session_start();
require_once "database/Connection.php";
require_once "metodos.php";
require_once "twitter/TwitterAPIExchange.php";
$conexion = Connection::make();
date_default_timezone_set('Europe/Madrid');
if (isset($_SESSION['entidad'])) {
    $stmt = $conexion->prepare("SELECT * FROM ".$_SESSION['entidad']." WHERE dni = :dni");
    $parameters=[':dni'=>$_SESSION['dni']];
    $stmt->execute($parameters);
    if($_SESSION['entidad']=="jugador") {
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Jugador");
        $usuario = $stmt->fetch();
        $stmt2 = $conexion->prepare("SELECT logo FROM equipo WHERE nombre = :nombre");
        $parameters2 = [':nombre'=>$usuario->getEquipo()];
        $stmt2->execute($parameters2);
        $equipo = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        $stmt_res = $conexion->prepare("SELECT * FROM reserva WHERE equipo_nombre = :equipo");
        $stmt_par = $conexion->prepare("SELECT * FROM partido WHERE equipo_nombre_1 = :equipo OR equipo_nombre_2 = :equipo");
        $parameters = [':equipo'=>$usuario->getEquipo()];
        $entity = $usuario->getEquipo();
        $cant_ligas = $usuario->contarLigas();
    } else {
        $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Jugador");
        $usuario = $stmt->fetch();
        $stmt_res = $conexion->prepare("SELECT * FROM reserva WHERE arbitro_dni = :dni");
        $stmt_par = $conexion->prepare("SELECT * FROM partido WHERE arbitro_dni = :dni");
        $parameters = [':dni'=>$usuario->getDni()];
        $entity = $usuario->getDni();
    }
    $stmt_res->execute($parameters);
    $stmt_par->execute($parameters);
    $reservas = $stmt_res->fetchAll(PDO::FETCH_ASSOC);
    $partidos = $stmt_par->fetchAll(PDO::FETCH_ASSOC);

}
if (isset($_POST['opciones_jugador'])) {
    if ($_POST['opciones_jugador'] == "apuntarse") {
        $usuario->apuntarse();
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="CSS/stylesheet.css" rel="stylesheet" media="all" type="text/css">
    <link rel="stylesheet" href="CSS/theme1.css">
    <title>Document</title>
</head>
<body>
<div id="content">
    <div id="cabecera">
        <a href="index.php" ><h1>dep<img src="IMGs/balon.png" width="10px">rVereda</h1></a>
        <div id="idiomas">
            <form action="usuario.php" method="post">
                <img src="IMGs/spanish.png" id="castellano"><br>
                <img src="IMGs/uk.png" id="ingles">
                <input type="hidden" name="lengua">
            </form>
        </div>
        <form action="index.php" method="post">
            <input type="button" id="logout" value='<?= $i_boton_3 ?>'>
            <input type="hidden" name="cerrar">
        </form>
        <div id="user"><div><img id="userfoto" src='<?= $usuario->getFoto() ?>' /></div><?= $usuario->getNombre() ?></div>
    </div>
    <div id="info">
        <img src='<?= $usuario->getFoto() ?>' />
        <div id="datos">
            <h2><?= $usuario->getNombre() ?></h2>
            <hr>
            <?php if($_SESSION['entidad']=="jugador"):?>
            <h3><?= $usuario->getEquipo() ?> <img src='<?= $equipo[0]['logo'] ?>' height="20px"/></h3>
            <?php endif;?>
        </div>
        <!-- AQUI VA EL CALENDARIO -->
        <div id="calendario">

        </div>
        <div id="<?= $_SESSION['entidad'] ?>">
            <?php if($_SESSION['entidad']=="jugador") {
                include 'jugador.php';
            } else if($_SESSION['entidad']=='arbitro') {
                include 'arbitro.php';
            }
            ?>
        </div>
        <script>
            cargarCalendario();
            document.getElementById('logout').onclick = cerrarSesion;
            document.getElementsByTagName('img')[1].onclick = cambiarIdioma;
            document.getElementsByTagName('img')[2].onclick = cambiarIdioma;

            function cargarCalendario() {
                let httpRequest = obtainXMLHttpRequest();
                httpRequest.open('POST', 'calendario_partidos.php', true);
                httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                httpRequest.onreadystatechange = function() {
                    if(httpRequest.readyState===4) {
                        if(httpRequest.status === 200) {
                            document.getElementById('calendario').innerHTML = httpRequest.responseText;
                            for (let i = 0; i<document.getElementsByClassName('partido').length; i++) {
                                document.getElementsByClassName('partido')[i].onclick = pagePartido;
                            }
                            document.getElementsByTagName('button')[0].onclick = cambioMes;
                            document.getElementsByTagName('button')[1].onclick = cambioMes;
                        }
                    }
                }
                let data = new Datos('<?= $entity ?>', "inicial");
                data = JSON.stringify(data);
                httpRequest.send('accion='+data);
            }

            function pagePartido(event) {
                if(event.target.tagName=="TD") {
                    event.target.getElementsByTagName('form')[0].submit();
                } else if(event.target.tagName=="DIV") {
                    event.target.parentNode.getElementsByTagName('form')[0].submit();
                } else if(event.target.tagName=="IMG" || event.target.tagName=="I") {
                    event.target.parentNode.parentNode.getElementsByTagName('form')[0].submit();
                }

            }

            function cambioMes(event) {
                let data;
                let httpRequest = obtainXMLHttpRequest();
                httpRequest.open('POST', 'calendario_partidos.php', true);
                httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                httpRequest.onreadystatechange = function() {
                    if(httpRequest.readyState===4) {
                        if(httpRequest.status === 200) {
                            document.getElementById('calendario').innerHTML = httpRequest.responseText;
                            for (let i = 0; i<document.getElementsByClassName('partido').length; i++) {
                                document.getElementsByClassName('partido')[i].onclick = pagePartido;
                            }
                            document.getElementsByTagName('button')[0].onclick = cambioMes;
                            document.getElementsByTagName('button')[1].onclick = cambioMes;
                        }
                    }
                }
                if(event.target.id==="anterior") {
                    data = new Datos('<?= $entity ?>', "anterior");
                } else {
                    data = new Datos('<?= $entity ?>', "siguiente");
                }
                data = JSON.stringify(data);
                httpRequest.send('accion='+data);
            }

            function Datos(entity, opcion) {
                this.entity = entity;
                this.opcion = opcion;
            }

            function obtainXMLHttpRequest() {
                let httpRequest;
                if(window.XMLHttpRequest) {
                    httpRequest = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    try {
                        httpRequest = new ActiveXObject('MSXML2.XMLHTTP');
                    } catch(e) {
                        try {
                            httpRequest = new ActiveXObject('Microsoft.XMLHTTP');
                        } catch(e) {}
                    }
                }
                if (!httpRequest) {
                    return false;
                } else {
                    return httpRequest;
                }
            }

            function cambiarIdioma(event) {
                document.getElementsByTagName('input')[0].value = event.target.id;
                document.getElementsByTagName('form')[0].submit();
            }

            function cerrarSesion(event) {
                document.getElementsByTagName('input')[2].value = event.target.id;
                document.getElementsByTagName('form')[1].submit();
            }
        </script>