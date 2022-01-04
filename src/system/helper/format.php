<?php

class Format {

  // Common
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

  // Profile
  public static function bio(string $string) {

    $string = preg_replace("|\*([\S]+)\*|i", "<b>$1</b>", $string);
    $string = preg_replace("|\~([\S]+)\~|i", "<i>$1</i>", $string);
    $string = preg_replace("|\_([\S]+)\_|i", "<u>$1</u>", $string);
    $string = preg_replace("|\-([\S]+)\-|i", "<s>$1</s>", $string);
    $string = preg_replace("|\`([\S]+)\`|i", "<samp>$1</samp>", $string);

    $string = preg_replace("|@([a-zA-Z0-9_]+)|i", "<a href=\"people/$1\">@$1</a>", $string);
    $string = preg_replace("|((https?://)+([\d\w\.-]+\.[\w\.]{2,6})[^\s\]\[\<\>]*/?)|i", "<a href=\"$1\" target=\"_blank\">$3</a>", $string);

    $string = nl2br($string);

    return $string;
  }

  // @TODO REPLACE
  public static function text(string $string) {

    $string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
    $string = htmlentities($string, ENT_QUOTES, 'UTF-8');

    $string = preg_replace("|\*([\S]+)\*|i", "<b>$1</b>", $string);
    $string = preg_replace("|\~([\S]+)\~|i", "<i>$1</i>", $string);
    $string = preg_replace("|\_([\S]+)\_|i", "<u>$1</u>", $string);
    $string = preg_replace("|\-([\S]+)\-|i", "<s>$1</s>", $string);
    $string = preg_replace("|\`([\S]+)\`|i", "<samp>$1</samp>", $string);

    $string = preg_replace("|@([a-zA-Z0-9_]+)|i", "<a href=\"people/$1\">@$1</a>", $string);
    $string = preg_replace("|((https?://)+([\d\w\.-]+\.[\w\.]{2,6})[^\s\]\[\<\>]*/?)|i", "<a href=\"$1\" target=\"_blank\">$3</a>", $string);

    $string = nl2br($string);

    return $string;
  }
}