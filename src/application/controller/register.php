<?php

if (isset($_SESSION['username'])) {
    header('Location: ' . PROJECT_HOST, true, 302);
}

$metaTitle = _('Register | Twisterarmy Cloud');

require(PROJECT_DIR . '/application/view/register.phtml');