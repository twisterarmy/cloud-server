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

  public static function base64(string $string) {

    if (base64_encode(base64_decode($string, true)) === $string) {

      return true;

    } else {

      return false;
    }
  }

  public static function base64image(string $string) {

    $string = str_replace([
      'data:image/jpeg;base64,',
      'data:image/jpg;base64,',
      'data:image/gif;base64,',
      'data:image/png;base64,',
      'data:image/webp;base64,',
    ], '', $string);

    if (self::base64($string) && imagecreatefromstring(base64_decode($string))) {

      return true;

    } else {

      return false;
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
