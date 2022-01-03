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
$fullName        = false;
$location        = false;
$url             = false;
$bitMessage      = false;
$tox             = false;
$bio             = false;
$avatar          = false;

$errorFullName   = false;
$errorLocation   = false;
$errorURL        = false;
$errorBitMessage = false;
$errorTOX        = false;
$errorBio        = false;

// Save profile details
if (isset($_POST) && !empty($_POST)) {

  // Prepare request
  $fullName   = isset($_POST['fullName'])   ? $_POST['fullName']   : '';
  $location   = isset($_POST['location'])   ? $_POST['location']   : '';
  $url        = isset($_POST['url'])        ? $_POST['url']        : '';
  $bitMessage = isset($_POST['bitMessage']) ? $_POST['bitMessage'] : '';
  $tox        = isset($_POST['tox'])        ? $_POST['tox']        : '';
  $bio        = isset($_POST['bio'])        ? $_POST['bio']        : '';

  // Get current block number
  $blockId = $_modelBlock->getThisBlock();

  // Avatar provided
  if (isset($_FILES['avatar']['tmp_name']) && getimagesize($_FILES['avatar']['tmp_name'])) {

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
    $_memcache->set('api.user.avatar.' . $_SESSION['userName'], $avatar, MEMCACHE_COMPRESS, MEMCACHE_DHT_AVATAR_TIMEOUT);
  }

  // Get profile revision
  $profileSeq = $_modelProfile->getMaxSeq($_SESSION['userId']) + 1;

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
  }

  // Update profile cache
  $_memcache->replace('api.user.profile.' . $_SESSION['userName'],
                      [
                        'userName'   => $_SESSION['userName'],
                        'fullName'   => $fullName,
                        'location'   => $location,
                        'url'        => $url,
                        'bitMessage' => $bitMessage,
                        'tox'        => $tox,
                        'bio'        => $bio,
                      ],
                      MEMCACHE_COMPRESS,
                      MEMCACHE_DHT_PROFILE_TIMEOUT);

}

// Get avatar details
if ($userAvatar = $_memcache->get('api.user.avatar.' . $_SESSION['userName'])) {

  $avatar = $userAvatar;

} else if ($avatarVersions = $_twister->getDHT($_SESSION['userName'], 'avatar', 's')) {

  // Add DHT version if not exists
  foreach ($avatarVersions as $avatarVersion) {

    if (!$_modelAvatar->versionExists($_SESSION['userId'],
                                      $avatarVersion['p']['height'],
                                      $avatarVersion['p']['seq'])) {

      $_modelAvatar->add( $_SESSION['userId'],
                          $avatarVersion['p']['height'],
                          $avatarVersion['p']['seq'],
                          $avatarVersion['p']['time'],
                          $avatarVersion['p']['v']);
    }
  }

  // Get latest version available
  if ($avatarInfo = $_modelAvatar->get($_SESSION['userId'])) {

    $avatar = $avatarInfo['data'];

    $_memcache->set('api.user.avatar.' . $_SESSION['userName'], $avatarInfo['data'], MEMCACHE_COMPRESS, MEMCACHE_DHT_AVATAR_TIMEOUT);
  }

// Generate identity icon
} else {

  $fileName = md5($_SESSION['userName']);
  $filePath = PROJECT_DIR . '/cache/image/' . $fileName . '.jpeg';

  if (!file_exists($filePath)) {

    $icon  = new Icon();
    $image = $icon->generateImageResource($fileName, 42, 42, false);

    file_put_contents($filePath, $image);
  }

  $avatar = sprintf('data:image/jpeg;base64,%s', base64_encode(file_get_contents($filePath)));
}

// Get profile details
if ($profile = $_memcache->get('api.user.profile.' . $_SESSION['userName'])) {

  $fullName   = $profile['fullName'];
  $location   = $profile['location'];
  $url        = $profile['url'];
  $bitMessage = $profile['bitMessage'];
  $tox        = $profile['tox'];
  $bio        = $profile['bio'];

} else if ($userProfileVersions = $_twister->getDHT($_SESSION['userName'], 'profile', 's')) {

  // Add DHT version if not exists
  foreach ($userProfileVersions as $userProfileVersion) {

    if (!$_modelProfile->versionExists($_SESSION['userId'],
                                       $userProfileVersion['p']['height'],
                                       $userProfileVersion['p']['seq'])) {

      $profile = $userProfileVersion['p']['v'];

      $_modelProfile->add($_SESSION['userId'],
                          $userProfileVersion['p']['height'],
                          $userProfileVersion['p']['seq'],
                          $userProfileVersion['p']['time'],

                          isset($profile['fullname'])   ? $profile['fullname']   : '',
                          isset($profile['bio'])        ? $profile['bio']        : '',
                          isset($profile['location'])   ? $profile['location']   : '',
                          isset($profile['url'])        ? $profile['url']        : '',
                          isset($profile['bitmessage']) ? $profile['bitmessage'] : '',
                          isset($profile['tox'])        ? $profile['tox']        : '');
    }
  }

  // Get latest version available
  if ($profileInfo = $_modelProfile->get($_SESSION['userId'])) {

    $profile = [
      'userName'   => $_SESSION['userName'],
      'fullName'   => $profileInfo['fullName'],
      'location'   => $profileInfo['location'],
      'url'        => $profileInfo['url'],
      'bitMessage' => $profileInfo['bitMessage'],
      'tox'        => $profileInfo['tox'],
      'bio'        => $profileInfo['bio'],
    ];

    $fullName   = $profile['fullName'];
    $location   = $profile['location'];
    $url        = $profile['url'];
    $bitMessage = $profile['bitMessage'];
    $tox        = $profile['tox'];
    $bio        = $profile['bio'];

    $_memcache->set('api.user.profile.' . $_SESSION['userName'], $profile, MEMCACHE_COMPRESS, MEMCACHE_DHT_PROFILE_TIMEOUT);

  }
}

require(PROJECT_DIR . '/application/view/settings/profile.phtml');