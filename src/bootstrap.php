<?php

// Dependencies
require('config.php');

require(PROJECT_DIR . '/application/model/model.php');
require(PROJECT_DIR . '/application/model/block.php');
require(PROJECT_DIR . '/application/model/user.php');

require(PROJECT_DIR . '/system/curl.php');
require(PROJECT_DIR . '/system/twister.php');
require(PROJECT_DIR . '/system/icon.php');
require(PROJECT_DIR . '/system/helper/filter.php');
require(PROJECT_DIR . '/system/helper/valid.php');
require(PROJECT_DIR . '/system/helper/format.php');

// Init libraries
$_twister = new Twister(
  new Curl(
    TWISTER_PROTOCOL,
    TWISTER_HOST,
    TWISTER_PORT,
    TWISTER_USER,
    TWISTER_PASSWORD,
    TWISTER_SSL
  )
);

// Init models
$_modelUser = new ModelUser(
  DB_DATABASE,
  DB_HOST,
  DB_PORT,
  DB_USER,
  DB_PASSWORD
);

$_modelBlock = new ModelBlock(
  DB_DATABASE,
  DB_HOST,
  DB_PORT,
  DB_USER,
  DB_PASSWORD
);

// Start session
if (!session_id()) {
     session_start();
}

// Route
if (isset($_GET['_route_'])) {

  switch ($_GET['_route_']) {

      // Pages
      case '':
        require(PROJECT_DIR . '/application/controller/home.php');
      break;
      case 'login':
        require(PROJECT_DIR . '/application/controller/login.php');
      break;
      case 'logout':
        require(PROJECT_DIR . '/application/controller/logout.php');
      break;
      /*
      case 'follow':
        require(PROJECT_DIR . '/application/controller/follow.php');
      break;
      */
      case 'register':
        require(PROJECT_DIR . '/application/controller/register.php');
      break;

      // API calls
      case 'api/image':
        require(PROJECT_DIR . '/application/controller/api/image.php');
      break;
      case 'api/post/add':
        require(PROJECT_DIR . '/application/controller/api/post/add.php');
      break;
      case 'api/post/get':
        require(PROJECT_DIR . '/application/controller/api/post/get.php');
      break;
      case 'api/follow/total':
        require(PROJECT_DIR . '/application/controller/api/follow/total.php');
      break;
      case 'api/follow/get':
        require(PROJECT_DIR . '/application/controller/api/follow/get.php');
      break;
      case 'api/follow/add':
        require(PROJECT_DIR . '/application/controller/api/follow/add.php');
      break;
      case 'api/follow/delete':
        require(PROJECT_DIR . '/application/controller/api/follow/delete.php');
      break;

      // Multi-attribute pages
      default:

        switch (true) {

          // Pages
          case preg_match('|^follow[/\w_]?|i', $_GET['_route_']):
            require(PROJECT_DIR . '/application/controller/follow.php');
          break;

          // 404
          default:
          require(PROJECT_DIR . '/application/controller/error/404.php');
        }
    }

} else {
  require(PROJECT_DIR . '/application/controller/home.php');
}