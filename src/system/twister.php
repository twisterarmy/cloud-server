<?php

class Twister {

  private $_curl;
  private $_error;

  public function __construct(Curl $curl) {

    $this->_curl = $curl;
  }

  public function getError() {

    return $this->_error; // Error string
  }

  public function importWallet(string $userName, string $userPrivateKey) {

    $this->_curl->prepare(
      '/',
      'POST',
      30,
      [
        'jsonrpc' => '2.0',
        'method'  => 'importprivkey',
        'params'  => [
          $userPrivateKey,
          $userName
        ],
        'id' => time() + rand()
      ]
    );

    if ($response = $this->_curl->execute()) {

      if ($response['error']) {

        $this->_error = _($response['error']['message']);

      } else {

        return true; // empty error, empty result keys on success
      }
    }

    return false;
  }

  public function getBlockHash(int $number) {

    $this->_curl->prepare(
      '/',
      'POST',
      30,
      [
        'jsonrpc' => '2.0',
        'method'  => 'getblockhash',
        'params'  => [
          $number
        ],
        'id' => time() + rand()
      ]
    );

    if ($response = $this->_curl->execute()) {

      if ($response['error']) {

        $this->_error = _($response['error']['message']);

      } else {

        return $response['result']; // Block hash
      }
    }

    return false;
  }

  public function getBlock(string $hash) {

    $this->_curl->prepare(
      '/',
      'POST',
      30,
      [
        'jsonrpc' => '2.0',
        'method'  => 'getblock',
        'params'  => [
          $hash
        ],
        'id' => time() + rand()
      ]
    );

    if ($response = $this->_curl->execute()) {

      if ($response['error']) {

        $this->_error = _($response['error']['message']);

      } else {

        return $response['result']; // Array of the block data
      }
    }

    return false;
  }

  public function getPosts(array $userNames, int $limit) {

    $data = [];
    foreach ($userNames as $userName) {
      $data[] = [
        'username' => $userName
      ];
    }

    $this->_curl->prepare(
      '/',
      'POST',
      30,
      [
        'jsonrpc' => '2.0',
        'method'  => 'getposts',
        'params'  => [
          $limit,
          $data
        ],
        'id' => time() + rand()
      ]
    );

    if ($response = $this->_curl->execute()) {

      if ($response['error']) {

        $this->_error = _($response['error']['message']);

      } else {

        return $response['result']; // Array
      }
    }

    return false;
  }

  public function follow(string $userName, array $userNames) {

    $this->_curl->prepare(
      '/',
      'POST',
      30,
      [
        'jsonrpc' => '2.0',
        'method'  => 'follow',
        'params'  => [
          $userName,
          $userNames
        ],
        'id' => time() + rand()
      ]
    );

    if ($response = $this->_curl->execute()) {

      if ($response['error']) {

        $this->_error = _($response['error']['message']);

      } else {

        return $response['result'];
      }
    }

    return false;
  }

  public function unFollow(string $userName, array $userNames) {

    $this->_curl->prepare(
      '/',
      'POST',
      30,
      [
        'jsonrpc' => '2.0',
        'method'  => 'unfollow',
        'params'  => [
          $userName,
          $userNames
        ],
        'id' => time() + rand()
      ]
    );

    if ($response = $this->_curl->execute()) {

      if ($response['error']) {

        $this->_error = _($response['error']['message']);

      } else {

        return $response['result'];
      }
    }

    return false;
  }

  public function getFollowing(string $userName) {

    $this->_curl->prepare(
      '/',
      'POST',
      30,
      [
        'jsonrpc' => '2.0',
        'method'  => 'getfollowing',
        'params'  => [
          $userName
        ],
        'id' => time() + rand()
      ]
    );

    if ($response = $this->_curl->execute()) {

      if ($response['error']) {

        $this->_error = _($response['error']['message']);

      } else {

        return $response['result']; // Array usernames
      }
    }

    return false;
  }

  public function getDHT(string $userName, string $command, string $flag) {

    $this->_curl->prepare(
      '/',
      'POST',
      30,
      [
        'jsonrpc' => '2.0',
        'method'  => 'dhtget',
        'params'  => [
          $userName,
          $command,
          $flag
        ],
        'id' => time() + rand()
      ]
    );

    if ($response = $this->_curl->execute()) {

      if ($response['error']) {

        $this->_error = _($response['error']['message']);

      } else {

        return $response['result']; // Array
      }
    }

    return false;
  }

  public function createWalletUser(string $userName) {

    $this->_curl->prepare(
      '/',
      'POST',
      30,
      [
        'jsonrpc' => '2.0',
        'method'  => 'createwalletuser',
        'params'  => [
          $userName
        ],
        'id' => time() + rand()
      ]
    );

    if ($response = $this->_curl->execute()) {

      if ($response['error']) {

        $this->_error = _($response['error']['message']);

      } else {

        return $response['result']; // Private Key
      }
    }

    return false;
  }

  public function sendNewUserTransaction(string $userName) {

    $this->_curl->prepare(
      '/',
      'POST',
      30,
      [
        'jsonrpc' => '2.0',
        'method'  => 'sendnewusertransaction',
        'params'  => [
          $userName
        ],
        'id' => time() + rand()
      ]
    );

    if ($response = $this->_curl->execute()) {

      if ($response['error']) {

        $this->_error = _($response['error']['message']);

      } else {

        return $response['result']; // transaction ID
      }
    }

    return false;
  }

  public function newPostMessage(string $userName, int $index, string $message) {

    $this->_curl->prepare(
      '/',
      'POST',
      30,
      [
        'jsonrpc' => '2.0',
        'method'  => 'newpostmsg',
        'params'  => [
          $userName,
          $index,
          $message
        ],
        'id' => time() + rand()
      ]
    );

    if ($response = $this->_curl->execute()) {

      if ($response['error']) {

        $this->_error = _($response['error']['message']);

      } else {

        return $response['result']; // transaction ID
      }
    }

    return false;
  }
}