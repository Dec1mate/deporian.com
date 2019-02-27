<?php
session_start();
require_once "database/Connection.php";
require_once "metodos.php";
$conexion = Connection::make();
$dias = [];
$dias_db = [];
$dias_sem = [];
//Sacamos la fecha actual y los 5 dias siguientes
$hoy = time() + 86400;
$horas = ['15:00:00', '18:00:00', '21:00:00'];
for ($i=0; $i<5; $i++) {
    array_push($dias, date("d/m/Y", $hoy));
    array_push($dias_db, date("Y-m-d", $hoy));
    array_push($dias_sem, date("w", $hoy));
    $hoy += 86400;
}

$stmt = $conexion->prepare("SELECT equipo_nombre, fecha FROM reserva WHERE campo_id = :id");
if (isset($_POST['accion'])) {

    $data = json_decode($_POST['accion'], true);

    $parameters = [':id'=>intval($data['campo'])];
    $stmt->execute($parameters);
    $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if($data!=null) :?>
<table>
    <tr>
        <?php foreach($dias as $dia_t) : ?>
            <th><?= $dia_t ?></th>
        <?php endforeach; ?>
    </tr>
    <?php for ($i=0; $i<count($horas); $i++): ?>
        <tr>
            <?php for($j=0; $j<count($dias_db); $j++) {
                $date = $dias_db[$j]." ".$horas[$i];
                $reservado1 = false;
                $reservado2 = false;
                $festivo = false;
                for ($k = 0; $k<count($reservas); $k++) {
                    if($reservas[$k]['fecha'] == $date) {
                        if($reservas[$k]['equipo_nombre']==$data['tema']) {
                            $reservado2 = true;
                        } else {
                            $reservado1 = true;
                        }
                    }
                }
                if($dias_sem[$j]==0 || $dias_sem[$j]==6) {
                    $festivo = true;
                }
                if($reservado1 == true) : ?>
                    <td class="reservado"><?= $i_reservado ?></td>
                <?php elseif ($reservado2==true): ?>
                    <td class="reservado2"><?= $data['tema'] ?></td>
                <?php elseif ($festivo==true): ?>
                    <td class="festivo"><?= $i_festivo ?></td>
                <?php else: ?>
                    <td><?=$horas[$i]?></td>
                <?php endif;
            }
            ?>

        </tr>
    <?php endfor; ?>
</table>
<?php endif;
}

if(isset($_POST['comprobar'])) {
    $data = json_decode($_POST['comprobar'], true);

    $stmt = $conexion->prepare("SELECT * FROM reserva WHERE campo_id = :id AND equipo_nombre = :equipo AND fecha = :fecha");
    $parameters = [':id'=>intval($data['campo']), ':equipo'=>$data['tema'], ':fecha'=>$data['fech']];
    $stmt->execute($parameters);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt2 = $conexion->prepare("SELECT MAX(fecha) as fecha FROM amonesta WHERE equipo_nombre = :equipo");
    $parameters2 = [':equipo'=>$data['tema']];
    $stmt2->execute($parameters2);
    $amonestaciones = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    $amonestado = false;
    if(!$amonestaciones) {
        if($amonestaciones[0]['fecha']<date("Y-m-d H:i:s", time() + 2592000)) {
            $amonestado = true;
        }
    }

    if ($amonestado == true) {
        echo "amonestado";
    } else if(date("Y-m-d H:i:s", time() + 86400)<=$data['fech']) {
        if ($result) {
            echo "unreservable";
        } else {
            echo "reservable";
        }
    } else {
        echo "f_plazo";
    }



}
?>
