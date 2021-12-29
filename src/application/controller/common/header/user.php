<?php

$usersTotal  = $_modelUser->getTotal();
$blocksTotal = $_modelBlock->getTotal();

require(PROJECT_DIR . '/application/view/common/header/user.phtml');