<?php

if (isset($_GET['hash'])) {

  $fileName = md5($_GET['hash']);
  $filePath = PROJECT_DIR . '/cache/image/' . $fileName . '.jpeg';

  if (!file_exists($filePath)) {

    $icon  = new Icon();
    $image = $icon->generateImageResource($fileName, 42, 42, false);

    file_put_contents($filePath, $image);
  }

  $image = file_get_contents($filePath);

  header("Content-Type: image/jpeg");

  echo $image;
}
