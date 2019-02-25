    <form action="usuario.php" method="post">
        <input type="button" value="<?= $i_apuntarse_boton ?>">
        <input type="button" value="<?= $i_reservas_boton ?>">
        <input type="button" value="<?= $i_modificar ?>">
        <input type="hidden" name="opciones_jugador">
    </form>
    <img src='<?= $equipo[0]['logo'] ?>' id="equipo"/>

    <script>
        document.getElementsByTagName('input')[3].onclick = apuntarseLiga;
        document.getElementsByTagName('input')[4].onclick = reservaCampo;
        document.getElementsByTagName('input')[5].onclick = modificarDatos;

        function apuntarseLiga() {
            document.getElementsByTagName('input')[6].value="apuntarse";
            document.getElementsByTagName('form')[2].action = "usuario.php";
            document.getElementsByTagName('form')[2].submit();
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
    </script>
</div>
