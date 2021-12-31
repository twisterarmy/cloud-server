<?php

$userName = false;

if (isset($_GET['_route_'])) {

  $route = explode('/', $_GET['_route_']);

  if (isset($route[1])) {
    $userName = filter::userName($route[1]);
  }
}

require(PROJECT_DIR . '/application/view/common/module/following.phtml');