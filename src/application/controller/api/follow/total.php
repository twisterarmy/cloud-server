<?php

$response = [
  'success' => false,
  'message' => _('Internal server error'),
  'total'   => 0
];

if (isset($_SESSION['userName'])) {

  $userName = isset($_POST['userName']) ? Filter::userName($_POST['userName']) : $_SESSION['userName'];

  $followingUsersTotal = 0;

  foreach ((array) $_twister->getFollowing($_SESSION['userName']) as $followingUserName) {
    $followingUsersTotal++;
  }

  $response = [
    'success' => true,
    'message' => _('Follow totals received'),
    'total'   => $followingUsersTotal
  ];

} else {

  $response = [
    'success' => false,
    'message' => _('Session expired. Please, reload the page.'),
    'total'   => 0
  ];

}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);