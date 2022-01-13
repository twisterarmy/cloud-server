<?php

class Filter {

  public static function userName(mixed $string) {

    $string = preg_replace('/[^a-zA-Z0-9_]+/u', '', (string) $string);

    $string = mb_substr($string, 0, 16);

    return $string;
  }

  public static function userPrivateKey(mixed $string) {

    return preg_replace('/[^a-zA-Z0-9_]+/u', '', (string) $string);
  }

  public static function blockHash(mixed $string) {

    return preg_replace('/[^a-zA-Z0-9]+/u', '', (string) $string);
  }

  public static function fullName(mixed $string) {

    $string = preg_replace('/[^\s\w]+/u', '', (string) $string);

    $string = mb_substr($string, 0, 200);

    return $string;
  }

  public static function sigUserPost(mixed $string) {

    return preg_replace('/[^a-zA-Z0-9]+/u', '', (string) $string);
  }

  public static function location(mixed $string) {

    $string = preg_replace('/[^\s\w\.\,]+/u', '', (string) $string);

    $string = mb_substr($string, 0, 200);

    return $string;
  }

  public static function url(mixed $string) {

    $string = preg_replace('/[^\w\?\&\=\.\:\/]+/u', '', (string) $string);

    $string = mb_substr($string, 0, 200);

    return $string;
  }

  public static function bitMessage(mixed $string) {

    $string = preg_replace('/[^\w\-]+/u', '', (string) $string);

    $string = mb_substr($string, 0, 200);

    return $string;
  }

  public static function tox(mixed $string) {

    $string = preg_replace('/[^\w]+/u', '', (string) $string);

    $string = mb_substr($string, 0, 200);

    return $string;
  }

  public static function bio(mixed $string) {

    $string = preg_replace('/[^\s\w\.\,\:\;\@\?\!\+\`\&\^\%\#\=\-\_\~\*\/\(\)\[\]\<\>\"\']+/u', '', (string) $string);

    $string = mb_substr($string, 0, 500);

    return $string;
  }

  public static function post(mixed $string) {

    $string = preg_replace('/[^\s\w\.\,\:\;\@\?\!\+\`\&\^\%\#\=\-\_\~\*\/\(\)\[\]\<\>\"\']+/u', '', (string) $string);

    $string = mb_substr($string, 0, 140);

    return $string;
  }

  public static function string(mixed $string) {

    return (string) $string;
  }

  public static function int(mixed $int) {

    return (int) $int;
  }

  public static function userPost(mixed $userPost) {

    $result = [];
    foreach ((array) $userPost as $key => $value) {

      switch ($key) {
        case 'height':
          $result[$key] = self::int($value);
        break;
        case 'time':
          $result[$key] = self::int($value);
        break;
        case 'k':
          $result[$key] = self::int($value);
        break;
        case 'lastk':
          $result[$key] = self::int($value);
        break;
        case 'n':
          $result[$key] = self::userName($value);
        break;
        case 'msg':
          $result[$key] = self::post($value);
        break;
        case 'msg2':
          $result[$key] = self::post($value);
        break;
        case 'sig_rt':
          $result[$key] = self::sigUserPost($value);
        break;
        case 'rt':
          $result[$key] = self::userPost($value);
        break;
      }
    }

    return $result;
  }
}
