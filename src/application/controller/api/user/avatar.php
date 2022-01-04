<?php

// Default response
$response = [
  'success' => false,
  'message' => _('Internal server error'),
  'avatar'  => false
];

// Access allowed for authorized users only
if (isset($_SESSION['userName'])) {

  // Prepare user request, authorized user by default
  $userName = isset($_GET['userName']) ? Filter::userName($_GET['userName']) : $_SESSION['userName'];

  // No cache request
  if (isset($_GET['nocache'])) {
      $_memcache->delete('api.user.avatar.' . $userName);
  }

  // Check user exists in the database
  if ($userId = $_modelUser->getUserId($userName)) {

    /*
    * Step 1: try to obtain avatar from cache
    *
    * */

    if ($mcAvatar = $_memcache->get('api.user.avatar.' . $userName)) {

      $response = [
        'success' => true,
        'message' => _('Avatar successfully received from Cache'),
        'avatar'  => $mcAvatar
      ];

    } else {

      /*
      * Step 2: try to obtain avatar from DHT
      *
      * */
      if ($dhtAvatarRevisions = $_twister->getDHTAvatarRevisions($userName)) {

        // Add DHT version if not exists
        foreach ((array) $dhtAvatarRevisions as $dhtAvatarRevision) {

          if (!$_modelAvatar->versionExists($userId,
                                            $dhtAvatarRevision['height'],
                                            $dhtAvatarRevision['seq'])) {

            $_modelAvatar->add($userId,
                              $dhtAvatarRevision['height'],
                              $dhtAvatarRevision['seq'],
                              $dhtAvatarRevision['time'],
                              $dhtAvatarRevision['data']);
          }
        }
      }

      /*
      * Step 3: Select latest version available from DB revisions
      *
      * */
      $dbAvatarRevision = $_modelAvatar->get($userId);

      if ($dbAvatarRevision && Valid::base64image($dbAvatarRevision['data'])) {

        // Response
        $response = [
          'success' => true,
          'message' => _('Avatar successfully received from DHT/DB'),
          'avatar'  => $dbAvatarRevision['data'] // format
        ];

        // Save request into the cache pool
        $_memcache->set('api.user.avatar.' . $userName, $dbAvatarRevision['data'], MEMCACHE_COMPRESS, MEMCACHE_DHT_AVATAR_TIMEOUT);

      // Cache, DHT, DB not contain any the avatar details about user requested,
      // Generate and return identity icon
      } else {

        // Generate identity icon
        $fileName = md5($userName);
        $filePath = PROJECT_DIR . '/cache/image/' . $fileName . '.jpeg';

        // Identity icons supports file cache
        if (!file_exists($filePath)) {

          $icon  = new Icon();
          $image = $icon->generateImageResource($fileName, 42, 42, false);

          file_put_contents($filePath, $image);
        }

        $identityIcon = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($filePath));

        $response = [
          'success' => true,
          'message' => _('Could not receive any avatar details, generated identity icon'),
          'avatar'  =>  $identityIcon
        ];

        // Save identity icon into the cache pool
        $_memcache->set('api.user.avatar.' . $userName, $identityIcon, MEMCACHE_COMPRESS, MEMCACHE_DHT_AVATAR_TIMEOUT);
      }
    }

  // User not found in the local database registry
  } else {

    $response = [
      'success' => false,
      'message' => _('Requested user not found'),
      'avatar'  => []
    ];
  }

// Session expired response
} else {

  $response = [
    'success' => false,
    'message' => _('Session expired. Please, reload the page.'),
    'avatar'  => false
  ];
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);