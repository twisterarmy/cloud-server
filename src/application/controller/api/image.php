<?php

if (isset($_GET['hash'])) {

  $icon = new Icon();

  header("Content-Type: image/jpeg");
  echo $icon->generateImageResource(md5($_GET['hash']), 42, 42, false);
}