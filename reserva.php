<?php
session_start();
date_default_timezone_set('Europe/Madrid');
require_once "metodos.php";

$stmt1 = $conexion->prepare("SELECT * FROM ".$_SESSION['entidad']." WHERE dni = :dni");
$parameters = [':dni'=>$_SESSION['dni']];
$stmt1->execute($parameters);
$usuario = $stmt1->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Jugador");


if(isset($_POST['opcion'])) {
    if($_POST['opcion']=='reservar') {
        $stmt1 = $conexion->prepare("SELECT dni FROM arbitro");
        $stmt1->execute();
        $arbitros = $stmt1->fetchAll(PDO::FETCH_ASSOC);
        $stmt2 = $conexion->prepare("INSERT INTO reserva(equipo_nombre, arbitro_dni, campo_id, fecha) VALUES (:equipo, :arbitro, :campo, :fecha)");
        $parameters2 = [':equipo'=>$usuario[0]->getEquipo(), ':arbitro'=>$arbitros[rand(0, count($arbitros)-1)]['dni'], ':campo'=>intval($_POST['reserva']), ':fecha'=>$_POST['hora_reserva']];
        $stmt2->execute($parameters2);
    } else if($_POST['opcion']=='cancelar') {
        $stmt = $conexion->prepare("DELETE FROM reserva WHERE equipo_nombre=:equipo AND fecha=:fecha AND campo_id=:campo");
        $parameters = [':equipo'=>$usuario[0]->getEquipo(), ':fecha'=>$_POST['hora_reserva'], ':campo'=>intval($_POST['reserva'])];
        $stmt->execute($parameters);
    }

}

