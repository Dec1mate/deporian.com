<?php
session_start();
date_default_timezone_set('Europe/Madrid');
require_once "metodos.php";
if (isset($_SESSION['entidad'])) {
    $stmt = $conexion->prepare("SELECT * FROM ".$_SESSION['entidad']." WHERE dni = :dni");
    $parameters=[':dni'=>$_SESSION['dni']];
    $stmt->execute($parameters);
    if($_SESSION['entidad']=="jugador") {
        $usuario = $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Jugador");
        $stmt2 = $conexion->prepare("SELECT logo FROM equipo WHERE nombre = :nombre");
        $parameters2 = [':nombre'=>$usuario[0]->getEquipo()];
        $stmt2->execute($parameters2);
        $equipo = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $usuario = $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Arbitro");
    }
}
if(isset($_POST['cambios'])) {
    if($_POST['new_name']!="") {
        $usuario[0]->updateNombre($_POST['new_name']);
    }
    if($_POST['new_age']!="") {
        $usuario[0]->updateEdad($_POST['new_age']);
    }
    if($_POST['new_height']!="") {
        $usuario[0]->updateAltura($_POST['new_height']);
    }
    if($_FILES['new_photo']['size']!=0) {
        $usuario[0]->updateFoto($_FILES['new_photo']);
    }
    if($_POST['new_pass']!="") {
        $usuario[0]->updateContrasenya($_POST['new_pass']);
    }
    header('Location: usuario.php');
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
    <title>Document</title>
</head>
<body>
<div id="content">
    <div id="cabecera">
        <a href="index.php" ><h1>dep<img src="IMGs/balon.png" width="10px">rVereda</h1></a>
        <div id="idiomas">
            <form action="modificar.php" method="post">
                <img src="IMGs/spanish.png" id="castellano"><br>
                <img src="IMGs/uk.png" id="ingles">
                <input type="hidden" name="lengua">
            </form>
        </div>
        <form action="index.php" method="post">
            <input type="button" id="logout" value='<?= $i_boton_3 ?>'>
            <input type="hidden" name="cerrar">
        </form>
        <a href="usuario.php" id="user"><div><img src='<?= $usuario[0]->getFoto() ?>' /></div><?= $usuario[0]->getNombre() ?></a>
    </div>
    <div id="form_mod">
        <form action="modificar.php" method="post" enctype="multipart/form-data">
            <p><?= $i_nombre ?></p>
            <input type="text" name="new_name">
            <p><?= $i_edad ?></p>
            <input type="text" name="new_age">
            <p><?= $i_altura ?></p>
            <input type="text" name="new_height">
            <p><?= $i_logo ?></p>
            <input type="file" name="new_photo">
            <p><?= $i_new_pass ?></p>
            <input type="password" name="new_pass"><br>
            <input type="submit" value="<?= $i_modificar_boton ?>"><hr>
            <input type="button" value="<?= $i_eliminar_boton ?>">

            <input type="hidden" name="cambios">
        </form>
    </div>
    <script>
        document.getElementById('logout').onclick = cerrarSesion;
        document.getElementsByTagName('img')[1].onclick = cambiarIdioma;
        document.getElementsByTagName('img')[2].onclick = cambiarIdioma;
        document.getElementsByTagName('input')[8].onclick = eliminarCuenta;

        function eliminarCuenta() {
            document.getElementsByTagName('form')[2].action = "index.php";
            document.getElementsByTagName('input')[9].name = "eliminar";
            document.getElementsByTagName('form')[2].submit();
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
</div>
</body>
</html>