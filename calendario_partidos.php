<?php
session_start();
require_once "database/Connection.php";
require_once "metodos.php";
$conexion = Connection::make();
$hoy = time();
$mes = date("m", $hoy);
$anyo = date("Y", $hoy);

function obtenerDia(&$cont, $reservas, $partidos, $conexion) {
    $reservado = false;
    $partido = false;
    // this is reservas
    for ($k = 0; $k < count($reservas); $k++) {
        if (date("Y-m-d", $cont) == substr($reservas[$k]['fecha'], 0, 10)) {
            $reservado = true;
            break;
        }
    }
    // this is partidos
    for ($l = 0; $l < count($partidos); $l++) {
        if (date("Y-m-d", $cont) == substr($partidos[$l]['fecha'], 0, 10)) {
            $partido = true;
            $stmt_part = $conexion->prepare("select E1.logo as logo1, E2.logo as logo2 from equipo E1, equipo E2, partido P WHERE P.fecha = :fecha and P.campo_id = :campo and E1.nombre = P.equipo_nombre_1 and E2.nombre = P.equipo_nombre_2");
            $parameters_part = [':fecha'=>$partidos[$l]['fecha'], ':campo'=>$partidos[$l]['campo_id']];
            $stmt_part->execute($parameters_part);
            $equipos_part = $stmt_part->fetchAll(PDO::FETCH_ASSOC);
            break;
        }
    }
    if ($reservado == true) : ?>
        <td><a class="reservado" href="reserva.php"><p class="date"><?= date("j", $cont) ?></p><p>Reserva</p></a></td>
    <?php elseif ($partido == true) : ?>
        <td class="partido">
            <form action="partido.php" method="post">
                <input type="hidden" name="fecha" value="<?=$partidos[$l]['fecha']?>">
            </form>
            <p class="date"><?= date("j", $cont) ?></p>
            <div>
                <img src="<?= $equipos_part[0]['logo1'] ?>" />
                <i> - VS - </i>
                <img src="<?= $equipos_part[0]['logo2'] ?>" />
            </div>
        </td>
    <?php else: ?>
        <td><p class="date"><?= date("j", $cont) ?></p></td>
    <?php
    endif;
    $cont += 86400;
}

if(isset($_POST['accion'])) {
    $data = json_decode($_POST['accion'], true);
    if ($_SESSION['entidad'] == "arbitro") {
        $stmt1 = $conexion->prepare("SELECT fecha, equipo_nombre_1, equipo_nombre_2, campo_id FROM partido WHERE arbitro_dni = :dni");
        $stmt2 = $conexion->prepare("SELECT fecha FROM reserva WHERE arbitro_dni = :dni");
        $parameters = [':dni' => $data['entity']];

    } else if ($_SESSION['entidad'] == "jugador") {
        $stmt1 = $conexion->prepare("SELECT fecha, equipo_nombre_1, equipo_nombre_2, campo_id FROM partido WHERE (equipo_nombre_1 = :equipo) OR (equipo_nombre_2 = :equipo)");
        $stmt2 = $conexion->prepare("SELECT fecha FROM reserva WHERE equipo_nombre = :equipo");
        $parameters = [':equipo' => $data['entity']];
    }
    $stmt1->execute($parameters);
    $partidos = $stmt1->fetchAll(PDO::FETCH_ASSOC);
    $stmt2->execute($parameters);
    $reservas = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    if ($data['opcion'] == "anterior") {
        $_SESSION['mes']--;
        if ($_SESSION['mes'] == 00) {
            $_SESSION['mes'] = 12;
            $_SESSION['anyo']--;
        }
    } else if ($data['opcion'] == "siguiente") {
        $_SESSION['mes']++;
        if ($_SESSION['mes'] == 13) {
            $_SESSION['mes'] = 1;
            $_SESSION['anyo']++;
        }
    } else {
        $_SESSION['mes'] = $mes;
        $_SESSION['anyo'] = $anyo;
    }
    $cont = mktime(null, null, null, $_SESSION['mes'], 01, $_SESSION['anyo']);
    if ($mes!=null) :?>
        <table align="center">
        <tr align="center">
            <th colspan="2">
                <button type="button" id="anterior"><</button>
            </th>
            <th colspan="3" id="mes"><?= $i_mes[$_SESSION['mes'] - 1]." - ".$_SESSION['anyo'] ?></th>
            <th colspan="2">
                <button type="button" id="siguiente">></button>
            </th>
        </tr>
        <tr>
            <?php for($i=0; $i<7; $i++): ?>
            <th><?= $i_dia[$i] ?></th>
            <?php endfor; ?>
        </tr>
        <?php for ($i = 0; $i < 6; $i++): ?>
            <tr>
            <?php for ($j = 0; $j < 7; $j++) {
                if ($_SESSION['mes'] == date("m", $cont)) {
                    if ($i == 0) {
                        if ($j == 0 && date("l", $cont) == "Monday") {
                            obtenerDia($cont, $reservas, $partidos, $conexion);
                        } else if ($j == 1 && date("l", $cont) == "Tuesday") {
                            obtenerDia($cont, $reservas, $partidos, $conexion);
                        } else if ($j == 2 && date("l", $cont) == "Wednesday") {
                            obtenerDia($cont, $reservas, $partidos, $conexion);
                        } else if ($j == 3 && date("l", $cont) == "Thursday") {
                            obtenerDia($cont, $reservas, $partidos, $conexion);
                        } else if ($j == 4 && date("l", $cont) == "Friday") {
                            obtenerDia($cont, $reservas, $partidos, $conexion);
                        } else if ($j == 5 && date("l", $cont) == "Saturday") {
                            obtenerDia($cont, $reservas, $partidos, $conexion);
                        } else if ($j == 6 && date("l", $cont) == "Sunday") {
                            obtenerDia($cont, $reservas, $partidos, $conexion);
                        } else {
                            echo "<td></td>";
                        }
                    } else {
                        obtenerDia($cont, $reservas, $partidos, $conexion);
                    }
                }
            }
            echo "</tr>";
            endfor; ?>
        </table>
<?php endif;
    }
    ?>

