<?php
/**
 * Created by PhpStorm.
 * User: ianmo
 * Date: 10/12/2018
 * Time: 16:42
 */

class Connection {
    public static function make() {
        try {
            $opciones=[PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES utf8",
                PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT=>true];
            $connection = new PDO('mysql:host=127.0.0.1;dbname=deporvereda3', 'becario', "1111", $opciones);
        } catch (PDOException $PDOException) {
            die($PDOException->getMessage());
        }
        return $connection;
    }
}