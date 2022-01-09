<?php

$response = [
  'success' => false,
  'message' => _('Internal server error'),
  'posts'   => [],
];

if (isset($_SESSION['userName'])) {

  $userNames = [];

  if (isset($_GET['userName']) && !empty($_GET['userName'])) {

    $userNames[] = Filter::userName($_GET['userName']);

  } else {

    foreach ((array) $_twister->getFollowing($_SESSION['userName']) as $followingUserName) {

      $userNames[] = Filter::userName($followingUserName);
    }
  }

  if ($result = $_twister->getPosts($userNames, APPLICATION_MAX_POST_FEED)) {

    $posts = [];
    foreach ($result as $post) {

      // Process reTwists
      $reTwist = [];
      if ($post['reTwist']) {

        $reTwist = [
          'message'  => Format::post($post['reTwist']['message']),
          'time'     => Format::time($post['reTwist']['time']),
          'userName' => $post['reTwist']['userName'],
        ];
      }

      // Process posts
      $posts[] = [
        'message'  => Format::post($post['message']),
        'time'     => Format::time($post['time']),
        'userName' => $post['userName'],
        'reTwist'  => $reTwist,
      ];
    }

    $response = [
      'success' => true,
      'message' => _('Posts successfully loaded'),
      'posts'   => $posts
    ];

  } else {

    $response = [
      'success' => false,
      'message' => _('Could not receive post data'),
      'posts'   => [],
    ];
  }

} else {
  $response = [
    'success' => false,
    'message' => _('Session expired'),
    'posts'   => [],
  ];
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);