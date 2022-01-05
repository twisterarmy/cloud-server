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

  // No cache request
  if (isset($_GET['nocache'])) {
      $_memcache->delete('api.user.profile.' . $userName);
  }

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

    } else {

      /*
      * Step 2: try to obtain profile from DHT
      *
      * */
      if ($dhtProfileRevisions = $_twister->getDHTProfileRevisions($userName)) {

        // Add DHT version if not exists
        foreach ((array) $dhtProfileRevisions as $dhtProfileRevision) {

          // Save revision into the database if not exists
          if (!$_modelProfile->versionExists($userId,
                                             Filter::int($dhtProfileRevision['height']),
                                             Filter::int($dhtProfileRevision['seq']))) {

            $_modelProfile->add($userId,
                                Filter::int($dhtProfileRevision['height']),
                                Filter::int($dhtProfileRevision['seq']),
                                Filter::int($dhtProfileRevision['time']),

                                Filter::fullName($dhtProfileRevision['fullName']),
                                Filter::bio($dhtProfileRevision['bio']),
                                Filter::location($dhtProfileRevision['location']),
                                Filter::url($dhtProfileRevision['url']),
                                Filter::bitMessage($dhtProfileRevision['bitMessage']),
                                Filter::tox($dhtProfileRevision['tox']));
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
          'fullName'   => $dbProfileRevision['fullName'],
          'location'   => $dbProfileRevision['location'],
          'url'        => $dbProfileRevision['url'],
          'bitMessage' => $dbProfileRevision['bitMessage'],
          'tox'        => $dbProfileRevision['tox'],
          'bio'        => isset($_GET['format']) ? Format::bio($dbProfileRevision['bio']) : $dbProfileRevision['bio'],
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