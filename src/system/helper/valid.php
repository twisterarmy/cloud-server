<?php

class Valid {

  public static function userPost(string $userPost) {

    $length = mb_strlen($userPost);

    if ($length < 1 || $length > 140) {

      return false;

    } else {

      return true;

    }
  }

  public static function userName(string $userName) {

    if (preg_match('/[^a-zA-Z0-9_]+/u', $userName)) {

      return false;

    } else if (mb_strlen($userName) > 16) {

      return false;

    } else {

      return true;

    }
  }

  public static function userPrivateKey(string $userPrivateKey) {

    if (preg_match('/[^a-zA-Z0-9_]+/u', $userPrivateKey)) {

      return false;

    } else {

      return true;

    }
  }
}
