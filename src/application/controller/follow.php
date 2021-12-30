<?php

if (!isset($_SESSION['userName'])) {

  header('Location: ' . PROJECT_HOST . '/login', true, 302);
}

$metaTitle = _('Following | Twisterarmy Cloud');

// Load dependencies
$metaStyles = [
  'css/template/default/module/menu.css',
  'css/template/default/module/post.css',
  'css/template/default/module/feed.css',
];

$metaScripts = [
  'js/module/menu.js',
  'js/module/post.js',
  'js/module/feed.js',
];

$followingUsersTotal = 0;
$followingUsers      = [];

foreach ((array) $_twister->getFollowing($_SESSION['userName']) as $followingUserName) {

  $followingUsers[] = [
    'name' => $followingUserName
  ];

  $followingUsersTotal++;
}

require(PROJECT_DIR . '/application/view/follow.phtml');