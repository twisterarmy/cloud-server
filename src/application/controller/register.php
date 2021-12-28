<?php

if (isset($_SESSION['username'])) {
    header('Location: ' . PROJECT_HOST, true, 302);
}

require(PROJECT_DIR . '/application/view/register.phtml');