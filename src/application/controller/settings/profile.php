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
  $fullName   = isset($_POST['fullName'])   ? Filter::string($_POST['fullName'])   : '';
  $location   = isset($_POST['location'])   ? Filter::string($_POST['location'])   : '';
  $url        = isset($_POST['url'])        ? Filter::string($_POST['url'])        : '';
  $bitMessage = isset($_POST['bitMessage']) ? Filter::string($_POST['bitMessage']) : '';
  $tox        = isset($_POST['tox'])        ? Filter::string($_POST['tox'])        : '';
  $bio        = isset($_POST['bio'])        ? Filter::string($_POST['bio'])        : '';

  // Get current block number
  $blockId = $_modelBlock->getThisBlock();

  // Avatar provided
  if (isset($_FILES['avatar']['tmp_name']) && file_exists($_FILES['avatar']['tmp_name']) && getimagesize($_FILES['avatar']['tmp_name'])) {

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
                                      Filter::int($avatarVersion['p']['height']),
                                      Filter::int($avatarVersion['p']['seq']))) {

      $_modelAvatar->add( $_SESSION['userId'],
                          Filter::int($avatarVersion['p']['height']),
                          Filter::int($avatarVersion['p']['seq']),
                          Filter::int($avatarVersion['p']['time']),
                          Filter::string($avatarVersion['p']['v']));
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

// Get profile details from cache
if ($profile = $_memcache->get('api.user.profile.' . $_SESSION['userName'])) {

  $fullName   = $profile['fullName'];
  $location   = $profile['location'];
  $url        = $profile['url'];
  $bitMessage = $profile['bitMessage'];
  $tox        = $profile['tox'];
  $bio        = $profile['bio'];

// Get profile details from DHT
} else if ($userProfileVersions = $_twister->getDHT($_SESSION['userName'], 'profile', 's')) {

  // Add DHT version if not exists
  foreach ($userProfileVersions as $userProfileVersion) {

    if (!$_modelProfile->versionExists($_SESSION['userId'],
                                       Filter::int($userProfileVersion['p']['height']),
                                       Filter::int($userProfileVersion['p']['seq']))) {

      if (isset($userProfileVersion['p']['v'])) {

        $profile = $userProfileVersion['p']['v'];

        $_modelProfile->add($_SESSION['userId'],
                            Filter::int($userProfileVersion['p']['height']),
                            Filter::int($userProfileVersion['p']['seq']),
                            Filter::int($userProfileVersion['p']['time']),

                            isset($profile['fullname'])   ? Filter::string($profile['fullname'])   : '',
                            isset($profile['bio'])        ? Filter::string($profile['bio'])        : '',
                            isset($profile['location'])   ? Filter::string($profile['location'])   : '',
                            isset($profile['url'])        ? Filter::string($profile['url'])        : '',
                            isset($profile['bitmessage']) ? Filter::string($profile['bitmessage']) : '',
                            isset($profile['tox'])        ? Filter::string($profile['tox'])        : '');
      }
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