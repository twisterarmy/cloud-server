<?php

if (!isset($_SESSION['username'])) {

  header('Location: ' . PROJECT_HOST . '/login', true, 302);
}

$metaTitle = _('Following | Twisterarmy Cloud');

$followingUsersTotal = 0;
$followingUsers      = [];

foreach ((array) $_twister->getFollowing($_SESSION['username']) as $followingUserName) {

  $followingUsers[] = [
    'name' => $followingUserName
  ];

  $followingUsersTotal++;
}

require(PROJECT_DIR . '/application/view/following.phtml');