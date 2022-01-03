<?php

$response = [
  'success' => false,
  'message' => _('Internal server error'),
];

if (isset($_SESSION['userName']) && isset($_POST['userName'])) {

  $userName = Filter::userName($_POST['userName']);

  if ($_SESSION['userName'] != $userName) {

    $_twister->unFollow($_SESSION['userName'], [$userName]);

    $response = [
      'success' => true,
      'message' => _('Unfollowed successfully'),
    ];

  } else {

    $response = [
      'success' => false,
      'message' => _("Can't unfollow yourself"),
    ];
  }

} else {

  $response = [
    'success' => false,
    'message' => _('Session expired. Please, reload the page.'),
  ];

}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);