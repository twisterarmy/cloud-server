<?php

if (isset($_SESSION['username'])) {
    header('Location: ' . PROJECT_HOST, true, 302);
}

$metaTitle = _('Register | Twisterarmy Cloud');

require(PROJECT_DIR . '/application/view/register.phtml');

// @TODO welcome message

/*
$metaTitle = _('Welcome | Twisterarmy Cloud');

$blockEstimated = 0;
$userName       = 'userName';
$userPrivateKey = '0000000000000000000000000000000000000000';

require(PROJECT_DIR . '/application/view/welcome.phtml');
*/