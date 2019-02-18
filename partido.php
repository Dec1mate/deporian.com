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
            let parrafo1 = document.createElement('p');
            let texto_p1 = document.createTextNode('Goles de <?= $partido[0]['equipo_nombre_1'] ?>');
            parrafo1.appendChild(texto_p1);
            let input1 = document.createElement('input');
            input1.setAttribute('type', 'text');
            input1.setAttribute('name', 'goles1');

            let parrafo2 = document.createElement('p');
            let texto_p2 = document.createTextNode('Goles de <?= $partido[0]['equipo_nombre_2'] ?>');
            parrafo2.appendChild(texto_p2);
            let input2 = document.createElement('input');
            input2.setAttribute('type', 'text');
            input2.setAttribute('name', 'goles2');

            let input3 = document.createElement('input');
            input3.setAttribute('type', 'hidden');
            input3.setAttribute('value', '<?= $_POST['fecha'] ?>');
            input3.setAttribute('name', 'fecha');

            let boton1 = document.createElement('input');
            boton1.setAttribute('type', 'submit');
            boton1.setAttribute('value', 'Confirmar');

            let boton2 = document.createElement('button');
            boton2.setAttribute('type', 'button');
            let text_button = document.createTextNode("Cancelar");
            boton2.appendChild(text_button);

            let salto1 = document.createElement('br');

            formulario.appendChild(parrafo1);
            formulario.appendChild(input1);
            formulario.appendChild(parrafo2);
            formulario.appendChild(input2);
            formulario.appendChild(input3);
            formulario.appendChild(salto1);
            formulario.appendChild(boton1);
            formulario.appendChild(boton2);

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

    function cerrarDiv() {
        document.body.removeChild(document.getElementById('difuminador'));
        document.body.removeChild(document.getElementById('confirmar'));
    }
</script>

</body>
</html>