<?php

$response = [
  'success' => false,
  'message' => _('Internal server error'),
  'posts'   => [],
  'page'    => 0,
];

if (isset($_SESSION['userName'])) {

  $page = isset($_GET['page']) ? Filter::int($_GET['page']) : 1;

  $userNames = [];

  if (isset($_GET['userName']) && !empty($_GET['userName'])) {

    $userNames[] = Filter::userName($_GET['userName']);

  } else {

    foreach ((array) $_twister->getFollowing($_SESSION['userName']) as $followingUserName) {

      $userNames[] = Filter::userName($followingUserName);
    }
  }

  if ($result = $_twister->getPosts($userNames, APPLICATION_MAX_POST_FEED * $page)) {

    $postsTotal = 0;
    $posts = [];
    foreach ($result as $post) {

      // Count posts
      $postsTotal++;

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
      'posts'   => $posts,
      'page'    => $postsTotal == $page * APPLICATION_MAX_POST_FEED ? $page + 1 : 0
    ];

  } else {

    $response = [
      'success' => false,
      'message' => _('Could not receive post data'),
      'posts'   => [],
      'page'    => 0
    ];
  }

} else {
  $response = [
    'success' => false,
    'message' => _('Session expired'),
    'posts'   => [],
    'page'    => 0
  ];
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);