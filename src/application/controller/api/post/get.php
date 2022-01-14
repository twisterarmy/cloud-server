<?php

// Define default response
$response = [
  'success' => false,
  'message' => _('Internal server error'),
  'posts'   => [],
  'page'    => 0,
];

// Authorization required
if (isset($_SESSION['userName'])) {

  // Define page number
  $page = isset($_GET['page']) ? Filter::int($_GET['page']) : 1;

  $userNames = [];

  // Single user posts mode
  if (isset($_GET['userName']) && !empty($_GET['userName'])) {

    $userNames[] = Filter::userName($_GET['userName']);

  // Following feed by default (when userName attribute not provided)
  } else {

    foreach ((array) $_twister->getFollowing($_SESSION['userName']) as $followingUserName) {

      $userNames[] = Filter::userName($followingUserName);
    }
  }

  // Get posts from the node (pre-collected from DHT)
  if ($result = $_twister->getPosts($userNames, APPLICATION_MAX_POST_FEED * $page)) {

    $postsTotal = 0;
    $posts = [];
    foreach ($result as $post) {

      // Count posts
      $postsTotal++;

      // Format reTwists
      $reTwist = [];
      if (isset($post['userpost']['rt'])) {

        $reTwist = [
          'userName' => isset($post['userpost']['rt']['n']) ? $post['userpost']['rt']['n'] : false,
          'message'  => Format::post((isset($post['userpost']['rt']['msg']) ? $post['userpost']['rt']['msg'] : false) . (isset($post['userpost']['rt']['msg2']) ? $post['userpost']['rt']['msg2'] : false)),
          'time'     => Format::time((isset($post['userpost']['rt']['time']) ? $post['userpost']['rt']['time'] : false)),
        ];
      }

      // Format posts
      $posts[] = [
        'userName' => isset($post['userpost']['n']) ? $post['userpost']['n'] : false,
        'message'  => Format::post((isset($post['userpost']['msg']) ? $post['userpost']['msg'] : false) . (isset($post['userpost']['msg2']) ? $post['userpost']['msg2'] : false)),
        'time'     => Format::time((isset($post['userpost']['time']) ? $post['userpost']['time'] : false)),

        'reTwist'  => $reTwist,

        'meta'     => base64_encode(json_encode($post)),
      ];
    }

    $response = [
      'success' => true,
      'message' => _('Posts successfully loaded'),
      'posts'   => $posts,

      // Increase page index for ajax pagination
      // (would be moved to JS, but next page button better to hide on empty results @TODO)
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