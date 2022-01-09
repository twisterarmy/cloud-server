<?php

header('HTTP/1.0 404 Not Found');

$metaTitle = _('Page not Found | Twisterarmy Cloud');

// Load dependencies
$metaStyles  = ['css/template/default/error/404.css',];
$metaScripts = [];

require(PROJECT_DIR . '/application/view/error/404.phtml');