<?php

class Model {

  protected $_db;

  public function __construct(string $database,
                              string $hostname,
                              int    $port,
                              string $user,
                              string $password) {
    try {

      $this->_db = new PDO('mysql:dbname=' . $database . ';host=' . $hostname . ';port=' . $port . ';charset=utf8', $user, $password, [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8']);
      $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->_db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    } catch(PDOException $e) {

      trigger_error($e->getMessage());

    }
  }
}