$clave = "http://api.openweathermap.org/data/2.5/forecast?lat=39.594189&lon=-0.54474&APPID=d0cb0d8b429769a6e1105782251c99aa&units=metric&lang=es";
$data = file_get_contents($clave);
$datos = json_decode($data);

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
            <form action="reserva.php" method="post">
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
    <form action="reserva.php" method="post" id="botones_reserva">
        <button type="button" value="interior"><?= $i_campo_1 ?></button>
        <button type="button" value="exterior"><?= $i_campo_2 ?></button>
               <!--Aqui va la tabla-->
        <input type="hidden" name="hora_reserva">
        <input type="hidden" name="reserva">
        <input type="hidden" name="opcion">
    </form>
    <div id="calendario_re">
        <div id="pos_cal"></div>
    </div>

    <script>
        let tiempos = JSON.parse('<?= $data ?>');
        let fecha ="";
        document.getElementById('logout').onclick = cerrarSesion;
        for (let i=0; i<2; i++) {
            document.getElementsByTagName('button')[i].onclick = cambiarTabla;
        }

        function cambiarTabla(event) {
            let httpRequest = obtainXMLHttpRequest();
            httpRequest.open('POST', 'calendario_reservas.php', true);
            httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            httpRequest.onreadystatechange=function() {
                if(httpRequest.readyState===4) {
                    if (httpRequest.status === 200) {
                        document.getElementById('pos_cal').innerHTML = httpRequest.responseText;
                        for (let i = 0; i<document.getElementsByTagName('td').length; i++) {
                            if (document.getElementsByTagName('td')[i].className !== 'reservado' && document.getElementsByTagName('td')[i].className !== 'reservado2' && document.getElementsByTagName('td')[i].className !== 'festivo') {
                                document.getElementsByTagName('td')[i].onclick = elegirFecha;
                            } else if (document.getElementsByTagName('td')[i].className === 'reservado2'){
                                document.getElementsByTagName('td')[i].onclick = cancelarReserva;
                            }
                        }
                    }
                }
            }
            if(event.target.value==="exterior") {
                document.getElementsByTagName('input')[4].value="2";
                event.target.setAttribute('id', 'selected');
                document.getElementsByTagName('button')[0].setAttribute('id', "");
                let data = new Datos('2', '<?= $usuario[0]->getEquipo() ?>', null);
                data = JSON.stringify(data);
                httpRequest.send('accion='+data);
            } else {
                document.getElementsByTagName('input')[4].value="1";
                event.target.setAttribute('id', 'selected');
                document.getElementsByTagName('button')[1].setAttribute('id', "");
                let data = new Datos('1', '<?= $usuario[0]->getEquipo() ?>', null);
                data = JSON.stringify(data);
                httpRequest.send('accion='+data);
            }
        }

        function Datos(campo, tema, fech) {
            this.campo = campo;
            this.tema = tema;
            this.fech = fech;
        }

        function elegirFecha(event) {
            for(let i = 1; i<4; i++) {
                for (let j = 0; j<5; j++) {
                    if(document.getElementsByTagName('tr')[i].getElementsByTagName('td')[j] === event.target) {
                        let dia = document.getElementsByTagName('th')[j].innerText;
                        dia = dia.split('/');
                        let dia_nuevo = dia[2] + "-" + dia[1] + "-" + dia[0];
                        fecha = dia_nuevo + " " + event.target.innerText;
                        let httpRequest = obtainXMLHttpRequest();
                        httpRequest.open('POST', 'calendario_reservas.php', true);
                        httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        httpRequest.onreadystatechange=function() {
                            if(httpRequest.readyState===4) {
                                if (httpRequest.status === 200) {
                                    if (httpRequest.responseText === 'reservable') {
                                        let divisor = document.createElement('div');
                                        divisor.setAttribute('id', 'confirmar');
                                        let difuminador = document.createElement('div');
                                        difuminador.setAttribute('id', 'difuminador');
                                        let parrafo = document.createElement('p');
                                        let temp_min, temp_max;
                                        for (let i = 0; i<tiempos.list.length; i++) {
                                            if (tiempos.list[i].dt_txt === fecha) {
                                                temp_min = tiempos.list[i].main.temp_min;
                                                temp_max = tiempos.list[i].main.temp_max;
                                            }
                                        }
                                        let texto = document.createTextNode("<?= $i_reserva_0[0] ?>" + fecha +"\n<?= $i_reserva_0[1] ?>" + temp_min + "<?= $i_reserva_0[2] ?>" + temp_max + "<?= $i_reserva_0[3] ?>");
                                        parrafo.appendChild(texto);
                                        let boton1 = document.createElement('button');
                                        boton1.setAttribute('type', 'button');
                                        boton1.setAttribute('id', 'aceptar');
                                        let boton2 = document.createElement('button');
                                        boton2.setAttribute('type', 'button');
                                        boton2.setAttribute('id', 'cancelar');
                                        let texto_b1 = document.createTextNode("<?= $i_aceptar ?>");
                                        let texto_b2 = document.createTextNode("<?= $i_cancelar ?>");
                                        boton1.appendChild(texto_b1);
                                        boton2.appendChild(texto_b2);
                                        divisor.appendChild(parrafo);
                                        divisor.appendChild(boton1);
                                        divisor.appendChild(boton2);
                                        divisor.style = "white-space: pre";

                                        document.body.appendChild(difuminador);
                                        document.body.appendChild(divisor);

                                        document.getElementsByTagName('button')[2].onclick = confirmarFecha;
                                        document.getElementsByTagName('button')[3].onclick = desconfirmarReserva;
                                    } else if(httpRequest.responseText === "unreservable") {
                                        let divisor = document.createElement('div');
                                        divisor.setAttribute('id', 'confirmar');
                                        let difuminador = document.createElement('div');
                                        difuminador.setAttribute('id', 'difuminador');
                                        let parrafo = document.createElement('p');
                                        let texto = document.createTextNode("<?= $i_reserva_1 ?>");
                                        parrafo.appendChild(texto);
                                        let boton1 = document.createElement('button');
                                        boton1.setAttribute('type', 'button');
                                        boton1.setAttribute('id', 'aceptar');
                                        let texto_b1 = document.createTextNode("<?= $i_aceptar ?>");
                                        boton1.appendChild(texto_b1);
                                        divisor.appendChild(parrafo);
                                        divisor.appendChild(boton1);

                                        document.body.appendChild(difuminador);
                                        document.body.appendChild(divisor);
                                        document.getElementsByTagName('button')[2].onclick = desconfirmarReserva;
                                    } else if (httpRequest.responseText === 'amonestado') {
                                        let divisor = document.createElement('div');
                                        divisor.setAttribute('id', 'confirmar');
                                        let difuminador = document.createElement('div');
                                        difuminador.setAttribute('id', 'difuminador');
                                        let parrafo = document.createElement('p');
                                        let texto = document.createTextNode("<?= $i_reserva_2 ?>");
                                        parrafo.appendChild(texto);
                                        let boton1 = document.createElement('button');
                                        boton1.setAttribute('type', 'button');
                                        boton1.setAttribute('id', 'aceptar');
                                        let texto_b1 = document.createTextNode("<?= $i_aceptar ?>");
                                        boton1.appendChild(texto_b1);
                                        divisor.appendChild(parrafo);
                                        divisor.appendChild(boton1);
                                        document.body.appendChild(difuminador);
                                        document.body.appendChild(divisor);
                                        document.getElementsByTagName('button')[2].onclick = desconfirmarReserva;
                                    } else if (httpRequest.responseText === 'f_plazo') {
                                        let divisor = document.createElement('div');
                                        divisor.setAttribute('id', 'confirmar');
                                        let difuminador = document.createElement('div');
                                        difuminador.setAttribute('id', 'difuminador');
                                        let parrafo = document.createElement('p');
                                        let texto = document.createTextNode("<?= $i_reserva_3 ?>");
                                        parrafo.appendChild(texto);
                                        let boton1 = document.createElement('button');
                                        boton1.setAttribute('type', 'button');
                                        let texto_b1 = document.createTextNode("<?= $i_aceptar ?>");
                                        boton1.appendChild(texto_b1);
                                        divisor.appendChild(parrafo);
                                        divisor.appendChild(boton1);

                                        document.body.appendChild(difuminador);
                                        document.body.appendChild(divisor);
                                        document.getElementsByTagName('button')[2].onclick = desconfirmarReserva;
                                    }
                                }
                            }
                        }
                        if(document.getElementsByTagName('input')[4].value==="2") {
                            let data = new Datos('1', '<?= $usuario[0]->getEquipo() ?>', fecha);
                            data = JSON.stringify(data);
                            httpRequest.send('comprobar='+data);
                        } else {
                            let data = new Datos('2', '<?= $usuario[0]->getEquipo() ?>', fecha);
                            data = JSON.stringify(data);
                            httpRequest.send('comprobar='+data);
                        }
                    }
                }
            }
        }

        function confirmarFecha() {
            document.getElementsByTagName('input')[3].value=fecha;
            document.getElementsByTagName('input')[5].value='reservar';
            document.getElementsByTagName('form')[2].submit();
        }

        function desconfirmarReserva() {
            document.body.removeChild(document.getElementById('difuminador'));
            document.body.removeChild(document.getElementById('confirmar'));
        }

        function cancelarReserva(event) {
            for(let i = 1; i<4; i++) {
                for (let j = 0; j<5; j++) {
                    if(document.getElementsByTagName('tr')[i].getElementsByTagName('td')[j] === event.target) {
                        let hora;
                        let dia = document.getElementsByTagName('th')[j].innerText;
                        dia = dia.split('/');
                        let dia_nuevo = dia[2] + "-" + dia[1] + "-" + dia[0];
                        if (i===1) {
                            hora = "15:00:00";
                        } else if(i===2) {
                            hora = "18:00:00";
                        } else {
                            hora = "21:00:00";
                        }
                        fecha = dia_nuevo + " " + hora;
                        let divisor = document.createElement('div');
                        divisor.setAttribute('id', 'confirmar');
                        let difuminador = document.createElement('div');
                        difuminador.setAttribute('id', 'difuminador');
                        let parrafo = document.createElement('p');
                        let texto = document.createTextNode("<?= $i_reserva_4[0] ?>" + fecha +"\n<?= $i_reserva_4[1] ?>");
                        parrafo.appendChild(texto);
                        let boton1 = document.createElement('button');
                        boton1.setAttribute('type', 'button');
                        boton1.setAttribute('id', 'aceptar');
                        let boton2 = document.createElement('button');
                        boton2.setAttribute('type', 'button');
                        boton2.setAttribute('id', 'cancelar');
                        let texto_b1 = document.createTextNode("<?= $i_aceptar ?>");
                        let texto_b2 = document.createTextNode("<?= $i_cancelar ?>");
                        boton1.appendChild(texto_b1);
                        boton2.appendChild(texto_b2);
                        divisor.appendChild(parrafo);
                        divisor.appendChild(boton1);
                        divisor.appendChild(boton2);
                        divisor.style = "white-space: pre";

                        document.body.appendChild(difuminador);
                        document.body.appendChild(divisor);

                        document.getElementsByTagName('button')[2].onclick = confirmarCancelacion;
                        document.getElementsByTagName('button')[3].onclick = desconfirmarReserva;
                    }
                }
            }
        }

        function confirmarCancelacion() {
            document.getElementsByTagName('input')[3].value=fecha;
            document.getElementsByTagName('input')[5].value='cancelar';
            document.getElementsByTagName('form')[2].submit();
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

        function cerrarSesion(event) {
            document.getElementsByTagName('input')[2].value = event.target.id;
            document.getElementsByTagName('form')[1].submit();
        }
        document.getElementsByTagName('img')[1].onclick = cambiarIdioma;
        document.getElementsByTagName('img')[2].onclick = cambiarIdioma;
        function cambiarIdioma(event) {
            document.getElementsByTagName('input')[0].value = event.target.id;
            document.getElementsByTagName('form')[0].submit();
        }
    </script>
</body>
</html>