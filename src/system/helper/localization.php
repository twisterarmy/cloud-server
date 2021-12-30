<?php

class Localization {

  public static function plural(int $number, array $texts) {

    $cases = [2, 0, 1, 1, 1, 2];

    return $texts[(($number % 100) > 4 && ($number % 100) < 20) ? 2 : $cases[min($number % 10, 5)]];
  }

  public static function time(int $time) {

    $timeDiff = time() - $time;

    if ($timeDiff < 1) {
      return _('0 seconds');
    }

    $a = [365 * 24 * 60 * 60  => [_('year'), _('years'), _('years')],
                30 * 24 * 60 * 60  => [_('month'), _('months'), _('months')],
                     24 * 60 * 60  => [_('day'), _('days'), _('days')],
                          60 * 60  => [_('hour'), _('hours'), _('hours')],
                               60  => [_('minute'), _('minutes'), _('minutes')],
                                1  => [_('second'), _('seconds'), _('seconds')]];

    foreach ($a as $secs => $v) {

      $d = $timeDiff / $secs;

      if ($d >= 1) {
          $r = round($d);
          return sprintf('%s %s ago', $r, self::plural($r, $v));
      }
    }
}
}
