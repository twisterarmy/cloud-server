<?php

class ModelProfile extends Model {

  public function get(int $userId) {

    try {

      $query = $this->_db->prepare("SELECT * FROM  `profile`
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

  public function getMaxSeq(int $userId) {

    try {

      $query = $this->_db->prepare("SELECT MAX(`seq`) + 1 AS `revision` FROM  `profile` WHERE `userId` = ?");

      $query->execute([$userId]);

      return (int) $query->fetch()['revision'];

    } catch (PDOException $e) {

      trigger_error($e->getMessage());
      return false;
    }
  }

  public function versionExists(int $userId, int $blockId, int $seq) {

    try {

      $query = $this->_db->prepare("SELECT COUNT(*) AS `total` FROM  `profile`
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
                      string $fullName,
                      string $bio,
                      string $location,
                      string $url,
                      string $bitMessage,
                      string $tox) {

    try {

      $query = $this->_db->prepare("INSERT INTO `profile` SET `userId`     = ?,
                                                              `blockId`    = ?,
                                                              `seq`        = ?,
                                                              `time`       = ?,
                                                              `fullName`   = ?,
                                                              `bio`        = ?,
                                                              `location`   = ?,
                                                              `url`        = ?,
                                                              `bitMessage` = ?,
                                                              `tox`        = ?");

      $query->execute([$userId,
                       $blockId,
                       $seq,
                       $time,
                       $fullName,
                       $bio,
                       $location,
                       $url,
                       $bitMessage,
                       $tox]);

      return $this->_db->lastInsertId();

    } catch (PDOException $e) {

      trigger_error($e->getMessage());
      return false;
    }
  }
}