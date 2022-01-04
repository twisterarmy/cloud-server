<?php

$response = [
  'success' => false,
  'message' => _('Internal server error'),
  'filter'  => '',
  'format'  => '',
];

if (isset($_SESSION['userName'])) {

  if (isset($_POST['message'])) {

    $filter = Filter::post($_POST['message']);
    $format = Format::post($filter);

    $response = [
      'success' => true,
      'message' => _('Success'),
      'filter'  => $filter,
      'format'  => $format,
    ];
  } else {

    $response = [
      'success' => false,
      'message' => _('Message required'),
      'filter'  => '',
      'format'  => '',
    ];
  }

} else {

  $response = [
    'success' => false,
    'message' => _('Session expired. Please, reload the page.'),
    'filter'  => '',
    'format'  => '',
  ];
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);