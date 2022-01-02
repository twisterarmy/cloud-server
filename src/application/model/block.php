<?php

class ModelBlock extends Model {

  public function getTotal() {

    try {

      $query = $this->_db->query("SELECT COUNT(*) AS `total` from `block`");

      return $query->fetch()['total'];

    } catch (PDOException $e) {

      trigger_error($e->getMessage());
      return false;
    }
  }

  public function getThisBlock() {

    try {

      $query = $this->_db->query("SELECT MAX(`blockId`) AS `blockId` FROM `block`");

      return $query->fetch()['blockId'];

    } catch (PDOException $e) {

      trigger_error($e->getMessage());
      return false;
    }
  }

  public function getNextBlock() {

    try {

      $query = $this->_db->query("SELECT COUNT(*) + 1 AS `nextBlock` FROM `block`");

      return $query->fetch()['nextBlock'];

    } catch (PDOException $e) {

      trigger_error($e->getMessage());
      return false;
    }
  }

  public function addBlock(string $hash, int $time) {

    try {

      $query = $this->_db->prepare("INSERT INTO `block` SET `hash` = ?,
                                                            `time` = ?");

      $query->execute([$hash, $time]);

      return $this->_db->lastInsertId();

    } catch (PDOException $e) {

      trigger_error($e->getMessage());
      return false;
    }
  }
}