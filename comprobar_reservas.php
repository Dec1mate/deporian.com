<?php
session_start();
require_once "database/Connection.php";
require_once "metodos.php";
if(!isset($_SESSION['dni'])) {
    header("Location: index.php");
}
$conexion = Connection::make();
$hoy = date("Y-m-d H:i:s", time());
//Selecciona las reservas vinculadas a un arbitro en las que no haya ningun equipo ya amonestado por ese arbitro en alguna de esas fechas
$stmt = $conexion->prepare("SELECT * FROM reserva WHERE arbitro_dni = :dni AND equipo_nombre NOT IN (SELECT A.equipo_nombre FROM amonesta A, reserva R WHERE A.arbitro_dni = :dni AND R.fecha = A.fecha)");
$parameters = [':dni'=>$_SESSION['dni']];
$stmt->execute($parameters);
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(empty($reservas)) {
    //Manda un string que manejara como error si no los encuentra
    echo "opcion1";
} else {
    //Muestra resultados si los encuentra
    if(!empty($reservas)):?>
    <table id="amonestaciones">
        <?php for($i=0; $i<count($reservas); $i++) {
            if($reservas[$i]['fecha']<$hoy):?>
            <tr>
                <td><p><?= $reservas[$i]['equipo_nombre'] ?></p></td>
                <td><p><?= $reservas[$i]['fecha'] ?></p></td>
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
