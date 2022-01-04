<?php

$response = [
  'success' => false,
  'message' => _('Internal server error')
];

if (isset($_SESSION['userName'])) {

  if (!$userPosts = $_twister->getPosts([$_SESSION['userName']], 1)) {

    $response = [
      'success' => false,
      'message' => _('Could not receive last user post')
    ];

  } else if (isset($userPosts[0]['userpost']['k']) &&
             $result = $_twister->newPostMessage($_SESSION['userName'],
                                                 Filter::int($userPosts[0]['userpost']['k']) + 1,
                                                 Filter::post($_POST['message']))) {

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