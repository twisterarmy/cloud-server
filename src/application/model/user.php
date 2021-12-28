<?php

class ModelUser extends Model {

  public function userNameExists(string $userName) {

    try {

      $query = $this->_db->prepare("SELECT COUNT(*) AS `total` from `user` WHERE `userName` = ? LIMIT 1");

      $query->execute([$userName]);

      return $query->rowCount() ? $query->fetch()['total'] : 0;

    } catch (PDOException $e) {

      trigger_error($e->getMessage());
      return false;
    }
  }

  public function addUser(int $blockId, string $userName) {

    try {

      $query = $this->_db->prepare("INSERT INTO `user` SET `blockId` = ?,
                                                           `userName` = ?");

      $query->execute([$blockId, $userName]);

      return $this->_db->lastInsertId();

    } catch (PDOException $e) {

      trigger_error($e->getMessage());
      return false;
    }
  }
}