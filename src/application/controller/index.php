<?php

if (!isset($_SESSION['username'])) {

  header('Location: ' . PROJECT_HOST . '/login', true, 302);
}

$metaTitle = _('Home | Twisterarmy Cloud');

require(PROJECT_DIR . '/application/view/home.phtml');