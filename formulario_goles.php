<?php
session_start();
require_once "metodos.php";
if(!isset($_SESSION['dni']) && !isset($_POST['equipos'])) {
    header("Location: index.php");
}
require_once "database/Connection.php";
if(isset($_POST['equipos'])) {
    $data = json_decode($_POST['equipos'], true);
    //Buscamos los jugadores de ambos equipos ordenandolos por nombre
    //Esto nos facilita a la hora de sumarles los goles el hecho de pasarselos por orden alfabetico
    $conexion = Connection::make();
    $stmt = $conexion->prepare("SELECT * FROM equipo WHERE nombre = :equipo");
    $stmt2 = $conexion->prepare("SELECT nombre FROM jugador WHERE equipo = :equipo ORDER BY nombre");
    $parameters1 = [':equipo'=>$data['equipo1']];
    $parameters2 = [':equipo'=>$data['equipo2']];
    $stmt->execute($parameters1);
    $equipo1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->execute($parameters2);
    $equipo2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt2->execute($parameters1);
    $jugadores1 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    $stmt2->execute($parameters2);
    $jugadores2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    if(!empty($jugadores1)):?>
    <form action="partido.php" method="POST">
        <table>
            <tr>
                <td><img src="<?= $equipo1[0]['logo'] ?>" ><input type="hidden" value="<?= $equipo1[0]['nombre'] ?>" name="equipo1"></td>
                <td><input type="text" name="goles1" value="0"</td>
                <td><input type="text" name="goles2" value="0"</td>
                <td><img src="<?= $equipo2[0]['logo'] ?>" ><input type="hidden" value="<?= $equipo2[0]['nombre'] ?>" name="equipo2"></td>
            </tr>
            <tr><td colspan="4"><hr></td></tr>
            <?php
            $max_jugadores = count($jugadores1);
            if(count($jugadores2)>$max_jugadores) {
                $max_jugadores = count($jugadores2);
            }
            for($i=0; $i<$max_jugadores; $i++) :?>
            <tr>
                <?php if(isset($jugadores1[$i])) :?>
                <td><?= $jugadores1[$i]['nombre'] ?></td>
                <td><button type="button" class="anyadir">+</button><button type="button" class="restar">-</button><input type="text" class="input_goles" name="goles_jugadores_1[]" value="0" readonly></td>
                <?php else: ?>
                <td colspan="2"></td>
                <?php endif; ?>
                <?php if(isset($jugadores2[$i])) :?>
                <td><input type="text" class="input_goles" name="goles_jugadores_2[]" value="0" readonly><button type="button" class="restar">-</button><button type="button" class="anyadir">+</button></td>
                <td><?= $jugadores2[$i]['nombre'] ?></td>
                <?php else: ?>
                <td colspan="2"></td>
                <?php endif; ?>
            </tr>
            <?php endfor; ?>
            <tr><td colspan="2"><button type="button" id="aceptar">Aceptar</button></td><td colspan="2"><button type="button" id="cancelar">Cancelar</button></td></tr>
        </table>
    </form>
    <?php endif;
}
?>