<?php

class ModelFollow extends Model {

  public function getFollow($userId) {

    try {

      $query = $this->_db->execute("SELECT `followUser`.`userId` FROM `followUser`
                                                                 JOIN `follow` ON (`follow`.`followId` = `followUser`.`followId`)

                                                                 WHERE `follow`.`userId` = ?

                                                                 GROUP BY `followUser`.`userId`
                                                                 ORDER BY `follow`.`seq` DESC");

      $query->execute([$userId]);

      return $query->rowCount() ? $query->fetch() : [];

    } catch (PDOException $e) {

      trigger_error($e->getMessage());
      return false;
    }
  }
}