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
}