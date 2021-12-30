<?php

$response = [
  'success' => false,
  'message' => _('Internal server error'),
];

if (isset($_SESSION['userName']) && isset($_POST['userName'])) {

  $result = $_twister->unFollow($_SESSION['userName'], [$_POST['userName']]);

  $response = [
    'success' => true,
    'message' => _('Unfollowed successfully'),
  ];

} else {

  $response = [
    'success' => false,
    'message' => _('Session expired. Please, reload the page.'),
  ];

}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);