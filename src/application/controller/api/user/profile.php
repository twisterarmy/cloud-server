<?php

$response = [
  'success' => false,
  'message' => _('Internal server error'),
  'profile' => []
];

if (isset($_SESSION['userName'])) {

  $userName = isset($_POST['userName']) ? Filter::userName($_POST['userName']) : $_SESSION['userName'];

  if ($userProfileVersions = $_twister->getDHT($userName, 'profile', 's')) {

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

      $response = [
        'success' => true,
        'message' => _('Profile successfully received'),
        'profile' => [
          'userName'   => $userName,
          'fullName'   => $profileInfo['fullName'],
          'location'   => $profileInfo['location'],
          'url'        => $profileInfo['url'],
          'bitMessage' => $profileInfo['bitMessage'],
          'tox'        => $profileInfo['tox'],
          'bio'        => nl2br($profileInfo['bio']),
        ]
      ];

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