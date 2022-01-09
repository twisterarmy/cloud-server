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

      $messages = [];

      if ($response['error']) {

        $this->_error = _($response['error']['message']);

      } else {

        // Format response
        if (isset($response['result'])) {

          foreach ($response['result'] as $message) {

            if (isset($message['userpost']['height']) &&
                isset($message['userpost']['k']) &&
                isset($message['userpost']['time']) &&
                isset($message['userpost']['n']) &&
                isset($message['userpost']['msg'])) {

                // Process reTwist if exist
                $reTwist = [];
                if (isset($message['userpost']['rt']) &&

                    isset($message['userpost']['rt']['height']) &&
                    isset($message['userpost']['rt']['k']) &&
                    isset($message['userpost']['rt']['time']) &&
                    isset($message['userpost']['rt']['msg']) &&
                    isset($message['userpost']['rt']['n'])) {

                  // Split reTwist parts
                  $reTwists = [Filter::post($message['userpost']['rt']['msg'])];

                  for ($i = 0; $i <= APPLICATION_MAX_POST_SPLIT; $i++) {

                    $n = sprintf('msg%s', $i);

                    if (isset($message['userpost']['rt'][$n])) {
                      $reTwists[] = Filter::post($message['userpost']['rt'][$n]);
                    }
                  }

                  $reTwist = [
                    'height'   => Filter::int($message['userpost']['rt']['height']),
                    'k'        => Filter::int($message['userpost']['rt']['k']),
                    'time'     => Filter::int($message['userpost']['rt']['time']),

                    'userName' => Filter::userName($message['userpost']['rt']['n']),
                    'message'  => implode('', $reTwists),
                  ];
                }

                // Split message parts
                $messageParts = [Filter::post($message['userpost']['msg'])];

                for ($i = 0; $i <= APPLICATION_MAX_POST_SPLIT; $i++) {

                  $n = sprintf('msg%s', $i);

                  if (isset($message['userpost'][$n])) {
                    $messageParts[] = Filter::post($message['userpost'][$n]);
                  }
                }

                $messages[] = [
                  'height'   => Filter::int($message['userpost']['height']),
                  'k'        => Filter::int($message['userpost']['k']),
                  'time'     => Filter::int($message['userpost']['time']),

                  'userName' => Filter::userName($message['userpost']['n']),
                  'message'  => implode('', $messageParts),

                  'reTwist'  => $reTwist
                ];
            }
          }
        }

        return $messages; // Array
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

  public function getDHTProfileRevisions(string $userName) {

    $this->_curl->prepare(
      '/',
      'POST',
      30,
      [
        'jsonrpc' => '2.0',
        'method'  => 'dhtget',
        'params'  => [
          $userName,
          'profile',
          's'
        ],
        'id' => time() + rand()
      ]
    );

    if ($response = $this->_curl->execute()) {

      if ($response['error']) {

        $this->_error = _($response['error']['message']);

      } else {

        $dhtProfileVersions = [];
        foreach ((array) $response['result'] as $dhtProfileVersion) {

          // Required fields validation needed to make the DB revision compare
          if (isset($dhtProfileVersion['p']['height']) &&
              isset($dhtProfileVersion['p']['seq']) &&
              isset($dhtProfileVersion['p']['time'])) {

              // Format revision response
              $dhtProfileVersions[] = [
                'height'     => (int) $dhtProfileVersion['p']['height'],
                'seq'        => (int) $dhtProfileVersion['p']['seq'],
                'time'       => (int) $dhtProfileVersion['p']['time'],

                'fullName'   => isset($dhtProfileVersion['p']['v']['fullname'])   ? (string) $dhtProfileVersion['p']['v']['fullname']   : '',
                'bio'        => isset($dhtProfileVersion['p']['v']['bio'])        ? (string) $dhtProfileVersion['p']['v']['bio']        : '',
                'location'   => isset($dhtProfileVersion['p']['v']['location'])   ? (string) $dhtProfileVersion['p']['v']['location']   : '',
                'url'        => isset($dhtProfileVersion['p']['v']['url'])        ? (string) $dhtProfileVersion['p']['v']['url']        : '',
                'bitMessage' => isset($dhtProfileVersion['p']['v']['bitmessage']) ? (string) $dhtProfileVersion['p']['v']['bitmessage'] : '',
                'tox'        => isset($dhtProfileVersion['p']['v']['tox'])        ? (string) $dhtProfileVersion['p']['v']['tox']        : '',
              ];
          }
        }

        return $dhtProfileVersions; // Formatted array
      }
    }

    return [];
  }

  public function getDHTAvatarRevisions(string $userName) {

    $this->_curl->prepare(
      '/',
      'POST',
      30,
      [
        'jsonrpc' => '2.0',
        'method'  => 'dhtget',
        'params'  => [
          $userName,
          'avatar',
          's'
        ],
        'id' => time() + rand()
      ]
    );

    if ($response = $this->_curl->execute()) {

      if ($response['error']) {

        $this->_error = _($response['error']['message']);

      } else {

        $dhtAvatarVersions = [];
        foreach ((array) $response['result'] as $dhtAvatarVersion) {

          // Required fields validation needed to make the DB revision compare
          if (isset($dhtAvatarVersion['p']['height']) &&
              isset($dhtAvatarVersion['p']['seq']) &&
              isset($dhtAvatarVersion['p']['time'])) {

              // Format revision response
              $dhtAvatarVersions[] = [
                'height' => (int) $dhtAvatarVersion['p']['height'],
                'seq'    => (int) $dhtAvatarVersion['p']['seq'],
                'time'   => (int) $dhtAvatarVersion['p']['time'],

                'data'   => isset($dhtAvatarVersion['p']['v']) ? (string) $dhtAvatarVersion['p']['v'] : '',
              ];
          }
        }

        return $dhtAvatarVersions; // Formatted array
      }
    }

    return [];
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

  public function putDHT(string $peerAlias,
                         string $command,
                         string $flag, // s(ingle)/m(ulti)
                         mixed  $value,
                         string $sig_user,
                         int    $seq) {

    $this->_curl->prepare(
      '/',
      'POST',
      30,
      [
        'jsonrpc' => '2.0',
        'method'  => 'dhtput',
        'params'  => [
          $peerAlias,
          $command,
          $flag,
          $value,
          $sig_user,
          $seq,
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