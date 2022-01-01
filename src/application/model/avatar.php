<?php

class ModelAvatar extends Model {

  public function get(int $userId) {

    try {

      $query = $this->_db->prepare("SELECT * FROM  `avatar`
                                             WHERE `userId` = ?

                                             ORDER BY `seq` DESC
                                             LIMIT 1");

      $query->execute([$userId]);

      return $query->fetch();

    } catch (PDOException $e) {

      trigger_error($e->getMessage());
      return false;
    }
  }

  public function versionExists(int $userId, int $blockId, int $seq) {

    try {

      $query = $this->_db->prepare("SELECT COUNT(*) AS `total` FROM  `avatar`
                                                               WHERE `userId` = ? AND `blockId` = ? AND `seq` = ?");

      $query->execute([$userId, $blockId, $seq]);

      return $query->fetch()['total'];

    } catch (PDOException $e) {

      trigger_error($e->getMessage());
      return false;
    }
  }

  public function add(int    $userId,
                      int    $blockId,
                      int    $seq,
                      int    $time,
                      string $data) {

    try {

      $query = $this->_db->prepare("INSERT INTO `avatar` SET  `userId`     = ?,
                                                              `blockId`    = ?,
                                                              `seq`        = ?,
                                                              `time`       = ?,
                                                              `data`       = ?");

      $query->execute([$userId,
                       $blockId,
                       $seq,
                       $time,
                       $data]);

      return $this->_db->lastInsertId();

    } catch (PDOException $e) {

      trigger_error($e->getMessage());
      return false;
    }
  }
}