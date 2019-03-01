<?php
require_once "metodos.php";
if(!isset($_SESSION['dni'])) {
    header("Location: index.php");
}
?>
<form action="usuario.php" method="post">
        <input type="button" value="<?= $i_apuntarse_boton ?>">
        <input type="button" value="<?= $i_reservas_boton ?>">
        <input type="button" value="<?= $i_modificar ?>">
        <input type="hidden" name="opciones_jugador">
    </form>
    <img src="IMGs/banner.png" id="banner">
    <img src='<?= $equipo[0]['logo'] ?>' id="equipo"/>

    <script>
        document.getElementsByTagName('input')[3].onclick = confirmarApuntarseLiga;
        document.getElementsByTagName('input')[4].onclick = reservaCampo;
        document.getElementsByTagName('input')[5].onclick = modificarDatos;

        function confirmarApuntarseLiga() {
            let httpRequest = obtainXMLHttpRequest();
            httpRequest.open('POST', 'comprobar_liga.php', true);
            httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            httpRequest.onreadystatechange = function() {
                if(httpRequest.readyState===4) {
                    if(httpRequest.status === 200) {
                        if(httpRequest.responseText == "opcion1") {
                            let divisor = document.createElement('div');
                            divisor.setAttribute('id', 'confirmar');
                            let difuminador = document.createElement('div');
                            difuminador.setAttribute('id', 'difuminador');
                            let parrafo = document.createElement('p');
                            let texto = document.createTextNode("<?= $i_apuntar_liga[0] ?><?= $usuario->getEquipo() ?><?= $i_apuntar_liga[1] ?><?= $cant_ligas ?><?= $i_apuntar_liga[2] ?>\n<?= $i_apuntar_liga[3] ?>");
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

                            document.getElementsByTagName('button')[2].onclick = apuntarseLiga;
                            document.getElementsByTagName('button')[3].onclick = desconfirmar;
                        } else {
                            let divisor = document.createElement('div');
                            divisor.setAttribute('id', 'confirmar');
                            let difuminador = document.createElement('div');
                            difuminador.setAttribute('id', 'difuminador');
                            let parrafo = document.createElement('p');
                            let texto = document.createTextNode("<?= $i_error_apuntar[0] ?>\n<?= $i_error_apuntar[1] ?>\n<?= $i_error_apuntar[2] ?>" + httpRequest.responseText);
                            parrafo.appendChild(texto);
                            let boton1 = document.createElement('button');
                            boton1.setAttribute('type', 'button');
                            boton1.setAttribute('id', 'aceptar');
                            let texto_b1 = document.createTextNode("<?= $i_aceptar ?>");
                            boton1.appendChild(texto_b1);
                            divisor.appendChild(parrafo);
                            divisor.appendChild(boton1);
                            divisor.style = "white-space: pre";

                            document.body.appendChild(difuminador);
                            document.body.appendChild(divisor);

                            document.getElementsByTagName('button')[2].onclick = desconfirmar;
                        }
                    }
                }
            }
            httpRequest.send();
        }

        function apuntarseLiga() {
            document.getElementsByTagName('input')[6].value="apuntarse";
            document.getElementsByTagName('form')[2].action = "usuario.php";
            document.getElementsByTagName('form')[2].submit();
        }

        function desconfirmar() {
            document.body.removeChild(document.getElementById('difuminador'));
            document.body.removeChild(document.getElementById('confirmar'));
        }

        function reservaCampo() {
            document.getElementsByTagName('input')[6].value="reserva";
            document.getElementsByTagName('form')[2].action = "reserva.php";
            document.getElementsByTagName('form')[2].submit();
        }

        function modificarDatos() {
            document.getElementsByTagName('input')[6].value="jugador";
            document.getElementsByTagName('form')[2].action = "modificar.php";
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
    </script>
</div>
