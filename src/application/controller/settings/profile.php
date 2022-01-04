<?php

if (!isset($_SESSION['userName'])) {

  header('Location: ' . PROJECT_HOST . '/login', true, 302);
}

$metaTitle = _('Profile Settings | Twisterarmy Cloud');
$pageTitle = _('Profile');

// Load dependencies
$metaStyles = [
  'css/template/default/module/menu.css',
  'css/template/default/module/settings.css',
  'css/template/default/settings/profile.css',
];

$metaScripts = [
  'js/module/menu.js',
  'js/module/settings.js',
  'js/settings/profile.js',
];

// Init variables
$successMessage = false;

/*
* Process profile update request
*
* */

if (isset($_POST) && !empty($_POST)) {

  // Filter form data
  $fullName   = isset($_POST['fullName'])   ? Filter::fullName($_POST['fullName'])     : '';
  $location   = isset($_POST['location'])   ? Filter::location($_POST['location'])     : '';
  $url        = isset($_POST['url'])        ? Filter::url($_POST['url'])               : '';
  $bitMessage = isset($_POST['bitMessage']) ? Filter::bitMessage($_POST['bitMessage']) : '';
  $tox        = isset($_POST['tox'])        ? Filter::tox($_POST['tox'])               : '';
  $bio        = isset($_POST['bio'])        ? Filter::bio($_POST['bio'])               : '';

  // Get current block number
  $blockId = $_modelBlock->getThisBlock();

  // Avatar provided
  if (isset($_FILES['avatar']['tmp_name']) && file_exists($_FILES['avatar']['tmp_name']) && @getimagesize($_FILES['avatar']['tmp_name'])) {

    // Prepare image
    $image = new Imagick();

    $image->readImage($_FILES['avatar']['tmp_name']);
    $image->resizeImage(64, 64, 1, false);
    $image->setImageFormat('jpeg');
    $image->setCompressionQuality(100);

    // Encode base 64
    $avatar = 'data:image/jpeg;base64,' . base64_encode($image->getImageBlob());

    // Get avatar revision
    $avatarSeq = $_modelAvatar->getMaxSeq($_SESSION['userId']) + 1;

    // Custom initial revision
    if ($avatarSeq == 1) {
        $avatarSeq = $avatarSeq + TWISTER_SEQ_START_FROM;
    }

    // Save avatar revision to DHT
    $_twister->putDHT($_SESSION['userName'],
                      'avatar',
                      's',
                      $avatar,
                      $_SESSION['userName'],
                      $avatarSeq);

    // Save avatar revision to DB
    if (!$_modelAvatar->versionExists($_SESSION['userId'],
                                      $blockId,
                                      $avatarSeq)) {

      $_modelAvatar->add( $_SESSION['userId'],
                          $blockId,
                          $avatarSeq,
                          time(),
                          $avatar);
    }

    // Update avatar cache
    $_memcache->replace('api.user.avatar.' . $_SESSION['userName'], $avatar, MEMCACHE_COMPRESS, MEMCACHE_DHT_AVATAR_TIMEOUT);
  }

  // Get profile revision
  $profileSeq = $_modelProfile->getMaxSeq($_SESSION['userId']) + 1;

  // Custom initial revision
  if ($profileSeq == 1) {
      $profileSeq = $profileSeq + TWISTER_SEQ_START_FROM;
  }

  // Save profile revision to DHT
  $_twister->putDHT($_SESSION['userName'],
                    'profile',
                    's',
                    [
                      'fullname'   => $fullName,
                      'location'   => $location,
                      'url'        => $url,
                      'bitmessage' => $bitMessage,
                      'tox'        => $tox,
                      'bio'        => $bio,
                    ],
                    $_SESSION['userName'],
                    $profileSeq);

  // Save profile revision to DB
  if (!$_modelProfile->versionExists($_SESSION['userId'],
                                     $blockId,
                                     $profileSeq)) {

      $_modelProfile->add($_SESSION['userId'],
                          $blockId,
                          $profileSeq,
                          time(),
                          $fullName,
                          $bio,
                          $location,
                          $url,
                          $bitMessage,
                          $tox);


    // Update profile cache
    $_memcache->replace('api.user.profile.' . $_SESSION['userName'],
                        [
                          'userName'   => $_SESSION['userName'],
                          'fullName'   => $fullName,
                          'location'   => $location,
                          'url'        => $url,
                          'bitMessage' => $bitMessage,
                          'tox'        => $tox,
                          'bio'        => Format::bio($bio),
                        ],
                        MEMCACHE_COMPRESS,
                        MEMCACHE_DHT_PROFILE_TIMEOUT);

    $successMessage = _('Profile successfully saved!');
  }
}

require(PROJECT_DIR . '/application/view/settings/profile.phtml');