<?php

class Filter {

  public static function userName(mixed $string) {

    $string = preg_replace('/[^a-zA-Z0-9_]+/u', '', $string);

    $string = mb_substr($string, 0, 16);

    return $string;
  }

  public static function userPrivateKey(mixed $string) {

    return preg_replace('/[^a-zA-Z0-9_]+/u', '', $string);
  }

  public static function blockHash(mixed $string) {

    return preg_replace('/[^a-zA-Z0-9]+/u', '', $string);
  }

  public static function fullName(string $string) {

    $string = preg_replace('/[^\s\w]+/u', '', $string);

    $string = mb_substr($string, 0, 200);

    return $string;
  }

  public static function location(string $string) {

    $string = preg_replace('/[^\s\w\.\,]+/u', '', $string);

    $string = mb_substr($string, 0, 200);

    return $string;
  }

  public static function url(string $string) {

    $string = preg_replace('/[^\w\?\&\=\.\:\/]+/u', '', $string);

    $string = mb_substr($string, 0, 200);

    return $string;
  }

  public static function bitMessage(string $string) {

    $string = preg_replace('/[^\w\-]+/u', '', $string);

    $string = mb_substr($string, 0, 200);

    return $string;
  }

  public static function tox(string $string) {

    $string = preg_replace('/[^\w]+/u', '', $string);

    $string = mb_substr($string, 0, 200);

    return $string;
  }

  public static function bio(string $string) {

    $string = preg_replace('/[^\s\w\.\,\:\;\@\?\!\+\`\&\^\%\#\=\-\_\~\*\/\(\)\[\]\<\>\"\']+/u', '', $string);

    $string = mb_substr($string, 0, 500);

    return $string;
  }

  public static function post(string $string) {

    $string = preg_replace('/[^\s\w\.\,\:\;\@\?\!\+\`\&\^\%\#\=\-\_\~\*\/\(\)\[\]\<\>\"\']+/u', '', $string);

    $string = mb_substr($string, 0, 140);

    return $string;
  }

  public static function string(mixed $string) {

    return (string) $string;
  }

  public static function int(mixed $int) {

    return (int) $int;
  }
}
