<?php

$response = [
  'success' => false,
  'message' => _('Internal server error'),
  'avatar'  => false
];

if (isset($_SESSION['userName'])) {

  $userName = isset($_GET['userName']) ? Filter::userName($_GET['userName']) : $_SESSION['userName'];

  if ($avatar = $_memcache->get('api.user.avatar.' . $userName)) {

    $response = [
      'success' => true,
      'message' => _('Avatar successfully received from Cache'),
      'avatar'  => $avatar
    ];

  } else if ($avatarVersions = $_twister->getDHT($userName, 'avatar', 's')) {

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
        'message' => _('Avatar successfully received from DHT'),
        'avatar'  => $avatarInfo['data']
      ];

      $_memcache->set('api.user.avatar.' . $userName, $avatarInfo['data'], MEMCACHE_COMPRESS, MEMCACHE_DHT_AVATAR_TIMEOUT);

    } else {

      $response = [
        'success' => false,
        'message' => _('Avatar data not available'),
        'avatar'  => false
      ];
    }

  // Generate identity icon
  } else {

    $fileName = md5($userName);
    $filePath = PROJECT_DIR . '/cache/image/' . $fileName . '.jpeg';

    if (!file_exists($filePath)) {

      $icon  = new Icon();
      $image = $icon->generateImageResource($fileName, 42, 42, false);

      file_put_contents($filePath, $image);
    }

    $image = file_get_contents($filePath);

    $response = [
      'success' => true,
      'message' => _('Avatar successfully received from Identity'),
      'avatar'  => 'data:image/jpeg;base64,' . base64_encode($image)
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