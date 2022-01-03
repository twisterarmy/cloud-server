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

      // Split message parts
      $messages = [Filter::string($post['userpost']['msg'])];

      for ($i = 0; $i <= APPLICATION_MAX_POST_SPLIT; $i++) {

        $n = sprintf('msg%s', $i);

        if (isset($post['userpost'][$n])) {
          $messages[] = Filter::string($post['userpost'][$n]);
        }
      }

      // Process reTwists
      $reTwist = [];
      if (isset($post['userpost']['rt'])) {

        // Split reTwists parts
        $reTwists = [Filter::string($post['userpost']['rt']['msg'])];

        for ($i = 0; $i <= APPLICATION_MAX_POST_SPLIT; $i++) {

          $n = sprintf('msg%s', $i);

          if (isset($post['userpost']['rt'][$n])) {
            $reTwists[] = Filter::string($post['userpost']['rt'][$n]);
          }
        }

        $reTwist = [
          'message'  => Format::post(implode('', $reTwists)),
          'time'     => Format::time(Filter::int($post['userpost']['rt']['time'])),
          'userName' => Filter::userName($post['userpost']['rt']['n']),
          'reTwist'  => $reTwist,
        ];
      }

      $posts[] = [
        'message'  => Format::post(implode('', $messages)),
        'time'     => Format::time(Filter::int($post['userpost']['time'])),
        'userName' => Filter::userName($post['userpost']['n']),
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