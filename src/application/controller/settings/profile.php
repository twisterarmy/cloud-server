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
$seq             = false;
$fullName        = false;
$location        = false;
$url             = false;
$bitMessage      = false;
$tox             = false;
$bio             = false;

$errorFullName   = false;
$errorLocation   = false;
$errorURL        = false;
$errorBitMessage = false;
$errorTOX        = false;
$errorBio        = false;

// Save profile details
if (isset($_POST) && !empty($_POST)) {

  // Prepare request
  $seq        = isset($_POST['seq'])        ? $_POST['seq']        : 0;
  $fullName   = isset($_POST['fullName'])   ? $_POST['fullName']   : '';
  $location   = isset($_POST['location'])   ? $_POST['location']   : '';
  $url        = isset($_POST['url'])        ? $_POST['url']        : '';
  $bitMessage = isset($_POST['bitMessage']) ? $_POST['bitMessage'] : '';
  $tox        = isset($_POST['tox'])        ? $_POST['tox']        : '';
  $bio        = isset($_POST['bio'])        ? $_POST['bio']        : '';

  // Increase revision number
  $seq++;

  // Get current block number
  $blockId = $_modelBlock->getThisBlock();

  // Save revision to DHT
  $userProfileVersions = $_twister->putDHT( $_SESSION['userName'],
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
                                            $seq);

  // Save revision to DB
  if (!$_modelProfile->versionExists($_SESSION['userId'],
                                     $blockId,
                                     $seq)) {

      $_modelProfile->add($_SESSION['userId'],
                          $blockId,
                          $seq,
                          time(),
                          $fullName,
                          $bio,
                          $location,
                          $url,
                          $bitMessage,
                          $tox);
  }

  $_memcache->replace('api.user.profile.' . $_SESSION['userName'],
                      [
                        'userName'   => $_SESSION['userName'],
                        'seq'        => $seq,
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

// Get profile details
if ($profile = $_memcache->get('api.user.profile.' . $_SESSION['userName'])) {

  $seq        = $profile['seq'];
  $fullName   = $profile['fullName'];
  $location   = $profile['location'];
  $url        = $profile['url'];
  $bitMessage = $profile['bitMessage'];
  $tox        = $profile['tox'];
  $bio        = $profile['bio'];

} else if ($userProfileVersions = $_twister->getDHT($_SESSION['userName'], 'profile', 's')) {

  // Check user exists
  if ($userId = $_modelUser->getUserId($_SESSION['userName'])) {

    // Add DHT version if not exists
    foreach ($userProfileVersions as $userProfileVersion) {

      if (!$_modelProfile->versionExists($userId,
                                         $userProfileVersion['p']['height'],
                                         $userProfileVersion['p']['seq'])) {

        $profile = $userProfileVersion['p']['v'];

        $_modelProfile->add($userId,
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
  }

  // Get latest version available
  if ($profileInfo = $_modelProfile->get($_SESSION['userId'])) {

    $profile = [
      'userName'   => $_SESSION['userName'],
      'seq'        => $profileInfo['seq'],
      'fullName'   => $profileInfo['fullName'],
      'location'   => $profileInfo['location'],
      'url'        => $profileInfo['url'],
      'bitMessage' => $profileInfo['bitMessage'],
      'tox'        => $profileInfo['tox'],
      'bio'        => $profileInfo['bio'],
    ];

    $seq        = $profile['seq'];
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