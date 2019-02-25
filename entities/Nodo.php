<?php
/**
 * Created by PhpStorm.
 * User: Ian
 * Date: 24/02/2019
 * Time: 23:03
 */

class Nodo {
    public $dato;
    public $siguiente;

    public function __construct($dato) {
        $this->dato = $dato;
        $this->siguiente = NULL;
    }

    /* -- GETTERS & SETTERS -- */

    public function getDato() {
        return $this->dato;
    }

    public function setDato($dato) {
        $this->dato = $dato;
        return $this;
    }

    public function getSiguiente() {
        return $this->siguiente;
    }

    public function setSiguiente($siguiente) {
        $this->siguiente = $siguiente;
        return $this;
    }

}