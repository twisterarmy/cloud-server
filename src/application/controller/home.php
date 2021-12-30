<?php

if (!isset($_SESSION['userName'])) {

  header('Location: ' . PROJECT_HOST . '/login', true, 302);
}

$metaTitle = _('Home | Twisterarmy Cloud');

$followingUsersTotal = 0;
foreach ((array) $_twister->getFollowing($_SESSION['userName']) as $followingUserName) {
  $followingUsersTotal++;
}

require(PROJECT_DIR . '/application/view/home.phtml');