<form action="usuario.php" method="post">
    <input type="button" value="<?= $i_modificar ?>">
    <input type="button" value="Amonestar">
    <input type="hidden" name="opciones_jugador">
</form>
<script>
    document.getElementsByTagName('input')[3].onclick = modificarDatos;
    document.getElementsByTagName('input')[4].onclick = amonestar;

    function modificarDatos() {
        document.getElementsByTagName('input')[4].value="arbitro";
        document.getElementsByTagName('form')[2].action = "modificar.php";
        document.getElementsByTagName('form')[2].submit();
    }

    function amonestar() {
        let httpRequest = obtainXMLHttpRequest();
        httpRequest.open('POST', 'comprobar_reservas.php', true);
        httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        httpRequest.onreadystatechange = function() {
            if(httpRequest.readyState===4) {
                if(httpRequest.status === 200) {
                    if(httpRequest.responseText == "opcion1") {
                        //El arbitro no tiene ninguna reserva en su calendario
                        let divisor = document.createElement('div');
                        divisor.setAttribute('id', 'confirmar');
                        let difuminador = document.createElement('div');
                        difuminador.setAttribute('id', 'difuminador');
                        let parrafo = document.createElement('p');
                        let texto = document.createTextNode("Prueba 1");
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
                    } else {
                        //Doble select de opciones (dia/equipo)
                        let divisor = document.createElement('div');
                        divisor.setAttribute('id', 'confirmar');
                        let difuminador = document.createElement('div');
                        difuminador.setAttribute('id', 'difuminador');

                        divisor.innerHTML = httpRequest.responseText;

                        let boton1 = document.createElement('button');
                        boton1.setAttribute('type', 'button');
                        boton1.setAttribute('id', 'aceptar');
                        let texto_b1 = document.createTextNode("<?= $i_aceptar ?>");
                        boton1.appendChild(texto_b1);
                        divisor.appendChild(boton1);
                        divisor.style = "white-space: pre";

                        document.body.appendChild(difuminador);
                        document.body.appendChild(divisor);


                        for(let i = 0; i<document.getElementsByClassName('btn_amonestar').length; i++) {
                            document.getElementsByClassName('btn_amonestar')[i].onclick = amonestarEquipo;
                        }
                        document.getElementById('aceptar').onclick = desconfirmar;
                    }
                }
            }
        }
        httpRequest.send();
    }

    function amonestarEquipo(event) {
        event.target.parentNode.getElementsByTagName('form')[0].submit();
    }

    function desconfirmar() {
        document.body.removeChild(document.getElementById('difuminador'));
        document.body.removeChild(document.getElementById('confirmar'));
    }
</script>