<?php

if (isset($_GET['hash'])) {

  $filename = md5($_GET['hash']);
  $filepath = PROJECT_DIR . '/cache/image/' . $filename . '.jpeg';

  if (!file_exists($filepath)) {

    $icon  = new Icon();
    $image = $icon->generateImageResource($filename, 42, 42, false);

    file_put_contents($filepath, $image);
  }

  $image = file_get_contents($filepath);

  header("Content-Type: image/jpeg");

  echo $image;
}
