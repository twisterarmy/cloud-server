
<?php

if (isset($_SESSION['userName'])) {

    session_destroy();

    $_memcache->flush();
}

header('Location: ' . PROJECT_HOST, true, 302);