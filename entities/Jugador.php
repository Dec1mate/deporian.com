<?php
/**
 * Created by PhpStorm.
 * User: ianmo
 * Date: 26/01/2019
 * Time: 16:57
 */
//require_once "../database/Connection.php";
class Jugador {
    protected $dni;
    protected $nombre;
    protected $edad;
    protected $altura;
    protected $foto;
    protected $contrasenya;
    protected $equipo;

    public function __construct($dni="", $nombre="", $edad="", $altura="", $foto="", $contrasenya="", $equipo="") {
        $this->dni = $dni;
        $this->nombre = $nombre;
        $this->edad = $edad;
        $this->altura = $altura;
        $this->foto = $foto;
        $this->contrasenya = $contrasenya;
        $this->equipo = $equipo;
    }

    public function updateNombre($nombre) {
        $conexion = Connection::make();
        $stmt = $conexion->prepare("UPDATE jugador SET nombre = :nombre WHERE dni = :dni");
        $parameters = [':nombre'=>$nombre, ':dni'=>$this->dni];
        $stmt->execute($parameters);
    }

    public function updateEdad($edad) {
        $conexion = Connection::make();
        $stmt = $conexion->prepare("UPDATE jugador SET edad = :edad WHERE dni = :dni");
        $parameters = [':edad'=>$edad, ':dni'=>$this->dni];
        $stmt->execute($parameters);
    }

    public function updateAltura($altura) {
        $conexion = Connection::make();
        $stmt = $conexion->prepare("UPDATE jugador SET altura = :altura WHERE dni = :dni");
        $parameters = [':altura'=>$altura, ':dni'=>$this->dni];
        $stmt->execute($parameters);
    }

