<?php
/**
 * Created by PhpStorm.
 * User: ianmo
 * Date: 26/01/2019
 * Time: 17:10
 */
//require_once "../database/Connection.php";
class Arbitro {
    protected $dni;
    protected $nombre;
    protected $edad;
    protected $altura;
    protected $foto;
    protected $contrasenya;

    public function __construct($dni="", $nombre="", $edad="", $altura="", $foto="", $contrasenya="") {
        $this->dni = $dni;
        $this->nombre = $nombre;
        $this->edad = $edad;
        $this->altura = $altura;
        $this->foto = $foto;
        $this->contrasenya = $contrasenya;
    }

    public function updateNombre($nombre) {
        $conexion = Connection::make();
        $stmt = $conexion->prepare("UPDATE arbitro SET nombre = :nombre WHERE dni = :dni");
        $parameters = [':nombre'=>$nombre, ':dni'=>$this->dni];
        $stmt->execute($parameters);
    }

    public function updateEdad($edad) {
        $conexion = Connection::make();
        $stmt = $conexion->prepare("UPDATE arbitro SET edad = :edad WHERE dni = :dni");
        $parameters = [':edad'=>$edad, ':dni'=>$this->dni];
        $stmt->execute($parameters);
    }

    public function updateAltura($altura) {
        $conexion = Connection::make();
        $stmt = $conexion->prepare("UPDATE arbitro SET altura = :altura WHERE dni = :dni");
        $parameters = [':altura'=>$altura, ':dni'=>$this->dni];
        $stmt->execute($parameters);
    }

    public function updateFoto($foto) {
        $conexion = Connection::make();
        $stmt = $conexion->prepare("UPDATE jugador SET foto = :foto WHERE dni = :dni");
        if ($foto['size'] != 0) {
            $rutaImagen = 'IMGs\arbitros\\' . $_FILES['su_file']['name'];
            if (!((strpos($foto['type'], 'png') || strpos($foto['type'], 'jpg') || strpos($foto['type'], 'jpeg')))) {
                echo("La extension no es correcta.");
            } else if (is_file($rutaImagen) === true) {
                $idUnico = time();
                $nombreArchivo = $idUnico . '_' . $foto['name'];
                $rutaImagen = 'IMGs\arbitros\\' . $nombreArchivo;
            }
            move_uploaded_file($foto['tmp_name'], $rutaImagen);
        } else {
            $rutaImagen = "IMGs\generic.png";
        }
        $parameters = [':foto'=>$rutaImagen, ':dni'=>$this->dni];
        $stmt->execute($parameters);
    }

    public function updateContrasenya($contrasenya) {
        $conexion = Connection::make();
        $stmt = $conexion->prepare("UPDATE arbitro SET contrasenya = :contrasenya WHERE dni = :dni");
        $parameters = [':contrasenya'=>password_hash($contrasenya, PASSWORD_DEFAULT, ['cost' => 10]), ':dni'=>$this->dni];
        $stmt->execute($parameters);
    }

    public function amonestar($equipo, $fecha) {
        $conexion=Connection::make();
        $stmt = $conexion->prepare("INSERT INTO amonesta VALUES (:dni, :equipo, :fecha)");
        $parameters = [':dni'=>$this->dni, ':equipo'=>$equipo, ':fecha'=>$fecha];
        $stmt->execute($parameters);
    }

    public function ponerResultado($equipo1, $equipo2, $goles1, $goles2, $fecha) {
        $conexion = Connection::make();
        $stmt1 = $conexion->prepare("UPDATE partido SET goles_1 = :goles1, goles_2 = :goles2 WHERE fecha = :fecha AND arbitro_dni = :dni");
        $parameters1 = [':goles1'=>$goles1, ':goles2'=>$goles2, ':fecha'=>$fecha, ':dni'=>$this->dni];
        $stmt1->execute($parameters1);
        $stmt2 = $conexion->prepare("SELECT puntos FROM equipo WHERE nombre = :nombre1 or nombre = :nombre2");
        $parameters2 = [':nombre1'=>$equipo1, ':nombre2'=>$equipo2];
        $stmt2->execute($parameters2);
        $puntos = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        $stmt3 = $conexion->prepare("UPDATE equipo SET puntos = :puntos WHERE nombre = :nombre");
        if($goles1>$goles2) {
            $parameters3 = [':puntos'=>$puntos[0]['puntos']+3, ':nombre'=>$equipo1];
            $stmt3->execute($parameters3);
        } else if($goles1<$goles2) {
            $parameters3 = [':puntos'=>$puntos[1]['puntos']+3, ':nombre'=>$equipo2];
            $stmt3->execute($parameters3);
        } else {
            $parameters3_1 = [':puntos'=>$puntos[0]['puntos']+1, ':nombre'=>$equipo1];
            $stmt3->execute($parameters3_1);
            $parameters3_2 = [':puntos'=>$puntos[1]['puntos']+1, ':nombre'=>$equipo2];
            $stmt3->execute($parameters3_2);
        }
        //faltan goles y tweets
        for($i=0; $i<$goles1; $i++) {

        }
    }

    /* -- GETTERS Y SETTERS -- */

    public function getDni(): string {
        return $this->dni;
    }

    public function setDni(string $dni): Arbitro {
        $this->dni = $dni;
        return $this;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function setNombre(string $nombre): Arbitro {
        $this->nombre = $nombre;
        return $this;
    }

    public function getEdad(): string {
        return $this->edad;
    }

    public function setEdad(string $edad): Arbitro {
        $this->edad = $edad;
        return $this;
    }

    public function getAltura(): string {
        return $this->altura;
    }

    public function setAltura(string $altura): Arbitro {
        $this->altura = $altura;
        return $this;
    }

    public function getFoto(): string {
        return $this->foto;
    }

    public function setFoto(string $foto): Arbitro {
        $this->foto = $foto;
        return $this;
    }

    public function getContrasenya(): string {
        return $this->contrasenya;
    }

    public function setContrasenya(string $contrasenya): Arbitro {
        $this->contrasenya = $contrasenya;
        return $this;
    }
}