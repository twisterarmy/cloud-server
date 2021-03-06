<?php

if (!isset($_SESSION['userName'])) {

  header('Location: ' . PROJECT_HOST . '/login', true, 302);
}

$metaTitle = _('Home | Twisterarmy Cloud');

// Load dependencies
$metaStyles = [
  'css/template/default/module/menu.css',
  'css/template/default/module/post.css',
  'css/template/default/module/feed.css',
  'css/template/default/module/users.css',
];

$metaScripts = [
  'js/module/menu.js',
  'js/module/post.js',
  'js/module/feed.js',
  'js/module/users.js',
  'js/home.js',
];

require(PROJECT_DIR . '/application/view/home.phtml');