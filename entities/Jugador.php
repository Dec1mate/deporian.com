<?php
require_once "Nodo.php";
require_once "Lista.php";
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
        //Comprobamos si hay alguna liga con menos de 12 equipos apuntados en ella actualmente
        $stmt1 = $conexion->prepare("select equipo_nombre from pertenece where 12>(select count(*) from pertenece group by liga_edicion);");
        $stmt1->execute();
        $nums_eq = $stmt1->fetchAll(PDO::FETCH_ASSOC);
        //Si encontramos algun resultado
        if (empty($nums_eq)) {
            //Si no hay ninguna liga en total creamos una nueva
            if ($this->contarLigas()==0) {
                $liga_actual=1;
                $stmt3 = $conexion->prepare("INSERT INTO liga VALUES()");
                $stmt3->execute();
                $this->anyadirEquipo($nums_eq, $liga_actual);
            } else {
                //Si ya hay alguna liga, comprobamos con la fecha de su ultimo partido si ya se ha acabado o esta en marcha
                $stmt2 = $conexion->prepare("select MAX(P.fecha) as fecha from partido P, liga L where L.edicion = P.liga_edicion and L.edicion=(select MAX(edicion) from liga);");
                $stmt2->execute();
                $fecha_ult_partido = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                $dia_act = date("Y-m-d H:i:s", time());
                if ($fecha_ult_partido[0]['fecha']>=$dia_act) {
                    //Si no ha acabado aun
                    echo "Error, ya hay una liga en marcha";
                } else {
                    //Si ya ha acabado creamos una nueva
                    $liga_actual = $this->contarLigas() + 1;
                    $stmt3 = $conexion->prepare("INSERT INTO liga VALUES()");
                    $stmt3->execute();
                    $this->anyadirEquipo($nums_eq, $liga_actual);
                }
            }
        } else {
            //Si existe una liga con menos de 12 equipos, añadimos el nuestro
            $liga_actual = $this->contarLigas();
            $this->anyadirEquipo($nums_eq, $liga_actual);
        }
    }

    //Esta function cuenta las ligas diferentes guardadas en la base de datos
    public function contarLigas() {
        $conexion = Connection::make();
        $stmt_pre = $conexion->prepare("SELECT * FROM liga");
        $stmt_pre->execute();
        $cant_ligas = $stmt_pre->fetchAll(PDO::FETCH_ASSOC);
        return count($cant_ligas);
    }

    public function anyadirEquipo($nums_eq, $liga_actual) {
        $conexion = Connection::make();
        //Comprobamos si nuestro equipo ya pertenece a la liga
        $stmt_pre = $conexion->prepare("SELECT * FROM pertenece WHERE equipo_nombre = :equipo AND liga_edicion = :liga");
        $parameters = [':equipo'=>$this->equipo, ':liga'=>$liga_actual];
        $stmt_pre->execute($parameters);
        $result = $stmt_pre->fetchAll(PDO::FETCH_ASSOC);
        //En caso negativo lo añadimos
        if (!$result) {
            $stmt = $conexion->prepare("INSERT INTO pertenece VALUES(:equipo, :liga)");
            $parameters = [':equipo'=>$this->equipo, ':liga'=>$liga_actual];
            $stmt->execute($parameters);
            //Y ponemos sus puntos a 0
            $stmt_puntos = $conexion->prepare("UPDATE equipo SET puntos = 0 WHERE nombre = :equipo");
            $parameters_puntos = [':equipo'=>$this->equipo];
            $stmt_puntos->execute($parameters_puntos);
            //Y ponemos un Tweet celebrando que nuestro equipo se ha registrado
            $texto = "El equipo ".$this->equipo." se ha inscrito a la ".$liga_actual." edicion de la liga DeporIan";
            $settings = array(
                'consumer_key'=>'72xgvwIlNfSotN66afqZGjASG',
                'consumer_secret'=>'NbXofTGJ78kSoRoPoMFIuiJ1ziwi1shfuE8ORwiKhtTN9k0eXY',
                'oauth_access_token'=>'1092417797588766720-tSwJjv0rArKBDAiGdWsJxWdZ4K0Uu5',
                'oauth_access_token_secret'=>'WgQMDEOpJ77k0Vxb4QNZyrzkqqXdO5g0VNIhapbi3weX5');
            publicarTweet($settings, $texto);
            //Si con nuestro equipo llegamos al total de 12 que necesita la liga, la empezamos
            if(count($nums_eq)==11) {
                $this->empezarLiga();
            }
        //En caso positivo sale un mensaje feo
        } else {
            echo "ERROR EL EQUIPO YA ESTA INSCRITO EN ESA LIGA!";
        }
    }

    public function empezarLiga() {
        $conexion = Connection::make();
        $fecha = time();
        $horas = ['09:00:00', '11:00:00', '13:00:00', '17:00:00', '19:00:00', '21:00:00'];
        //Guardamos todos los equipos que pertenecen a la liga actual
        $stmt = $conexion->prepare("SELECT equipo_nombre FROM pertenece WHERE liga_edicion = :liga");
        $parameters = [':liga'=>$this->contarLigas()];
        $stmt->execute($parameters);
        $equipos_liga = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //Ponemos sus puntos a 0
        $stmt_reiniciar = $conexion->prepare("UPDATE equipo SET puntos = 0 WHERE nombre = :equipo");
        for ($i=0; $i<count($equipos_liga)-1; $i++) {
            $parameters_reiniciar = [':equipo'=>$equipos_liga[$i]['equipo_nombre']];
            $stmt_reiniciar->execute($parameters_reiniciar);
        }

        //Desordenamos el array de equipos para hacer los partidos mas aleatorios
        shuffle($equipos_liga);
        //Creamos dos listas con el nodo inicial los primeros dos equipos
        $lista_A = new Lista($equipos_liga[0]['equipo_nombre']);
        $lista_B = new Lista($equipos_liga[1]['equipo_nombre']);
        //Arbitros
        $stmt_arb = $conexion->prepare("SELECT * FROM arbitro");
        $stmt_arb->execute();
        $arbitros = $stmt_arb->fetchAll(PDO::FETCH_ASSOC);
        //Campos
        $stmt_camp = $conexion->prepare("SELECT * FROM campo");
        $stmt_camp->execute();
        $campos = $stmt_camp->fetchAll(PDO::FETCH_ASSOC);

        //Dividimos los equipos en las dos listas
        for($i=2; $i<12; $i++) {
            if($i%2==0) {
                $lista_A->anyadirNodoFinal($equipos_liga[$i]['equipo_nombre']);
            } else {
                $lista_B->anyadirNodoFinal($equipos_liga[$i]['equipo_nombre']);
            }
        }


        $stmt_anyadir_partido = $conexion->prepare("INSERT INTO partido(equipo_nombre_1, equipo_nombre_2, arbitro_dni, campo_id, fecha, jornada_numero, liga_edicion) VALUES (:equipo1, :equipo2, :arbitro, :campo, :fecha, :jornada, :liga)");
        //Por cada jornada haremos lo siguiente:
        //Creamos las fechas de los partidos
        //Hacemos que los equipos se enfrenten en orden de lista
        //Cogemos primer valor de la lista 2 y lo ponemos como segundo valor de la lista 1
        //Cogemos el ultimo valor de la lista 1 y lo ponemos como ultimo valor de la lista 2
        for($k = 1; $k<12; $k++) {
            $fecha += 604800;
            if(date("l", $fecha)!="Saturday" && $k==1) {
                do {
                    $fecha += 86400;
                } while(date("l", $fecha)!="Saturday");
            }
            $fechas = [date("Y-m-d", $fecha), date("Y-m-d", $fecha + 86400)];
            $equipo1 = $lista_A->getInicio();
            $equipo2 = $lista_B->getInicio();
            $equipo_aux_1 = $lista_B->getInicio()->dato;
            $equipo_aux_2 = $lista_A->getNodoFinal()->dato;
            for($l=0; $l<6; $l++) {
                do {
                    //Sacamos dia para el partido, arbitro y campo hasta que encontremos una combinacion no usada ya en esa jornada
                    $dia_nuevo = $fechas[rand(0, count($fechas)-1)];
                    $fecha_nueva = $dia_nuevo." ".$horas[rand(0, count($horas)-1)];
                    $campo_nuevo = $campos[rand(0, count($campos)-1)];
                    $arbitro_nuevo = $arbitros[rand(0, count($arbitros)-1)];
                    $stmt_aux = $conexion->prepare("SELECT * FROM partido WHERE fecha LIKE :fecha AND arbitro_dni = :dni");
                    $parameters_aux = [':fecha'=>$dia_nuevo.'%', ':dni'=>$arbitro_nuevo['dni']];
                    $stmt_aux->execute($parameters_aux);
                    $partido = $stmt_aux->fetchAll(PDO::FETCH_ASSOC);
                } while(count($partido)>0);
                $parameters_partido = [':equipo1'=>$equipo1->dato, ':equipo2'=>$equipo2->dato,':arbitro'=>$arbitro_nuevo['dni'], ':campo'=>$campo_nuevo['id'], ':fecha'=>$fecha_nueva, ':jornada'=>$k, ':liga'=>$this->contarLigas()];
                $stmt_anyadir_partido->execute($parameters_partido);
                if(isset($equipo1->siguiente)) {
                    $equipo1 = $equipo1->siguiente;
                    $equipo2 = $equipo2->siguiente;
                }
            }
            $lista_A->anyadirNodoPosicion($equipo_aux_1, 2);
            $lista_B->eliminarNodoInicio();
            $lista_A->eliminarNodoFinal();
            $lista_B->anyadirNodoFinal($equipo_aux_2);

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