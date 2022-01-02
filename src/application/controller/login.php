<?php

// Define variables
$userName            = false;
$userPrivateKey      = false;

$errorUserName       = false;
$errorUserPrivateKey = false;

$metaTitle = _('Login | Twisterarmy Cloud');

// Redirect home when user already logged
if (isset($_SESSION['userName'])) {
    header('Location: ' . PROJECT_HOST, true, 302);
}

// Process form request
if (isset($_POST) && $_POST) {

  // Validate userName
  if (!isset($_POST['userName'])) {

    $errorUserName = _('Username value required.');

  } else {

    if (!Valid::userName($_POST['userName'])) {

      $errorUserName = _('Username must contain a-z_0-9 up to 16 chars.');
    }

    $userName = Filter::userName($_POST['userName']);

    if (!$userName) {

      $errorUserName = _('Username required.');
    }
  }

  // Validate userPrivateKey
  if (!isset($_POST['userPrivateKey'])) {

    $errorUserPrivateKey = _('Key value required.');

  } else {

    if (!Valid::userPrivateKey($_POST['userPrivateKey'])) {

      $errorUserPrivateKey = _('Key must contain a-z_0-9 chars.');
    }

    $userPrivateKey = Filter::userPrivateKey($_POST['userPrivateKey']);

    if (!$userPrivateKey) {

      $errorUserPrivateKey = _('Private key required.');
    }
  }

  // Request valid
  if (!$errorUserName && !$errorUserPrivateKey) {

    // Check user exists
    if ($userId = $_modelUser->getUserId($userName)) {

      if ($_twister->importWallet($userName, $userPrivateKey)) {

        // Follow self feed
        $_twister->follow($userName, [$userName]);

        // @TODO: add login time

        // Auth
        if (session_id()) {
            session_destroy();
        }

        session_start();

        $_SESSION['userName'] = $userName;
        $_SESSION['userId']   = $userId;

        $_memcache->flush();

        // Redirect
        header('Location: ' . PROJECT_HOST, true, 302);

      } else {

        $errorUserPrivateKey = $_twister->getError();
      }

    } else {

      $errorUserName = _('Username not exists or pending for registration.');

    }
  }
}

require(PROJECT_DIR . '/application/view/login.phtml');