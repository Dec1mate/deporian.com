<?php
session_start();
require_once "database/Connection.php";
require_once "metodos.php";
if(!isset($_SESSION['dni']) && !isset($_POST['accion'])) {
    header("Location: index.php");
}
if(isset($_POST['accion'])) {
    $conexion = Connection::make();
    if($_POST['accion']==='desplegable') {
        $stmt = $conexion->prepare("SELECT nombre FROM equipo");
        $stmt->execute();
        $equipos_form = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if($equipos_form):?>
            <select name="su_team">
                <?php foreach ($equipos_form as $equip) :?>
                    <option value="<?= $equip['nombre'] ?>"><?= $equip['nombre'] ?></option>
                <?php endforeach;?>
            </select>
        <?php endif;
    } else if($_POST['accion']==='comprobar') {
        $stmt = $conexion->prepare("SELECT dni FROM jugador;");
        $stmt2 = $conexion->prepare("SELECT dni FROM arbitro;");
        $stmt->execute();
        $jugadores = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $stmt2->execute();
        $arbitros = $stmt2->fetchAll(PDO::FETCH_COLUMN);
        $usuarios = array_merge($jugadores, $arbitros);

        $users = json_encode($usuarios);
        echo($users);
    }
}
?>