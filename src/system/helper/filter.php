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

  public static function fullName(string $string) {

    $string = preg_replace('/[^\s\w]+/u', '', $string);

    return $string;
  }

  public static function location(string $string) {

    $string = preg_replace('/[^\s\w\.\,]+/u', '', $string);

    return $string;
  }

  public static function url(string $string) {

    $string = preg_replace('/[^\w\?\&\=\.\:\/]+/u', '', $string);

    return $string;
  }

  public static function bitMessage(string $string) {

    $string = preg_replace('/[^\w\-]+/u', '', $string);

    return $string;
  }

  public static function tox(string $string) {

    $string = preg_replace('/[^\w]+/u', '', $string);

    return $string;
  }

  public static function bio(string $string) {

    $string = preg_replace('/[^\s\w\.\,\:\;\@\#\-\_\~\*\/]+/u', '', $string);

    return $string;
  }

  public static function string(mixed $string) {

    return (string) $string;
  }

  public static function int(mixed $int) {

    return (int) $int;
  }
}
