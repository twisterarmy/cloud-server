<?php

class Localization {

  public static function plural(int $number, array $texts) {

    $cases = [2, 0, 1, 1, 1, 2];

    return $texts[(($number % 100) > 4 && ($number % 100) < 20) ? 2 : $cases[min($number % 10, 5)]];
  }
}
