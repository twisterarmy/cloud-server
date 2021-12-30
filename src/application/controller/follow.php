<?php

if (!isset($_SESSION['userName'])) {

  header('Location: ' . PROJECT_HOST . '/login', true, 302);
}

$metaTitle = _('Following | Twisterarmy Cloud');

$followingUsersTotal = 0;
$followingUsers      = [];

foreach ((array) $_twister->getFollowing($_SESSION['userName']) as $followingUserName) {

  $followingUsers[] = [
    'name' => $followingUserName
  ];

  $followingUsersTotal++;
}

require(PROJECT_DIR . '/application/view/follow.phtml');