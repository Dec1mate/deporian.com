<?php
require_once "Nodo.php";
/**
 * Created by PhpStorm.
 * User: Ian
 * Date: 24/02/2019
 * Time: 23:02
 */

class Lista {
    protected $inicio;

    public function __construct($dato) {
        $this->inicio = new Nodo($dato);
    }

    public function anyadirNodoFinal($dato) {
        $aux = $this->inicio;
        while($aux->siguiente!=NULL) {
            $aux = $aux->siguiente;
        }
        $aux->siguiente = new Nodo($dato);
    }

    public function anyadirNodoPrincipio($dato) {
        $aux = $this->inicio;
        $this->inicio = new Nodo($dato);
        $this->inicio->siguiente = $aux;
    }

    public function anyadirNodoPosicion($dato, $posicion) {
        $aux = $this->inicio;
        $cont = 1;
        while($aux->siguiente!=NULL && $cont+1!=$posicion) {
            $aux = $aux->siguiente;
            $cont++;
        }
        /*$n = new Nodo($dato);
        $n->siguiente = $aux->siguiente->siguiente;
        $aux->siguiente = $n;*/

        $aux_nodo = $aux->siguiente;
        $aux->siguiente = new Nodo($dato);
        $aux->siguiente->siguiente = $aux_nodo;
    }

    public function eliminarNodoInicio() {
        $aux = $this->inicio;
        $nodos = $aux->siguiente;
        unset($aux);
        $this->inicio = $nodos;
    }

    public function eliminarNodoFinal() {
        $aux = $this->inicio;
        while($aux->siguiente->siguiente!=NULL) {
            $aux = $aux->siguiente;
        }
        unset($aux->siguiente);
    }

    public function eliminarNodoPosicion($posicion) {
        $aux = $this->inicio;
        $cont = 1;
        while($aux->siguiente!=NULL && $cont+1!=$posicion) {
            $aux = $aux->siguiente;
            $cont++;
        }
        $aux_nodo = $aux->siguiente->siguiente;
        unset($aux->siguiente);
        $aux->siguiente = $aux_nodo;
    }

    /* -- GETTERS & SETTERS -- */

    public function getInicio() {
        return $this->inicio;
    }

    public function setInicio($inicio) {
        $this->inicio = $inicio;
        return $this;
    }
}