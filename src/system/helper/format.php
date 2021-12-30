<?php

class Format {

  public static function plural(int $number, array $texts) {

    $cases = [2, 0, 1, 1, 1, 2];

    return $texts[(($number % 100) > 4 && ($number % 100) < 20) ? 2 : $cases[min($number % 10, 5)]];
  }

  public static function time(int $time, bool $ago = true) {

    if ($ago) {
      $timeDiff = time() - $time;
    } else {
      $timeDiff = $time - time();
    }

    if ($timeDiff < 1) {
      return _('now');
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

          if ($ago) {
            return sprintf(_('%s %s ago'), $r, self::plural($r, $v));
          } else {
            return sprintf(_('%s %s later'), $r, self::plural($r, $v));
          }


      }
    }
  }

  public static function post(string $text) {

    $text = preg_replace("|((https?://)?([\d\w\.-]+\.[\w\.]{2,6})[^\s\]\[\<\>]*/?)|i", "<a href=\"$1\" target=\"_blank\">$3</a>", $text);
    $text = nl2br($text);

    return $text;
  }
}