    public function updateFoto($foto) {
        $conexion = Connection::make();
        $stmt = $conexion->prepare("UPDATE jugador SET foto = :foto WHERE dni = :dni");
        if ($foto['size'] != 0) {
            $rutaImagen = 'IMGs\jugadores\\' . $foto['name'];
            if (!((strpos($foto['type'], 'png') || strpos($foto['type'], 'jpg') || strpos($foto['type'], 'jpeg')))) {
                echo("La extension no es correcta.");
            } else if (is_file($rutaImagen) === true) {
                $idUnico = time();
                $nombreArchivo = $idUnico . '_' . $foto['name'];
                $rutaImagen = 'IMGs\jugadores\\' . $nombreArchivo;
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
        $stmt = $conexion->prepare("UPDATE jugador SET contrasenya = :contrasenya WHERE dni = :dni");
        $parameters = [':contrasenya'=>password_hash($contrasenya, PASSWORD_DEFAULT, ['cost' => 10]), ':dni'=>$this->dni];
        $stmt->execute($parameters);
    }

    public function reservar($dia, $hora, $campo) {
        $fecha = $dia." ".$hora;
        $conexion = Connection::make();
        $stmt = $conexion->prepare("INSERT INTO reserva VALUES (:equipo, :campo, :fecha)");
        $parameters = [':equipo'=>$this->equipo, ':campo'=>$campo, ':fecha'=>$fecha];
        $stmt->execute($parameters);
    }

    public function apuntarse() {
        $conexion = Connection::make();
        $stmt1 = $conexion->prepare("select equipo_nombre from pertenece where 12>(select count(*) from pertenece group by liga_edicion);");
        $stmt1->execute();
        $nums_eq = $stmt1->fetchAll(PDO::FETCH_ASSOC);
        if (empty($nums_eq)) {
            if ($this->contarLigas()==0) {
                $liga_actual=1;
                $stmt3 = $conexion->prepare("INSERT INTO liga VALUES()");
                $stmt3->execute();
                $this->anyadirEquipo($nums_eq, $liga_actual);
            } else {
                $stmt2 = $conexion->prepare("select MAX(P.fecha) as fecha from partido P, liga L where L.edicion = P.liga_edicion and L.edicion=(select MAX(edicion) from liga);");
                $stmt2->execute();
                $fecha_ult_partido = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                $dia_act = date("Y-m-d H:i:s", time());
                if ($fecha_ult_partido[0]['fecha']>=$dia_act) {
                    echo "Error, ya hay una liga en marcha";
                } else {
                    $liga_actual = $this->contarLigas() + 1;
                    $stmt3 = $conexion->prepare("INSERT INTO liga VALUES()");
                    $stmt3->execute();
                    $this->anyadirEquipo($nums_eq, $liga_actual);
                }
            }
        } else {
            $liga_actual = $this->contarLigas();
            $this->anyadirEquipo($nums_eq, $liga_actual);
        }
    }

    public function contarLigas() {
        $conexion = Connection::make();
        $stmt_pre = $conexion->prepare("SELECT * FROM liga");
        $stmt_pre->execute();
        $cant_ligas = $stmt_pre->fetchAll(PDO::FETCH_ASSOC);
        return count($cant_ligas);
    }

    public function anyadirEquipo($nums_eq, $liga_actual) {
        $conexion = Connection::make();
        $stmt_pre = $conexion->prepare("SELECT * FROM pertenece WHERE equipo_nombre = :equipo AND liga_edicion = :liga");
        $parameters = [':equipo'=>$this->equipo, ':liga'=>$liga_actual];
        $stmt_pre->execute($parameters);
        $result = $stmt_pre->fetchAll(PDO::FETCH_ASSOC);
        if (!$result) {
            $stmt = $conexion->prepare("INSERT INTO pertenece VALUES(:equipo, :liga)");
            $parameters = [':equipo'=>$this->equipo, ':liga'=>$liga_actual];
            $stmt->execute($parameters);
            $texto = "El equipo ".$this->equipo." se ha inscrito a la ".$liga_actual." edicion de la liga DeporIan";
            $settings = array(
                'consumer_key'=>'72xgvwIlNfSotN66afqZGjASG',
                'consumer_secret'=>'NbXofTGJ78kSoRoPoMFIuiJ1ziwi1shfuE8ORwiKhtTN9k0eXY',
                'oauth_access_token'=>'1092417797588766720-tSwJjv0rArKBDAiGdWsJxWdZ4K0Uu5',
                'oauth_access_token_secret'=>'WgQMDEOpJ77k0Vxb4QNZyrzkqqXdO5g0VNIhapbi3weX5');
            publicarTweet($settings, $texto);
            if(count($nums_eq)==11) {
                $this->empezarLiga();
            }
        } else {
            echo "ERROR EL EQUIPO YA ESTA INSCRITO EN ESA LIGA!";
        }
    }

    public function empezarLiga() {
        set_time_limit(600);
        $conexion = Connection::make();
        $fecha = time();
        $horas = ['09:00:00', '11:00:00', '13:00:00', '17:00:00', '19:00:00', '21:00:00'];
        $stmt = $conexion->prepare("SELECT equipo_nombre FROM pertenece WHERE liga_edicion = :liga");
        $parameters = [':liga'=>$this->contarLigas()];
        $stmt->execute($parameters);
        $equipos_liga = $stmt->fetchAll(PDO::FETCH_ASSOC);
        for ($i=0; $i<11; $i++) {
            $fecha += 604800;
            if(date("l", $fecha)!="Saturday" && $i==0) {
                do {
                    $fecha += 86400;
                } while(date("l", $fecha)!="Saturday");
            }
            $fechas = [date("Y-m-d", $fecha), date("Y-m-d", $fecha + 86400)];
            $equipos_aux = $equipos_liga;
            /*do {
                shuffle($equipos_aux);
                $foo = false;
                for($k=0; $k<6; $k++) {
                    $stmt2 = $conexion->prepare("SELECT equipo_nombre_1 FROM partido WHERE liga_edicion = :liga AND ((equipo_nombre_1 = :equipo1 AND equipo_nombre_2 = :equipo2) OR (equipo_nombre_1 = :equipo2 AND equipo_nombre_2 = :equipo1))");
                    $parameters2 = [':liga'=>$this->contarLigas(), ':equipo1'=>$equipos_aux[($k*2)]['equipo_nombre'], ':equipo2'=>$equipos_aux[($k*2) + 1]['equipo_nombre']];
                    $stmt2->execute($parameters2);
                    $existe = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                    if(!empty($existe)) {
                        $foo = true;
                    }
                }
            } while ($foo == true);
            for ($j=0; $j<6; $j++) {
                $stmt_arb = $conexion->prepare("SELECT * FROM arbitro");
                $stmt_arb->execute();
                $arbitros = $stmt_arb->fetchAll(PDO::FETCH_ASSOC);
                $stmt_camp = $conexion->prepare("SELECT * FROM campo");
                $stmt_camp->execute();
                $campos = $stmt_camp->fetchAll(PDO::FETCH_ASSOC);
                do {
                    $fecha_nueva = $fechas[rand(0, count($fechas)-1)]." ".$horas[rand(0, count($horas)-1)];
                    $campo_nuevo = $campos[rand(0, count($campos)-1)];
                    $arbitro_nuevo = $arbitros[rand(0, count($arbitros)-1)];
                    $stmt_aux = $conexion->prepare("SELECT * FROM partido WHERE fecha = :fecha AND campo_id = :campo AND NOT EXISTS (SELECT * FROM partido WHERE fecha = :fecha AND campo_id <> :campo AND arbitro_dni = :dni);");
                    $parameters_aux = [':fecha'=>$fecha_nueva, ':campo'=>$campo_nuevo['id'], ':dni'=>$arbitro_nuevo['dni']];
                    $stmt_aux->execute($parameters_aux);
                    $partido = $stmt_aux->fetchAll(PDO::FETCH_ASSOC);
                } while(count($partido)>0);
                $num_ligas = $this->contarLigas();
                $stmt_meter = $conexion->prepare("INSERT INTO partido(equipo_nombre_1, equipo_nombre_2, arbitro_dni, campo_id, fecha, jornada_numero, liga_edicion) VALUES (:equipo1, :equipo2, :arbitro, :campo, :fecha, :jornada, :liga)");
                $parameters_meter = [':equipo1'=>$equipos_aux[($j*2)]['equipo_nombre'], ':equipo2'=>$equipos_aux[($j*2)+1]['equipo_nombre'], ':arbitro'=>$arbitro_nuevo['dni'], ':campo'=>$campo_nuevo['id'], ':fecha'=>$fecha_nueva, ':jornada'=>$i+1, ':liga'=>$num_ligas];
                $stmt_meter->execute($parameters_meter);
            }*/
        }

        for ($i=0; $i<12; $i++) {
            for ($j=0; $j<12; $j++) {
                if($i!=$j && $i>$j) {
                    $stmt_ins = $conexion->prepare("INSERT INTO partido (equipo_nombre_1, equipo_nombre_2, liga_edicion) VALUES (:equipo1, :equipo2, :liga)");
                    $parameters_ins = [':equipo1'=>$equipos_liga[$i]['equipo_nombre'], ':equipo2'=>$equipos_liga[$j]['equipo_nombre'], ':liga'=>$this->contarLigas()];
                    $stmt_ins->execute($parameters_ins);
                }
            }
        }

        $stmt_partidos = $conexion->prepare("SELECT * FROM partido WHERE liga_edicion = :liga");
        $parameters = [':liga'=>$this->contarLigas()];
        $stmt_partidos->execute($parameters);
        $partidos = $stmt_partidos->fetchAll(PDO::FETCH_ASSOC);
        shuffle($partidos);
        $new_stmt_partido = $conexion->prepare("SELECT * FROM partido WHERE liga_edicion = :liga AND jornada_numero = :jornada AND (equipo_nombre_1 = :equipo1 OR equipo_nombre_2 = :equipo1 OR equipo_nombre_1 = :equipo2 OR equipo_nombre_2 = :equipo2)");
        //$new_stmt_partido = $conexion->prepare("SELECT * FROM partido WHERE liga_edicion = :liga AND equipo_nombre_1 NOT IN (SELECT * FROM partido WHERE (equipo_nombre_1 = :equipo1 OR equipo_nombre_2 = :equipo2) AND jornada_numero = :jornada) AND equipo_nombre_2 NOT IN (SELECT * FROM partido WHERE (equipo_nombre_1 = :equipo1 OR equipo_nombre_2 = :equipo2) AND jornada_numero = :jornada)");
        for ($i=1; $i<=11; $i++) {
            for ($j=0; $j<6; $j++) {
                for($k=0; $k<count($partidos); $k++) {
                    $new_parameters = [':liga'=>$this->contarLigas(), ':jornada'=>$i, ':equipo1'=>$partidos[$k]['equipo_nombre_1'], ':equipo2'=>$partidos[$k]['equipo_nombre_2']];
                    $new_stmt_partido->execute($new_parameters);
                    $partidos_jornada = $new_stmt_partido->fetchAll(PDO::FETCH_ASSOC);
                    if(empty($partidos_jornada)) {
                        $stmt_update_partido = $conexion->prepare("UPDATE partido SET jornada_numero = :jornada WHERE equipo_nombre_1 = :equipo1 AND equipo_nombre_2 = :equipo2");
                        $parameters_update_partido = [':jornada'=>$i, ':equipo1'=>$partidos[$k]['equipo_nombre_1'], ':equipo2'=>$partidos[$k]['equipo_nombre_2']];
                        $stmt_update_partido->execute($parameters_update_partido);
                        array_splice($partidos, $k, 1);
                        break;
                    }
                }
                echo count($partidos)."<br>";
            }
        }

    }

    /* -- GETTERS Y SETTERS -- */

    public function getDni(): string {
        return $this->dni;
    }

    public function setDni(string $dni): Jugador {
        $this->dni = $dni;
        return $this;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function setNombre(string $nombre): Jugador {
        $this->nombre = $nombre;
        return $this;
    }

    public function getEdad(): string {
        return $this->edad;
    }

    public function setEdad(string $edad): Jugador {
        $this->edad = $edad;
        return $this;
    }

    public function getAltura(): string {
        return $this->altura;
    }

    public function setAltura(string $altura): Jugador {
        $this->altura = $altura;
        return $this;
    }

    public function getFoto(): string {
        return $this->foto;
    }

    public function setFoto(string $foto): Jugador {
        $this->foto = $foto;
        return $this;
    }

    public function getContrasenya(): string {
        return $this->contrasenya;
    }

    public function setContrasenya(string $contrasenya): Jugador {
        $this->contrasenya = $contrasenya;
        return $this;
    }

    public function getEquipo(): string {
        return $this->equipo;
    }

    public function setEquipo(string $equipo): Jugador {
        $this->equipo = $equipo;
        return $this;
    }
}