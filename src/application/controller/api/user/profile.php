<?php

// Default response
$response = [
  'success' => false,
  'message' => _('Internal server error'),
  'profile' => []
];

// Access allowed for authorized users only
if (isset($_SESSION['userName'])) {

  // Prepare user request, authorized user by default
  $userName = isset($_GET['userName']) ? Filter::userName($_GET['userName']) : $_SESSION['userName'];

  // Check user exists in the database
  if ($userId = $_modelUser->getUserId($userName)) {

    /*
    * Step 1: try to obtain profile from cache
    *
    * */
    if ($mcProfile = $_memcache->get('api.user.profile.' . $userName)) {

      $response = [
        'success' => true,
        'message' => _('Profile successfully received from Cache'),
        'profile' => $mcProfile
      ];

    /*
    * Step 2: try to obtain profile from DHT
    *
    * */
    } else if ($dhtProfileRevisions = $_twister->getDHTProfileRevisions($userName)) {

      // Add DHT version if not exists
      foreach ((array) $dhtProfileRevisions as $dhtProfileRevision) {

        // Save revision into the database if not exists
        if (!$_modelProfile->versionExists($userId,
                                           $dhtProfileRevision['height'],
                                           $dhtProfileRevision['seq'])) {

          $_modelProfile->add($userId,
                              $dhtProfileRevision['height'],
                              $dhtProfileRevision['seq'],
                              $dhtProfileRevision['time'],

                              $dhtProfileRevision['fullName'],
                              $dhtProfileRevision['bio'],
                              $dhtProfileRevision['location'],
                              $dhtProfileRevision['url'],
                              $dhtProfileRevision['bitMessage'],
                              $dhtProfileRevision['tox']);
        }
      }
    }

    /*
    * Step 3: Select latest version available from DB revisions
    *
    * */
    if ($dbProfileRevision = $_modelProfile->get($userId)) {

      // Format output
      $profile = [
        'userName'   => $userName,
        'fullName'   => Format::text($dbProfileRevision['fullName']),
        'location'   => Format::text($dbProfileRevision['location']),
        'url'        => Format::text($dbProfileRevision['url']),
        'bitMessage' => Format::text($dbProfileRevision['bitMessage']),
        'tox'        => Format::text($dbProfileRevision['tox']),
        'bio'        => Format::text($dbProfileRevision['bio']),
      ];

      // Save request into the cache pool
      $_memcache->set('api.user.profile.' . $userName, $profile, MEMCACHE_COMPRESS, MEMCACHE_DHT_PROFILE_TIMEOUT);

      // Response
      $response = [
        'success' => true,
        'message' => _('Profile successfully received from DHT/DB'),
        'profile' => $profile
      ];

    // Cache, DHT, DB not contain any the details about user requested
    } else {

      $response = [
        'success' => false,
        'message' => _('Could not receive any profile details'),
        'profile' => []
      ];
    }

  // User not found in the local database registry
  } else {

    $response = [
      'success' => false,
      'message' => _('Requested user not found'),
      'profile' => []
    ];
  }

// Session expired response
} else {

  $response = [
    'success' => false,
    'message' => _('Session expired. Please, reload the page.'),
    'profile' => []
  ];
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);