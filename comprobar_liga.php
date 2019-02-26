<?php
require_once "database/Connection.php";
$conexion = Connection::make();

$stmt1 = $conexion->prepare("SELECT * FROM liga");
$stmt1->execute();
$ligas = $stmt1->fetchAll(PDO::FETCH_ASSOC);

if(empty($ligas)) {
    echo "opcion1";
} else {
    $stmt2 = $conexion->prepare("SELECT * FROM pertenece WHERE liga_edicion = :liga");
    $parameters2 = [":liga"=>count($ligas)];
    $stmt2->execute($parameters2);
    $equipos_registrados = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    if(count($equipos_registrados)<12) {
        echo "opcion1";
    } else {
        $stmt3 = $conexion->prepare("select MAX(P.fecha) as fecha from partido P, liga L where L.edicion = P.liga_edicion and L.edicion=(select MAX(edicion) from liga)");
        $stmt3->execute();
        $fecha_max = $stmt3->fetchAll(PDO::FETCH_ASSOC);
        $dia_act = date("Y-m-d H:i:s", time());
        if($dia_act>$fecha_max[0]['fecha']) {
            echo "opcion1";
        } else {
            echo $fecha_max[0]['fecha'];
        }
    }
}
?>