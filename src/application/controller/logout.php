
<?php

if (isset($_SESSION['userName'])) {

    $_memcache->delete('api.user.profile.' . $_SESSION['userName']);
    $_memcache->delete('api.user.avatar.' . $_SESSION['userName']);

    session_destroy();
}

header('Location: ' . PROJECT_HOST, true, 302);