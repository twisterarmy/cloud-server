<?php

// Dependencies
require('config.php');

require(PROJECT_DIR . '/application/model/model.php');
require(PROJECT_DIR . '/application/model/block.php');
require(PROJECT_DIR . '/application/model/user.php');

require(PROJECT_DIR . '/system/curl.php');
require(PROJECT_DIR . '/system/twister.php');
require(PROJECT_DIR . '/system/helper/filter.php');
require(PROJECT_DIR . '/system/helper/valid.php');

// Init libraries
$_twister = new Twister(
  new Curl(
    TWISTER_PROTOCOL,
    TWISTER_HOST,
    TWISTER_PORT,
    TWISTER_USER,
    TWISTER_PASSWORD
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

/*
$_modelBlock = new ModelBlock(
  DB_DATABASE,
  DB_HOST,
  DB_PORT,
  DB_USER,
  DB_PASSWORD
);
*/

// Route
if (isset($_GET['_route_'])) {

    switch ($_GET['_route_']) {
      case '':
        require(PROJECT_DIR . '/application/controller/index.php');
      break;
      case 'login':
        require(PROJECT_DIR . '/application/controller/login.php');
      break;
      case 'register':
        require(PROJECT_DIR . '/application/controller/register.php');
      break;
      case 'api/user':
        require(PROJECT_DIR . '/application/controller/api/user.php');
      break;
      default:
        require(PROJECT_DIR . '/application/controller/error/404.php');
    }

} else {
  require(PROJECT_DIR . '/application/controller/index.php');
}