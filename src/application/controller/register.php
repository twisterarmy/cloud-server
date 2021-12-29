<?php

// Redirect to the login page on active session
if (isset($_SESSION['username'])) {
    header('Location: ' . PROJECT_HOST, true, 302);
}

// Define variables
$userName       = false;
$errorUserName  = false;

$blockEstimated = $_modelBlock->getTotal() + 1;

$metaTitle = _('Register | Twisterarmy Cloud');

// Check registration enabled
if (!APPLICATION_ALLOW_REGISTRATION) {

    require(PROJECT_DIR . '/application/view/register_off.phtml');
    exit;
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

    if ($_modelUser->userNameExists($userName)) {

      $errorUserName = _('Username already taken.');
    }
  }

  // Request valid
  if (!$errorUserName) {

    // Generate new wallet
    if ($userPrivateKey = $_twister->createWalletUser($userName)) {

      // Post new user public key to the network
      if ($transaction = $_twister->sendNewUserTransaction($userName)) {

        // Prepare Welcome page
        $metaTitle = _('Welcome | Twisterarmy Cloud');

        require(PROJECT_DIR . '/application/view/welcome.phtml');

        exit;

      } else {

        trigger_error($_twister->getError());
      }

    } else {

      trigger_error($_twister->getError());
    }
  }
}

require(PROJECT_DIR . '/application/view/register_on.phtml');