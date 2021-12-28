<?php

header('HTTP/1.0 404 Not Found');

$metaTitle = _('Page not Found | Twisterarmy Cloud');

require(PROJECT_DIR . '/application/view/error/404.phtml');