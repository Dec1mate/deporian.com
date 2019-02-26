<?php
session_start();
require_once "database/Connection.php";
$conexion = Connection::make();
$hoy = date("Y-m-d H:i:s", time());
$stmt = $conexion->prepare("SELECT * FROM reserva WHERE arbitro_dni = :dni");
$parameters = [':dni'=>$_SESSION['dni']];
$stmt->execute($parameters);
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(empty($reservas)) {
    echo "opcion1";
} else {
    if(!empty($reservas)):?>
    <table id="amonestaciones">
        <?php for($i=0; $i<count($reservas); $i++) {
            if($reservas[$i]['fecha']<$hoy):?>
            <tr>
                <td><?= $reservas[$i]['equipo_nombre'] ?></td>
                <td><?= $reservas[$i]['fecha'] ?></td>
                <td><button type="button" class="btn_amonestar">Amonestar</button>
                    <form action="usuario.php" method="POST">
                        <input type="hidden" value="<?= $reservas[$i]['equipo_nombre'] ?>" name="equipo_amonest">
                        <input type="hidden" value="<?= $reservas[$i]['fecha'] ?>" name="fecha_amonest">
                    </form>
                </td>
            </tr>
        <?php endif;
        }
        ?>
    </table>
    <?php endif;
}
?>
