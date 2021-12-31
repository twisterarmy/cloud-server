<?php

$response = [
  'success' => false,
  'message' => _('Internal server error'),
  'users'   => [],
  'total'   => 0
];

$usersTotal = 0;
$users      = [];

foreach ((array) $_modelUser->getLastRandomUsers(APPLICATION_MODULE_USERS_LIMIT) as $user) {

  $users[] = [
    'userName' => $user['username']
  ];

  $usersTotal++;
}

$response = [
  'success' => true,
  'message' => _('Users received'),
  'users'   => $users,
  'total'   => $usersTotal
];

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);