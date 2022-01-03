<?php

// Dependencies
require(dirname(dirname(__DIR__)) . '/src/config.php');

require(PROJECT_DIR . '/application/model/model.php');
require(PROJECT_DIR . '/application/model/user.php');
require(PROJECT_DIR . '/application/model/block.php');

require(PROJECT_DIR . '/system/curl.php');
require(PROJECT_DIR . '/system/twister.php');
require(PROJECT_DIR . '/system/helper/filter.php');

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

print("import begin...\n");

$nextBlock = $_modelBlock->getNextBlock();

while (true) {

  if (!$blockHash = $_twister->getBlockHash($nextBlock)) {

    print("database up to date\n");
    exit;
  }

  $blockHash = Filter::blockHash($blockHash);

  if (!$block = $_twister->getBlock($blockHash)) {

    trigger_error(sprintf('could not receive block info on %s (%s)', $nextBlock, $blockHash));
    exit;
  }

  // Add block
  if ($blockId = $_modelBlock->addBlock($blockHash, time())) {

    print(sprintf("add block %s\n", $blockId));

    // Add users
    foreach ($block['usernames'] as $userName) {

      $userName = Filter::userName($userName);

      if (!$_modelUser->addUser($blockId, $userName)) {
        trigger_error(sprintf('could not add user %s in block %s)', $userName, $blockId));
        exit;
      }

      print(sprintf("add user %s\n", $userName));
    }

    // Update queue
    $nextBlock++;

  } else {

    trigger_error(sprintf('could not add block %s (%s)', $nextBlock, $blockHash));
    exit;
  }
}