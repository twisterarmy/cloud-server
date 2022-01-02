<?php

$response = [
  'success' => false,
  'message' => _('Internal server error'),
  'profile' => []
];

if (isset($_SESSION['userName'])) {

  $userName = isset($_GET['userName']) ? Filter::userName($_GET['userName']) : $_SESSION['userName'];

  if ($profile = $_memcache->get('api.user.profile.' . $userName)) {

    $response = [
      'success' => true,
      'message' => _('Profile successfully received from Cache'),
      'profile' => $profile
    ];

  } else if ($userProfileVersions = $_twister->getDHT($userName, 'profile', 's')) {

    // Check user exists
    if ($userId = $_modelUser->getUserId($userName)) {

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
    if ($profileInfo = $_modelProfile->get($userId)) {

      $profile = [
        'userName'   => $userName,
        'fullName'   => $profileInfo['fullName'],
        'location'   => $profileInfo['location'],
        'url'        => $profileInfo['url'],
        'bitMessage' => $profileInfo['bitMessage'],
        'tox'        => $profileInfo['tox'],
        'bio'        => nl2br($profileInfo['bio']),
      ];

      $response = [
        'success' => true,
        'message' => _('Profile successfully received from DHT'),
        'profile' => $profile
      ];

      $_memcache->set('api.user.profile.' . $userName, $profile, MEMCACHE_COMPRESS, MEMCACHE_DHT_PROFILE_TIMEOUT);

    } else {

      $response = [
        'success' => false,
        'message' => _('Profile data not available'),
        'profile' => []
      ];
    }

  } else {

    $response = [
      'success' => false,
      'message' => _('Could not receive profile details'),
      'profile' => []
    ];

  }

} else {

  $response = [
    'success' => false,
    'message' => _('Session expired. Please, reload the page.'),
    'profile' => []
  ];
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);