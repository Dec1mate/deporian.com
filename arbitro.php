<form action="usuario.php" method="post">
    <input type="button" value="Modificar datos">
    <input type="hidden" name="opciones_jugador">
</form>
<script>
    document.getElementsByTagName('input')[3].onclick = modificarDatos;

    function modificarDatos() {
        document.getElementsByTagName('input')[4].value="arbitro";
        document.getElementsByTagName('form')[2].action = "modificar.php";
        document.getElementsByTagName('form')[2].submit();
    }
</script>