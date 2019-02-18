<?php
session_start();
require_once "metodos.php";
$stmt = $conexion->prepare("SELECT nombre FROM equipo");
$stmt->execute();
$equipos_form = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(isset($_SESSION['dni'])) {
    $stmt = $conexion->prepare("SELECT * FROM ".$_SESSION['entidad']." WHERE dni = :dni");
    $parameters = [':dni'=>$_SESSION['dni']];
    $stmt->execute($parameters);
    $usuario = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
if (isset($_POST['orden'])) {
    if ($_POST['orden'] == 'login') {        //INICIAR SESION
        $stmt = $conexion->prepare("SELECT * FROM " . $_POST['entidad'] . " WHERE dni = :dni");
        $parameters = [':dni' => $_POST['li_dni']];
        $stmt->execute($parameters);
        $usuario = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($usuario)) {
            echo "ERROR dni"; //DNI ERRONEO
        } else if (password_verify($_POST['li_pass'], $usuario[0]['contrasenya'])) {
            $_SESSION['dni'] = $usuario[0]['dni'];
            $_SESSION['entidad'] = $_POST['entidad'];
        } else {
            echo "ERROR pass"; //CONTRASEÑA ERRONEA
        }

    } else if ($_POST['orden'] == 'signup') {           //REGISTRARSE
        if ($_POST['entidad'] == "equipo") {
            $stmt_pre = $conexion->prepare("SELECT nombre FROM equipo WHERE nombre = :nombre");
            $parameters_pre = [':nombre' => $_POST['su_name']];
            $stmt_pre->execute($parameters_pre);
            $team = $stmt_pre->fetchAll(PDO::FETCH_ASSOC);
            if (!$team) {
                $stmt = $conexion->prepare("INSERT INTO equipo(nombre, logo, fecha) VALUES (:nombre, :logo, CURRENT_TIME());");
                if ($_FILES['su_file']['size'] == 0) {
                    $rutaImagen = "IMGs\generic.png";
                } else {
                    $rutaImagen = 'IMGs\equipos\\' . $_FILES['su_file']['name'];
                    if (!((strpos($_FILES['su_file']['type'], 'png') || strpos($_FILES['su_file']['type'], 'jpg') || strpos($_FILES['su_file']['type'], 'jpeg')))) {
                        echo("La extension no es correcta.");
                    } else if (is_file($rutaImagen) === true) {
                        $idUnico = time();
                        $nombreArchivo = $idUnico . '_' . $_FILES['su_file']['name'];
                        $rutaImagen = 'IMGs\equipos\\' . $nombreArchivo;
                    }
                    move_uploaded_file($_FILES['su_file']['tmp_name'], $rutaImagen);
                }
                $parameters = [':nombre' => $_POST['su_name'], ':logo' => $rutaImagen];
            }
            $stmt->execute($parameters);

        } else {
            $stmt_pre = $conexion->prepare("SELECT * FROM ".$_POST['entidad']." WHERE :dni = dni");
            $parameters_pre = [':dni' => $_POST['su_dni']];
            $stmt_pre->execute($parameters_pre);
            $usuario = $stmt_pre->fetchAll(PDO::FETCH_ASSOC);
            if (empty($usuario)) {
                if($_POST['entidad'] == "jugador") {
                    $stmt = $conexion->prepare("INSERT INTO jugador(nombre, dni, equipo, edad, altura, foto, contrasenya) VALUES (:nombre, :dni, :equipo, :edad, :altura, :foto, :contrasenya)");
                    $carpeta = "jugadores";
                } else {
                    $stmt = $conexion->prepare("INSERT INTO arbitro(nombre, dni, edad, altura, foto, contrasenya) VALUES (:nombre, :dni, :edad, :altura, :foto, :contrasenya)");
                    $carpeta = "arbitros";
                }
                if ($_FILES['su_file']['size'] != 0) {
                    $rutaImagen = 'IMGs\\'.$carpeta.'\\' . $_FILES['su_file']['name'];
                    if (!((strpos($_FILES['su_file']['type'], 'png') || strpos($_FILES['su_file']['type'], 'jpg') || strpos($_FILES['su_file']['type'], 'jpeg')))) {
                        echo("La extension no es correcta.");
                    } else if (is_file($rutaImagen) === true) {
                        $idUnico = time();
                        $nombreArchivo = $idUnico . '_' . $_FILES['su_file']['name'];
                        $rutaImagen = 'IMGs\\'.$carpeta.'\\' . $nombreArchivo;
                    }
                    move_uploaded_file($_FILES['su_file']['tmp_name'], $rutaImagen);
                } else {
                    $rutaImagen = "IMGs\generic.png";
                }
                if($_POST['entidad'] == "jugador") {
                    $parameters = [':nombre' => $_POST['su_name'], ':dni' => $_POST['su_dni'], ':equipo' => $_POST['su_team'], ':edad' => $_POST['su_age'], ':altura' => $_POST['su_height'], ':foto' => $rutaImagen, ':contrasenya' => password_hash($_POST['su_pass'], PASSWORD_DEFAULT, ['cost' => 10])];
                } else {
                    $parameters = [':nombre' => $_POST['su_name'], ':dni' => $_POST['su_dni'], ':edad' => $_POST['su_age'], ':altura' => $_POST['su_height'], ':foto' => $rutaImagen, ':contrasenya' => password_hash($_POST['su_pass'], PASSWORD_DEFAULT, ['cost' => 10])];
                }
                $stmt->execute($parameters);
                $usuario[0]['nombre'] = $_POST['su_name'];
                $usuario[0]['foto'] = $rutaImagen;
                $_SESSION['dni'] = $_POST['su_dni'];
                $_SESSION['entidad'] = $_POST['entidad'];
            }
        }
    }
}
if(isset($_POST['eliminar'])) {
    $stmt = $conexion->prepare("DELETE FROM ".$_SESSION['entidad']." WHERE :dni = dni");
    $parameters = [':dni'=>$_SESSION['dni']];
    $stmt->execute($parameters);
    $idioma = $_SESSION['idioma'];
    session_destroy();
    session_start();
    $_SESSION['idioma'] = $idioma;
}

