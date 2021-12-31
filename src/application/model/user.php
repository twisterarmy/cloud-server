<?php

class ModelUser extends Model {

  public function getTotal() {

    try {

      $query = $this->_db->query("SELECT COUNT(*) AS `total` FROM `user`");

      return $query->fetch()['total'];

    } catch (PDOException $e) {

      trigger_error($e->getMessage());
      return false;
    }
  }

  public function getLastUser() {

    try {

      $query = $this->_db->query("SELECT * FROM `user`
                                           JOIN `block` ON (`user`.`blockId` = `block`.`blockId`)
                                           ORDER BY `userId` DESC
                                           LIMIT 1");

      return $query->rowCount() ? $query->fetch() : [];

    } catch (PDOException $e) {

      trigger_error($e->getMessage());
      return false;
    }
  }

  public function getLastRandomUsers(int $limit) {

    try {

      $query = $this->_db->query("SELECT * FROM `user`
                                           JOIN `block` ON (`user`.`blockId` = `block`.`blockId`)
                                           WHERE `block`.`time` > UNIX_TIMESTAMP(CURDATE() - interval 5 YEAR)
                                           ORDER BY RAND()
                                           LIMIT " . (int) $limit);

      return $query->rowCount() ? $query->fetchAll() : [];

    } catch (PDOException $e) {

      trigger_error($e->getMessage());
      return false;
    }
  }

  public function userNameExists(string $userName) {

    try {

      $query = $this->_db->prepare("SELECT `userId` FROM `user` WHERE `userName` = ? LIMIT 1");

      $query->execute([$userName]);

      return $query->rowCount() ? $query->fetch()['userId'] : 0;

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