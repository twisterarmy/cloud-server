<?php

class Twister {

  private $_curl;
  private $_error;

  public function __construct(Curl $curl) {

    $this->_curl = $curl;
  }

  public function getError() {

    return $this->_error;
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

        return true;
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

        return $response['result'];
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

        return $response['result'];
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

        return $response['result'];
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

        return $response['result'];
      }
    }

    return false;
  }
}