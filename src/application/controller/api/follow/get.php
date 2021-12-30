<?php

$response = [
  'success' => false,
  'message' => _('Internal server error'),
  'users'   => [],
  'total'   => 0
];

if (isset($_SESSION['userName'])) {

  $userName = isset($_POST['userName']) ? $_POST['userName'] : $_SESSION['userName'];

  $followingUsersTotal = 0;
  $followingUsers      = [];

  foreach ((array) $_twister->getFollowing($_SESSION['userName']) as $followingUserName) {

    $followingUsers[] = [
      'userName' => $followingUserName
    ];

    $followingUsersTotal++;
  }

  $response = [
    'success' => true,
    'message' => _('Follow totals received'),
    'users'   => $followingUsers,
    'total'   => $followingUsersTotal
  ];

} else {

  $response = [
    'success' => false,
    'message' => _('Session expired. Please, reload the page.'),
    'users'   => [],
    'total'   => 0
  ];

}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);