<?php

class Filter {

  public static function userName(mixed $userName) {

    return preg_replace('/[^a-zA-Z0-9_]+/u', '', $userName);
  }

  public static function userPrivateKey(mixed $userPrivateKey) {

    return preg_replace('/[^a-zA-Z0-9_]+/u', '', $userPrivateKey);
  }

  public static function blockHash(mixed $blockHash) {

    return preg_replace('/[^a-zA-Z0-9]+/u', '', $blockHash);
  }

  public static function string(mixed $string) {

    return (string) $string;
  }

  public static function int(mixed $int) {

    return (int) $int;
  }
}
