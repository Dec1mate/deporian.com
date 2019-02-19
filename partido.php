/* THIS IS A TEST */
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
if(isset($_POST['fecha'])) {
    if($_SESSION['entidad'] == 'jugador') {
        $stmt = $conexion->prepare("SELECT * FROM partido WHERE (equipo_nombre_1 = :equipo OR equipo_nombre_2 = :equipo) AND fecha = :fecha");
        $parameters = [':equipo'=>$usuario[0]->getEquipo(), ':fecha'=>$_POST['fecha']];
    } else {
        $stmt = $conexion->prepare("SELECT * FROM partido WHERE arbitro_dni = :arbitro AND fecha = :fecha");
        $parameters = [':arbitro'=>$usuario[0]->getDni(), 'fecha'=>$_POST['fecha']];
    }
    $stmt->execute($parameters);
    $partido = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(isset($_POST['goles1'])) {
        $usuario[0]->ponerResultado($partido[0]['equipo_nombre_1'], $partido[0]['equipo_nombre_2'], $_POST['goles1'], $_POST['goles2'], $_POST['fecha']);

    }
    $stmt_eq = $conexion->prepare("SELECT * FROM equipo WHERE nombre = :nombre");
    $parameters_eq1 = [':nombre'=>$partido[0]['equipo_nombre_1']];
    $parameters_eq2 = [':nombre'=>$partido[0]['equipo_nombre_2']];
    $stmt_eq->execute($parameters_eq1);
    $stmt_ref = $conexion->prepare("SELECT * FROM arbitro WHERE dni = :dni");
    $parameters_ref = [':dni'=>$partido[0]['arbitro_dni']];
    $stmt_ref->execute($parameters_ref);
    $arbitro = $stmt_ref->fetchAll(PDO::FETCH_ASSOC);
    $equipo1 = $stmt_eq->fetchAll(PDO::FETCH_ASSOC);
    $stmt_eq->execute($parameters_eq2);
    $equipo2 = $stmt_eq->fetchAll(PDO::FETCH_ASSOC);
    $hoy = date("Y-m-d H:i:s", time());
    $stmt_players = $conexion->prepare("SELECT * FROM jugador WHERE equipo = :equipo");
    $parameters_players1 = [':equipo'=>$equipo1[0]['nombre']];
    $parameters_players2 = [':equipo'=>$equipo2[0]['nombre']];
    $stmt_players->execute($parameters_players1);
    $jugadores1 = $stmt_players->fetchAll(PDO::FETCH_ASSOC);
    $stmt_players->execute($parameters_players2);
    $jugadores2 = $stmt_players->fetchAll(PDO::FETCH_ASSOC);


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
                <td></td>
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
        <h3>Colegiado</h3>
        <img src="<?= $arbitro[0]['foto'] ?>" />
        <p><?= $arbitro[0]['nombre'] ?></p>

        <h3>Campo</h3>
        <?php if($partido[0]['campo_id'] == 1): ?>
        <p>Interior</p>
        <img src="IMGs/interior.jpg">
        <?php else: ?>
        <p>Exterior</p>
        <img src="IMGs/exterior.jpg">
        <?php endif; ?>
        <?php if($_SESSION['entidad']=='arbitro' && $partido[0]['goles_1']==null): ?>
        <button type="button">Puntuar partido</button>
        <?php endif; ?>
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
            let formulario = document.createElement('form');
            formulario.setAttribute('action', 'partido.php');
            formulario.setAttribute('method', 'POST');

            let tabla = document.createElement('table');
            let tbody = document.createElement('tbody');

            for (let i = 0; i<4; i++) {
                let hilera = document.createElement('tr');
                if(i===0) {
                    let columna1 = document.createElement('td');
                    let imagen1 = document.createElement('img');
                    imagen1.src = '<?= $equipo1[0]['logo']; ?>';
                    columna1.appendChild(imagen1);
                    let columna2 = document.createElement('td');
                    let input1 = document.createElement('input');
                    input1.setAttribute('type', 'text');
                    input1.setAttribute('name', 'goles1');
                    columna2.appendChild(input1);
                    let columna3 = document.createElement('td');
                    let input2 = document.createElement('input');
                    input2.setAttribute('type', 'text');
                    input2.setAttribute('name', 'goles2');
                    columna3.appendChild(input2);
                    let columna4 = document.createElement('td');
                    let imagen2 = document.createElement('img');
                    imagen2.src = "<?= $equipo2[0]['logo']; ?>";
                    columna4.appendChild(imagen2);
                    hilera.appendChild(columna1);
                    hilera.appendChild(columna2);
                    hilera.appendChild(columna3);
                    hilera.appendChild(columna4);
                } else if (i===1) {
                    let columna = document.createElement('td');
                    columna.setAttribute('colspan', '4');
                    let linea = document.createElement('hr');
                    columna.appendChild(linea);
                    hilera.appendChild(columna);
                } else if (i===3){
                    let columna1 = document.createElement('td');
                    columna1.setAttribute('colspan', '2');
                    let boton1 = document.createElement('input');
                    boton1.setAttribute('type', 'submit');
                    boton1.setAttribute('value', 'Confirmar');
                    columna1.appendChild(boton1);
                    let columna2 = document.createElement('td');
                    columna2.setAttribute('colspan', '2');
                    let boton2 = document.createElement('button');
                    boton2.setAttribute('type', 'button');
                    let text_button = document.createTextNode("Cancelar");
                    boton2.appendChild(text_button);
                    columna2.appendChild(boton2);
                    hilera.appendChild(columna1);
                    hilera.appendChild(columna2);
                } else {
                    let httpRequest = new XMLHttpRequest();
                    httpRequest.open('POST', 'formulario_goles.php', true);
                    httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    httpRequest.onreadystatechange = function() {
                        if(httpRequest.readyState===4) {
                            if(httpRequest.status===200) {
                                hilera.innerHTML = httpRequest.responseText;
                            }
                        }
                    }
                    let obj_eq = new Equipos(<?= $partido[0]['equipo_nombre_1'] ?>, <?= $partido[0]['equipo_nombre_1'] ?>);
                    let equipos = JSON.stringify(obj_eq);
                    httpRequest.send('equipos='+equipos);
                }
                tbody.appendChild(hilera);
            }
            tabla.appendChild(tbody);
            formulario.appendChild(tabla);
            divisor.appendChild(formulario);
        } else {
            let parrafo = document.createElement('p');
            let texto_p = document.createTextNode("No puedes puntuar un partido que aun no se ha jugado");
            parrafo.appendChild(texto_p);
            let button = document.createElement('button');
            button.setAttribute('type', 'button');
            let text_button = document.createTextNode("Aceptar");
            button.appendChild(text_button);
            divisor.appendChild(parrafo);
            divisor.appendChild(button);
        }
        document.body.appendChild(difuminador);
        document.body.appendChild(divisor);

        document.getElementsByTagName('button')[1].onclick = cerrarDiv;

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