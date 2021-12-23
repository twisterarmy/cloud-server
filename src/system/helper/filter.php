<?php

class Filter {

  public static function userName(string $userName) {

    return preg_replace('/[^a-zA-Z0-9_]+/u', '', $userName);
  }

  public static function userPrivateKey(string $userPrivateKey) {

    return preg_replace('/[^a-zA-Z0-9_]+/u', '', $userPrivateKey);
  }
}
