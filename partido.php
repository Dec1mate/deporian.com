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
if(isset($_POST['fecha']) || isset($_SESSION['fecha'])) {
    if(isset($_POST['fecha'])) {
        $_SESSION['fecha'] = $_POST['fecha'];
    }
    if(isset($_POST['goles1'])) {
        $stmt_partido = $conexion->prepare("UPDATE partido SET goles_1 = :goles1, goles_2 = :goles2 WHERE (equipo_nombre_1 = :equipo1 AND equipo_nombre_2 = :equipo2)");
        $parameters_partido = [':goles1'=>$_POST['goles1'], ':goles2'=>$_POST['goles2'], ':equipo1'=>$_POST['equipo1'], ':equipo2'=>$_POST['equipo2']];
        $stmt_partido->execute($parameters_partido);
        $stmt_puntos = $conexion->prepare("UPDATE equipo SET puntos = puntos + :cant WHERE nombre = :equipo");
        if($_POST['goles1']>$_POST['goles2']) {
            $parameters_puntos = [':cant'=>3, ':equipo'=>$_POST['equipo1']];
            $stmt_puntos->execute($parameters_puntos);
        } else if($_POST['goles1']<$_POST['goles2']) {
            $parameters_puntos = [':cant'=>3, ':equipo'=>$_POST['equipo2']];
            $stmt_puntos->execute($parameters_puntos);
        } else {
            $parameters_puntos1 = [':cant'=>1, ':equipo'=>$_POST['equipo1']];
            $stmt_puntos->execute($parameters_puntos1);
            $parameters_puntos2 = [':cant'=>1, ':equipo'=>$_POST['equipo2']];
            $stmt_puntos->execute($parameters_puntos2);
        }
        $goles_equipo_1 = $_POST['goles_jugadores_1'];
        $goles_equipo_2 = $_POST['goles_jugadores_2'];
        $stmt2 = $conexion->prepare("SELECT * FROM jugador WHERE equipo = :equipo ORDER BY nombre");
        $parameters1 = [':equipo'=>$_POST['equipo1']];
        $parameters2 = [':equipo'=>$_POST['equipo2']];
        $stmt2->execute($parameters1);
        $jugadores1 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        $stmt2->execute($parameters2);
        $jugadores2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        $stmt_goles = $conexion->prepare("UPDATE jugador SET num_goles = num_goles + :cant WHERE dni = :dni");
        for ($i=0; $i<count($goles_equipo_1); $i++) {
            $parameters_goles = [':cant'=>$goles_equipo_1[$i], ':dni'=>$jugadores1[$i]['dni']];
            $stmt_goles->execute($parameters_goles);
        }
        for ($j=0; $j<count($goles_equipo_2); $j++) {
            $parameters_goles = [':cant'=>$goles_equipo_2[$j], ':dni'=>$jugadores2[$j]['dni']];
            $stmt_goles->execute($parameters_goles);
        }

    }
    if($_SESSION['entidad'] == 'jugador') {
        $stmt = $conexion->prepare("SELECT * FROM partido WHERE (equipo_nombre_1 = :equipo OR equipo_nombre_2 = :equipo) AND fecha = :fecha");
        $parameters = [':equipo'=>$usuario[0]->getEquipo(), ':fecha'=>$_SESSION['fecha']];
    } else {
        $stmt = $conexion->prepare("SELECT * FROM partido WHERE arbitro_dni = :arbitro AND fecha = :fecha");
        $parameters = [':arbitro'=>$usuario[0]->getDni(), 'fecha'=>$_SESSION['fecha']];
    }
    $stmt->execute($parameters);
    $partido = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt_eq = $conexion->prepare("SELECT * FROM equipo WHERE nombre = :nombre");
    $parameters_eq1 = [':nombre'=>$partido[0]['equipo_nombre_1']];
    $parameters_eq2 = [':nombre'=>$partido[0]['equipo_nombre_2']];
    $stmt_eq->execute($parameters_eq1);
    $equipo1 = $stmt_eq->fetchAll(PDO::FETCH_ASSOC);
    $stmt_eq->execute($parameters_eq2);
    $equipo2 = $stmt_eq->fetchAll(PDO::FETCH_ASSOC);
    $stmt_ref = $conexion->prepare("SELECT * FROM arbitro WHERE dni = :dni");
    $parameters_ref = [':dni'=>$partido[0]['arbitro_dni']];
    $stmt_ref->execute($parameters_ref);
    $arbitro = $stmt_ref->fetchAll(PDO::FETCH_ASSOC);
    $hoy = date("Y-m-d H:i:s", time());
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
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Open+Sans|Quicksand|Raleway" rel="stylesheet">
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
        <a href="usuario.php" id="user"><div><img src='<?= $usuario[0]->getFoto() ?>' /></div><?= $usuario[0]->getNombre() ?></a>
    </div>
    <div id="content_partido">
        <table align="center">
            <tr>
                <td><img src="<?= $equipo1[0]['logo'] ?>" /></td>
                <td><b> - VS - </b></td>
                <td><img src="<?= $equipo2[0]['logo'] ?>" /></td>
            </tr>
            <tr>
                <td><?= $partido[0]['equipo_nombre_1'] ?></td>
                <?php if($_SESSION['entidad']=='arbitro' && $partido[0]['goles_1']==null): ?>
                    <td><button type="button">Puntuar partido</button></td>
                <?php else: ?>
                    <td></td>
                <?php endif; ?>
                <td><?= $partido[0]['equipo_nombre_2'] ?></td>
            </tr>
            <?php if($partido[0]['goles_1']!=null): ?>
            <tr>
                <td><?= $partido[0]['goles_1'] ?></td>
                <td></td>
                <td><?= $partido[0]['goles_2'] ?></td>
            </tr>
            <?php endif; ?>
        </table>
        <table id="partido_2">
            <tr>
                <td>
                    <img src="<?= $arbitro[0]['foto'] ?>" />
                </td>
                <td>
                    <h3>Colegiado</h3>
                    <p><?= $arbitro[0]['nombre'] ?></p>
                </td>
                <td>
                    <h3>Campo</h3>
                    <?php if($partido[0]['campo_id'] == 1): ?>
                        <p>Interior</p>
                    <?php else: ?>
                        <p>Exterior</p>
                    <?php endif; ?>
                </td>
                <td><?php if($partido[0]['campo_id'] == 1): ?>
                        <img src="IMGs/interior.jpg">
                    <?php else: ?>
                        <img src="IMGs/exterior.jpg">
                    <?php endif; ?></td>
            </tr>
        </table>
    </div>

</div>

<script>
    document.getElementsByTagName('button')[0].onclick = puntuar;

    function puntuar() {
        let fechaPartido = "<?= $_POST['fecha'] ?>";
        let fechaHoy = "<?= $hoy ?>";
        let divisor = document.createElement('div');
        divisor.setAttribute('id', 'confirmar');
        let difuminador = document.createElement('div');
        difuminador.setAttribute('id', 'difuminador');
        if(fechaHoy>fechaPartido) {
            let httpRequest = new XMLHttpRequest();
            httpRequest.open('POST', 'formulario_goles.php', true);
            httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            httpRequest.onreadystatechange = function() {
                if(httpRequest.readyState===4) {
                    if(httpRequest.status === 200) {
                        divisor.innerHTML = httpRequest.responseText;
                        document.getElementById('aceptar').onclick = updatePartido;
                        document.getElementById('cancelar').onclick = cerrarDiv;
                        for(let i = 0; i<document.getElementsByClassName('anyadir').length; i++) {
                            document.getElementsByClassName('anyadir')[i].onclick = sumarGol;
                        }
                        for(let i = 0; i<document.getElementsByClassName('restar').length; i++) {
                            document.getElementsByClassName('restar')[i].onclick = restarGol;
                        }
                    }
                }
            }
            let obj_eq = new Equipos("<?= $partido[0]['equipo_nombre_1'] ?>", "<?= $partido[0]['equipo_nombre_2'] ?>");
            let equipos = JSON.stringify(obj_eq);
            httpRequest.send('equipos='+equipos);

        } else {
            let parrafo = document.createElement('p');
            let texto_p = document.createTextNode("No puedes puntuar un partido que aun no se ha jugado");
            parrafo.appendChild(texto_p);
            let button = document.createElement('button');
            button.setAttribute('type', 'button');
            button.setAttribute('id', 'cancelar');
            let text_button = document.createTextNode("Aceptar");
            button.appendChild(text_button);
            divisor.appendChild(parrafo);
            divisor.appendChild(button);
        }
        document.body.appendChild(difuminador);
        document.body.appendChild(divisor);
        document.getElementById('cancelar').onclick = cerrarDiv;
    }

    function sumarGol(event) {
        for (let i = 0; i<document.getElementsByClassName('anyadir').length; i+=2) {
            if(event.target === document.getElementsByClassName('anyadir')[i]) {
                document.getElementsByName('goles_jugadores_1[]')[i/2].value++;
            }
        }
        for (let j = 1; j<document.getElementsByClassName('anyadir').length; j+=2) {
            if(event.target === document.getElementsByClassName('anyadir')[j]) {
                document.getElementsByName('goles_jugadores_2[]')[Math.floor(j/2)].value++;
            }
        }
    }

    function restarGol(event) {
        for (let i = 0; i<document.getElementsByClassName('restar').length; i+=2) {
            if(event.target === document.getElementsByClassName('restar')[i] && document.getElementsByName('goles_jugadores_1[]')[i/2].value!=0) {
                document.getElementsByName('goles_jugadores_1[]')[i/2].value--;
            }
        }
        for (let j = 1; j<document.getElementsByClassName('restar').length; j+=2) {
            if(event.target === document.getElementsByClassName('restar')[j] && document.getElementsByName('goles_jugadores_2[]')[Math.floor(j/2)].value!=0) {
                document.getElementsByName('goles_jugadores_2[]')[Math.floor(j/2)].value--;
            }
        }
    }

    function updatePartido() {
        document.getElementsByTagName('form')[2].submit();
    }

    function Equipos(equipo1, equipo2) {
        this.equipo1 = equipo1;
        this.equipo2 = equipo2;
    }

    function cerrarDiv() {
        document.body.removeChild(document.getElementById('difuminador'));
        document.body.removeChild(document.getElementById('confirmar'));
    }
</script>

</body>
</html>