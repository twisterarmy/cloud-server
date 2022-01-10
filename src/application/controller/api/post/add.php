<?php

$response = [
  'success' => false,
  'message' => _('Internal server error')
];

if (isset($_SESSION['userName'])) {

  // Get post index
  $postK = 1;

  if ($userPosts = $_twister->getPosts([$_SESSION['userName']], 1)) {

    if (isset($userPosts[0]['k'])) {
      $postK = Filter::int($userPosts[0]['k']) + 1;
    }

  }

  if ($postK && $result = $_twister->newPostMessage($_SESSION['userName'],
                                                    $postK,
                                                    Filter::post($_POST['message']))) {

    $response = [
      'success' => true,
      'message' => $postK
    ];

  } else {

    $response = [
      'success' => false,
      'message' => _('Could not send post message')
    ];
  }

} else {
  $response = [
    'success' => false,
    'message' => _('Session expired. Please, reload the page.')
  ];
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);