<?php
require_once "database/Connection.php";
if(isset($_POST['equipos'])) {
    $data = json_decode($_POST['equipos'], true);

    $conexion = Connection::make();
    $stmt = $conexion->prepare("SELECT * FROM equipo WHERE nombre = :equipo");
    $parameters1 = [':equipo'=>$data['equipo1']];
    $parameters2 = [':equipo'=>$data['equipo2']];
    $stmt->execute($parameters1);
    $jugadores1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->execute($parameters2);
    $jugadores2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(!empty($jugadores)) {
        for($i=0; $i<5; $i++) :?>
<tr>
    <td><?= $jugadores1[$i]['nombre'] ?></td>
    <td><button type="button" class="anyadir">+</button><button type="button" class="restar">-</button><input type="text" name="goles_jugador" value="0" readonly></td>
    <td><input type="text" name="goles_jugador" value="0" readonly><button type="button" class="restar">-</button><button type="button" class="anyadir">+</button></td>
    <td><?= $jugadores2[$i]['nombre'] ?></td>
</tr>
<?php endfor;
    }
}
?>