$stmt_partidos = $conexion->prepare("select * from equipo E, pertenece P, liga L where P.liga_edicion = L.edicion and E.nombre = P.equipo_nombre and L.edicion = (select MAX(edicion) from liga) order by puntos DESC");
$stmt_partidos->execute();
$teams_liga = $stmt_partidos->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="CSS/stylesheet.css" rel="stylesheet" media="all" type="text/css">
    <title>deporVereda</title>
</head>
<body>
    <div id="content">
        <div id="cabecera">
            <h1>dep<img src="IMGs/balon.png" width="10px">rVereda</h1>
            <div id="idiomas">
                <form action="index.php" method="post">
                    <img src="IMGs/spanish.png" id="castellano"><br>
                    <img src="IMGs/uk.png" id="ingles">
                    <input type="hidden" name="lengua">
                </form>
            </div>
            <?php if(!isset($_SESSION['dni'])): ?>
                <input type="button" id="signup" value='<?= $i_boton_2 ?>'>
                <input type="button" id="login" value='<?= $i_boton_1 ?>'>
            <?php elseif (isset($_SESSION['dni'])): ?>
                <form action="index.php" method="post">
                    <input type="button" id="logout" value='<?= $i_boton_3 ?>'>
                    <input type="hidden" name="cerrar">
                </form>
                <a href="usuario.php" id="user"><div><img src='<?= $usuario[0]['foto'] ?>' /></div><?= $usuario[0]['nombre'] ?></a>
            <?php endif; ?>
        </div>
        <?php if(!empty($teams_liga)) :?>
        <table id="liga">
            <tr><th colspan="3">LIGA ACTUAL</th></tr>
            <tr><td colspan="2">Equipo</td><td>Puntos</td></tr>
            <?php foreach ($teams_liga as $team):?>
            <tr><td><img src="<?= $team['logo'] ?>"></td><td><?= $team['nombre'] ?></td><td><?= $team['puntos'] ?></td></tr>
            <?php endforeach; ?>
        </table>
        <?php else: ?>
        <div><img src="IMGs/exterior.jpg" /></div>
        <?php endif; ?>
        <div id="texto_index">
            <h1>Bienvenido a DeporIan</h1>
            <h2>La pagina de gestion de ligas donde tus sueños se hacen realidad</h2>
            <p>Lorem ipsum dolor sit amet consectetur adipiscing elit a congue tortor, nostra fames suspendisse condimentum gravida elementum torquent rhoncus blandit ut at, non justo tellus aptent fermentum litora tristique ultrices fusce. Inceptos etiam blandit in penatibus ut vestibulum felis, ligula praesent scelerisque tincidunt turpis vitae. Sociis lacus fames congue blandit molestie primis est lobortis non, viverra quisque duis etiam facilisi conubia libero eget.</p>
            <p>Congue enim ullamcorper erat volutpat quam ad parturient eget eros nisl, sociis eleifend dignissim venenatis quis donec mauris interdum proin vel natoque, purus est potenti magna velit facilisi nec consequat feugiat. Mus vehicula fusce litora gravida condimentum himenaeos facilisis, massa viverra scelerisque erat mattis posuere, nisl sapien habitant nec est libero. Ante ultricies diam ad morbi urna class quisque in dapibus nibh integer pulvinar aliquam pellentesque vel, sollicitudin curabitur ornare felis penatibus tincidunt eleifend maecenas ullamcorper feugiat curae sociosqu cras aptent.</p>
            <br><h2>Tenemos los mejores campos!</h2>
            <img width="49%" src="IMGs/exterior.jpg"/>
            <img width="49%" src="IMGs/interior.jpg"/>
        </div>
    </div>
    <script>
        if (document.getElementById('logout')==null) {
            document.getElementById('signup').onclick = crearForm;
            document.getElementById('login').onclick = crearForm;
        }
        if (document.getElementById('logout')!=null) {
            document.getElementById('logout').onclick = cerrarSesion;
        }
        document.getElementsByTagName('img')[1].onclick = cambiarIdioma;
        document.getElementsByTagName('img')[2].onclick = cambiarIdioma;
        document.body.onkeyup = acciones;

        function acciones(event) {
            if(event.key === "Escape") {
                if(document.getElementsByTagName('form')[1]) {
                    quitarForm();
                }
            }
        }

        function crearForm(event) {
            document.getElementById('signup').onclick = null;
            document.getElementById('login').onclick = null;
            if (document.getElementsByTagName('form').length===1) {
                let form = document.createElement('form');
                form.setAttribute('class', 'formulario');
                form.setAttribute('action', 'index.php');
                form.setAttribute('method', 'post');
                form.setAttribute('enctype', 'multipart/form-data');
                let button_1 = document.createElement('input');
                button_1.setAttribute('type', 'button');
                button_1.setAttribute('value', '<?= $i_boton_1 ?>');
                button_1.setAttribute('class', 'opciones');
                let button_2 = document.createElement('input');
                button_2.setAttribute('type', 'button');
                button_2.setAttribute('value', '<?= $i_boton_2 ?>');
                button_2.setAttribute('class', 'opciones');
                let input_h = document.createElement('input');
                input_h.setAttribute('type', 'hidden');
                input_h.setAttribute('id', 'oculto');
                input_h.setAttribute('name', 'orden');
                form.appendChild(button_1);
                form.appendChild(button_2);
                form.appendChild(input_h);
                document.body.appendChild(form);
                document.getElementsByClassName('opciones')[0].onclick = log_in;
                document.getElementsByClassName('opciones')[1].onclick = sign_up;
                if (event.target.id === "login") {
                    log_in();
                } else if (event.target.id === "signup") {
                    sign_up();
                }
                let separador = document.createElement('div');
                separador.setAttribute('id', 'difuminador');
                document.body.appendChild(separador);
                document.getElementById('difuminador').onclick = quitarForm;
            }
        }

        function sign_up() {
            document.getElementsByClassName('opciones')[0].onclick = log_in;
            document.getElementsByClassName('opciones')[1].onclick = null;
            document.getElementsByClassName('opciones')[0].setAttribute('id', 'inactivo');
            document.getElementsByClassName('opciones')[1].setAttribute('id', 'activo');
            document.getElementById('oculto').value = "signup";
            if (document.getElementById('log_in')) {
                document.getElementsByTagName('form')[1].removeChild(document.getElementById('log_in'));
            }
            let div_su = document.createElement('div');
            div_su.setAttribute('id', 'sign_up');

            let seleccion_1 = document.createElement('input');
            seleccion_1.setAttribute('type', 'radio');
            seleccion_1.setAttribute('name', 'entidad');
            seleccion_1.setAttribute('value', 'equipo');
            seleccion_1.setAttribute('checked', 'true');

            let seleccion_2 = document.createElement('input');
            seleccion_2.setAttribute('type', 'radio');
            seleccion_2.setAttribute('name', 'entidad');
            seleccion_2.setAttribute('value', 'jugador');

            let seleccion_3 = document.createElement('input');
            seleccion_3.setAttribute('type', 'radio');
            seleccion_3.setAttribute('name', 'entidad');
            seleccion_3.setAttribute('value', 'arbitro');

            let opcion_1 = document.createTextNode('<?= $i_opcion_1 ?>');
            let opcion_2 = document.createTextNode('<?= $i_opcion_2 ?>');
            let opcion_3 = document.createTextNode('<?= $i_opcion_3 ?>');
            div_su.appendChild(seleccion_1);
            div_su.appendChild(opcion_1);
            div_su.appendChild(seleccion_2);
            div_su.appendChild(opcion_2);
            div_su.appendChild(seleccion_3);
            div_su.appendChild(opcion_3);

            let p_1 = document.createElement('p');
            let text_1 = document.createTextNode('<?= $i_nombre ?>');
            p_1.appendChild(text_1);
            let input_1 = document.createElement('input');
            input_1.setAttribute('type', 'text');
            input_1.setAttribute('name', 'su_name');

            let p_7 = document.createElement('p');
            let text_7 = document.createTextNode('<?= $i_equipo ?>');
            p_7.appendChild(text_7);
            p_7.setAttribute('hidden', 'true');
            p_7.setAttribute('class', 'opcional_2');

            let input_7 = document.createElement('div');
            input_7.setAttribute('class', 'opcional_2');
            input_7.setAttribute('hidden', 'true');

            let httpRequest = obtainXMLHttpRequest();
            httpRequest.open('POST', 'registro.php', true);
            httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            httpRequest.onreadystatechange=function() {
                if(httpRequest.readyState===4) {
                    if (httpRequest.status === 200) {
                        input_7.innerHTML = httpRequest.responseText;
                    }
                }
            }
            httpRequest.send('accion=desplegable');

            let p_8 = document.createElement('p');
            let text_8 = document.createTextNode('<?= $i_dni ?>');
            p_8.appendChild(text_8);
            p_8.setAttribute('hidden', 'true');
            p_8.setAttribute('class', 'opcional');
            let input_8 = document.createElement('input');
            input_8.setAttribute('type', 'text');
            input_8.setAttribute('name', 'su_dni');
            input_8.setAttribute('hidden', 'true');
            input_8.setAttribute('class', 'opcional');

            let p_2 = document.createElement('p');
            let text_2 = document.createTextNode('<?= $i_edad ?>');
            p_2.appendChild(text_2);
            p_2.setAttribute('hidden', 'true');
            p_2.setAttribute('class', 'opcional');
            let input_2 = document.createElement('input');
            input_2.setAttribute('type', 'text');
            input_2.setAttribute('name', 'su_age');
            input_2.setAttribute('hidden', 'true');
            input_2.setAttribute('class', 'opcional');

            let p_3 = document.createElement('p');
            let text_3 = document.createTextNode('<?= $i_altura ?>');
            p_3.appendChild(text_3);
            p_3.setAttribute('hidden', 'true');
            p_3.setAttribute('class', 'opcional');
            let input_3 = document.createElement('input');
            input_3.setAttribute('type', 'text');
            input_3.setAttribute('name', 'su_height');
            input_3.setAttribute('hidden', 'true');
            input_3.setAttribute('class', 'opcional');

            let p_4 = document.createElement('p');
            let text_4 = document.createTextNode('<?= $i_logo ?>');
            p_4.appendChild(text_4);
            let input_4 = document.createElement('input');
            input_4.setAttribute('type', 'file');
            input_4.setAttribute('value', 'Añadir archivo');
            input_4.setAttribute('name', 'su_file');
            input_4.setAttribute('id', 'file');
            let label_4 = document.createElement('label');
            label_4.setAttribute('for', 'file');
            let label_text_4 = document.createTextNode('<?= $i_archivo ?>');
            label_4.appendChild(label_text_4);

            let p_5 = document.createElement('p');
            let text_5 = document.createTextNode('<?= $i_pass ?>');
            p_5.appendChild(text_5);
            p_5.setAttribute('hidden', 'true');
            p_5.setAttribute('class', 'passwds');
            let input_5 = document.createElement('input');
            input_5.setAttribute('type', 'password');
            input_5.setAttribute('name', 'su_pass');
            input_5.setAttribute('hidden', 'true');
            input_5.setAttribute('class', 'passwds');

            let p_6= document.createElement('p');
            let text_6 = document.createTextNode('<?= $i_pass_conf ?>');
            p_6.appendChild(text_6);
            p_6.setAttribute('hidden', 'true');
            p_6.setAttribute('class', 'passwds');
            let input_6 = document.createElement('input');
            input_6.setAttribute('type', 'password');
            input_6.setAttribute('name', 'su_pass_conf');
            input_6.setAttribute('hidden', 'true');
            input_6.setAttribute('class', 'passwds');

            let salto = document.createElement('br');
            let submit = document.createElement('input');
            submit.setAttribute('type', 'submit');
            submit.setAttribute('value', '<?= $i_submit_2 ?>');
            div_su.appendChild(p_1);
            div_su.appendChild(input_1);
            div_su.appendChild(p_7);
            div_su.appendChild(input_7);
            div_su.appendChild(p_8);
            div_su.appendChild(input_8);
            div_su.appendChild(p_2);
            div_su.appendChild(input_2);
            div_su.appendChild(p_3);
            div_su.appendChild(input_3);
            div_su.appendChild(p_4);
            div_su.appendChild(input_4);
            div_su.appendChild(label_4);
            div_su.appendChild(p_5);
            div_su.appendChild(input_5);
            div_su.appendChild(p_6);
            div_su.appendChild(input_6);
            div_su.appendChild(salto);
            div_su.appendChild(submit);
            document.getElementsByClassName('formulario')[0].appendChild(div_su);
            document.getElementsByName('entidad')[0].checked = true;
            document.getElementsByName('su_dni')[0].onblur = comprobarDni;
            for (let i = 0; i<3; i++) {
                document.getElementsByName('entidad')[i].onchange = cambiosForm;
            }

        }

        function log_in() {
            document.getElementsByClassName('opciones')[0].onclick = null;
            document.getElementsByClassName('opciones')[1].onclick = sign_up;
            document.getElementsByClassName('opciones')[1].setAttribute('id', 'inactivo');
            document.getElementsByClassName('opciones')[0].setAttribute('id', 'activo');
            document.getElementById('oculto').value = "login";
            if (document.getElementById('sign_up')) {
                document.getElementsByTagName('form')[1].removeChild(document.getElementById('sign_up'));
            }
            let div_li = document.createElement('div');
            div_li.setAttribute('id', 'log_in');

            let seleccion_1 = document.createElement('input');
            seleccion_1.setAttribute('type', 'radio');
            seleccion_1.setAttribute('name', 'entidad');
            seleccion_1.setAttribute('value', 'jugador');
            //seleccion_1.setAttribute('checked', 'true');
            let seleccion_2 = document.createElement('input');
            seleccion_2.setAttribute('type', 'radio');
            seleccion_2.setAttribute('name', 'entidad');
            seleccion_2.setAttribute('value', 'arbitro');
            let opcion_1 = document.createTextNode('<?= $i_opcion_2 ?>');
            let opcion_2 = document.createTextNode('<?= $i_opcion_3 ?>');
            div_li.appendChild(seleccion_1);
            div_li.appendChild(opcion_1);
            div_li.appendChild(seleccion_2);
            div_li.appendChild(opcion_2);

            let p_1 = document.createElement('p');
            let text_1 = document.createTextNode('<?= $i_dni ?>');
            p_1.appendChild(text_1);
            let input_1 = document.createElement('input');
            input_1.setAttribute('type', 'text');
            input_1.setAttribute('name', 'li_dni');

            let p_2 = document.createElement('p');
            let text_2 = document.createTextNode('<?= $i_pass ?>');
            p_2.appendChild(text_2);
            let input_2 = document.createElement('input');
            input_2.setAttribute('type', 'password');
            input_2.setAttribute('name', 'li_pass');

            let p_3 = document.createElement('p');
            let text_3 = document.createTextNode('<?= $i_pass_conf ?>');
            p_3.appendChild(text_3);
            let input_3 = document.createElement('input');
            input_3.setAttribute('type', 'password');
            input_3.setAttribute('name', 'li_pass_conf');

            let salto = document.createElement('br');
            let submit = document.createElement('input');
            submit.setAttribute('type', 'submit');
            submit.setAttribute('value', '<?= $i_submit_1 ?>');
            div_li.appendChild(p_1);
            div_li.appendChild(input_1);
            div_li.appendChild(p_2);
            div_li.appendChild(input_2);
            div_li.appendChild(p_3);
            div_li.appendChild(input_3);
            div_li.appendChild(salto);
            div_li.appendChild(submit);
            document.getElementsByClassName('formulario')[0].appendChild(div_li);
            document.getElementsByName('entidad')[0].checked = true;
            for (let i = 0; i<2; i++) {
                document.getElementsByName('entidad')[i].onchange = cambiosForm;
            }
        }

        function cambiosForm(event) {
            if (document.getElementById('sign_up')) {
                document.getElementsByName('su_dni')[0].style.backgroundColor = "white";
                if (event.target.value === "equipo") {
                    for (let i=0; i<6; i++) {
                        document.getElementsByClassName('opcional')[i].hidden = true;
                    }
                    for (let i=0; i<2; i++) {
                        document.getElementsByClassName('opcional_2')[i].hidden = true;
                    }
                    for (let i=0; i<4; i++) {
                        document.getElementsByClassName('passwds')[i].hidden = true;
                    }
                } else if (event.target.value === "jugador") {
                    for (let i=0; i<6; i++) {
                        document.getElementsByClassName('opcional')[i].hidden = false;
                    }
                    for (let i=0; i<2; i++) {
                        document.getElementsByClassName('opcional_2')[i].hidden = false;
                    }
                    for (let i=0; i<4; i++) {
                        document.getElementsByClassName('passwds')[i].hidden = false;
                    }
                } else {
                    for (let i=0; i<6; i++) {
                        document.getElementsByClassName('opcional')[i].hidden = false;
                    }
                    for (let i=0; i<2; i++) {
                        document.getElementsByClassName('opcional_2')[i].hidden = true;
                    }
                    for (let i=0; i<4; i++) {
                        document.getElementsByClassName('passwds')[i].hidden = false;
                    }
                }
            }
        }

        function comprobarDni(event) {
            let httpRequest = obtainXMLHttpRequest();
            let existe = false;
            httpRequest.open('POST', 'registro.php', true);
            httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            httpRequest.onreadystatechange=function() {
                if(httpRequest.readyState===4) {
                    if (httpRequest.status === 200) {
                        let jsonFile =httpRequest.responseText;
                        let datos = JSON.parse(jsonFile);
                        for (let i=0; i<datos.length; i++) {
                            if (datos[i] === event.target.value) {
                                existe = true;
                            }
                        }
                        if (existe===true) {
                            event.target.style.backgroundColor = "coral";
                        } else {
                            event.target.style.backgroundColor = "lightgreen";
                        }
                    }
                }
            }
            httpRequest.send('accion=comprobar');
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

        function quitarForm() {
            document.body.removeChild(document.getElementsByTagName('form')[1]);
            document.body.removeChild(document.getElementById('difuminador'));
            document.getElementById('signup').onclick = crearForm;
            document.getElementById('login').onclick = crearForm;
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
</body>
</html>