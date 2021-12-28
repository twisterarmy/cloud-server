<?php

class Curl {

  private $_curl;
  private $_protocol;
  private $_host;
  private $_port;
  private $_ssl;

  public function __construct(string $protocol,
                              string $host,
                              int    $port,
                              string $username,
                              string $password,
                              bool   $ssl) {

    $this->_protocol = $protocol;
    $this->_host     = $host;
    $this->_port     = $port;
    $this->_ssl      = $ssl;

    $this->_curl     = curl_init();

    if ($username && $password) {
        $headers = [
          'Content-Type: application/json', sprintf('Authorization: Basic %s', base64_encode($username . ':' . $password))
        ];
    } else {
        $headers = [
          'Content-Type: application/json'
        ];
    }

    curl_setopt_array($this->_curl, [CURLOPT_RETURNTRANSFER => true,
                                     CURLOPT_FOLLOWLOCATION => true,
                                     CURLOPT_FRESH_CONNECT  => true,
                                     //CURLOPT_VERBOSE        => true,
                                     CURLOPT_HTTPHEADER     => $headers,
                                    ]);
  }

  public function __destruct() {
    curl_close($this->_curl);
  }

  public function prepare(string $url,
                          string $method,
                          int    $timeout = 30,
                          array  $postFields = [],
                          bool   $validate = false) {

    curl_setopt($this->_curl, CURLOPT_URL, $this->_protocol . '://' . $this->_host . ':' . $this->_port . $url);
    curl_setopt($this->_curl, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($this->_curl, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($this->_curl, CURLOPT_TIMEOUT, $timeout);

    if ($this->_ssl) {
      curl_setopt($this->_curl, CURLOPT_SSL_VERIFYPEER, $validate);
      curl_setopt($this->_curl, CURLOPT_SSL_VERIFYHOST, $validate);
    }

    if ($method == 'POST' && $postFields) {
      curl_setopt($this->_curl, CURLOPT_POSTFIELDS, json_encode($postFields));
    }
  }

  public function execute(bool $json = true) {

    $response    = curl_exec($this->_curl);
    $errorNumber = curl_errno($this->_curl);
    $errorText   = curl_error($this->_curl);

    if ($errorNumber > 0) {

      trigger_error($errorText);

      return false;
    }

    if ($response) {
      if ($json) {
        return json_decode($response, true);
      } else {
        return $response;
      }
    }

    trigger_error('curl error');

    return false;
  }
}