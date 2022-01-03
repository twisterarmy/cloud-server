<?php

if (!isset($_SESSION['userName'])) {

  header('Location: ' . PROJECT_HOST . '/login', true, 302);
}

$metaTitle = _('People | Twisterarmy Cloud');
$pageTitle = _('People');

// Load dependencies
$metaStyles = [
  'css/template/default/module/menu.css',
  'css/template/default/module/post.css',
  'css/template/default/module/feed.css',
  'css/template/default/module/following.css',
];

$metaScripts = [
  'js/module/menu.js',
  'js/module/post.js',
  'js/module/feed.js',
  'js/module/following.js',
  'js/people.js',
];

// Auto-following
if (isset($_GET['_route_'])) {

  $route = explode('/', $_GET['_route_']);

  if (isset($route[1])) {

    $userName = filter::userName($route[1]);
    $_twister->follow($_SESSION['userName'], [$userName]);

    $pageTitle = sprintf(_('@%s'), $userName);
  }
}

require(PROJECT_DIR . '/application/view/people.phtml');