<?php

$response = [
  'success' => false,
  'message' => _('Internal server error'),
  'avatar'  => false
];

if (isset($_SESSION['userName'])) {

  $userName = isset($_GET['userName']) ? Filter::userName($_GET['userName']) : $_SESSION['userName'];

  if ($avatarVersions = $_twister->getDHT($userName, 'avatar', 's')) {

    // Check avatar exists
    if ($userId = $_modelUser->getUserId($userName)) {

      // Add DHT version if not exists
      foreach ($avatarVersions as $avatarVersion) {

        if (!$_modelAvatar->versionExists($userId,
                                           $avatarVersion['p']['height'],
                                           $avatarVersion['p']['seq'])) {

          $_modelAvatar->add( $userId,
                              $avatarVersion['p']['height'],
                              $avatarVersion['p']['seq'],
                              $avatarVersion['p']['time'],
                              $avatarVersion['p']['v']);
        }
      }
    }

    // Get latest version available
    if ($avatarInfo = $_modelAvatar->get($userId)) {

      $response = [
        'success' => true,
        'message' => _('Avatar successfully received'),
        'avatar'  => $avatarInfo['data']
      ];

    } else {

      $response = [
        'success' => false,
        'message' => _('Avatar data not available'),
        'avatar'  => false
      ];
    }

  } else {

    $response = [
      'success' => false,
      'message' => _('Could not receive avatar details'),
      'avatar'  => false
    ];

  }

} else {

  $response = [
    'success' => false,
    'message' => _('Session expired. Please, reload the page.'),
    'avatar'  => false
  ];
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);