<?php
session_start();
require_once "database/Connection.php";
require_once "metodos.php";
if(!isset($_SESSION['dni'])) {
    header("Location: index.php");
}
$conexion = Connection::make();
//Busca las ligas existentes
$stmt1 = $conexion->prepare("SELECT * FROM liga");
$stmt1->execute();
$ligas = $stmt1->fetchAll(PDO::FETCH_ASSOC);

if(empty($ligas)) {
    //Si no encuentra ninguna devuelve el string opcion1 que lanzara el metodo que crea la liga
    echo "opcion1";
} else {
    //Si la encuentra mira cuantos equipos hay
    $stmt2 = $conexion->prepare("SELECT * FROM pertenece WHERE liga_edicion = :liga");
    $parameters2 = [":liga"=>count($ligas)];
    $stmt2->execute($parameters2);
    $equipos_registrados = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    if(count($equipos_registrados)<12) {
        //Si hay menos de 12 devuelve el string opcion1 que lanzara el metodo que crea la liga
        echo "opcion1";
    } else {
        //Si no mira cuando fue el ultimo partido
        $stmt3 = $conexion->prepare("select MAX(P.fecha) as fecha from partido P, liga L where L.edicion = P.liga_edicion and L.edicion=(select MAX(edicion) from liga)");
        $stmt3->execute();
        $fecha_max = $stmt3->fetchAll(PDO::FETCH_ASSOC);
        $dia_act = date("Y-m-d H:i:s", time());
        if($dia_act>$fecha_max[0]['fecha']) {
            //Si ya ha pasado devuelve el string opcion1 que lanzara el metodo que crea la liga
            echo "opcion1";
        } else {
            //Si no devuelve la fecha del ultimo partido, haciendo que no pueda apuntarse y indicando a partir de cuando podra
            echo $fecha_max[0]['fecha'];
        }
    }
}
?>