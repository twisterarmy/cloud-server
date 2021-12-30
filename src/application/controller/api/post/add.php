<?php

$response = [
  'success' => false,
  'message' => _('Internal server error')
];

if (isset($_SESSION['userName'])) {

  if (!isset($_POST['message']) || (isset($_POST['message']) && !Valid::userPost($_POST['message']))) {

    $response = [
      'success' => false,
      'message' => _('Post message must contain from 1 to 140 chars')
    ];

  } else if (!$userPosts = $_twister->getPosts([$_SESSION['userName']], 1)) {

    $response = [
      'success' => false,
      'message' => _('Could not receive user post')
    ];

  } else if (isset($userPosts[0]['userpost']['k']) && $result = $_twister->newPostMessage($_SESSION['userName'], $userPosts[0]['userpost']['k'] + 1, $_POST['message'])) {

    $response = [
      'success' => true,
      'message' => _('Post successfully sent')
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