
<?php

if (isset($_SESSION['username'])) {
    session_destroy();
}

header('Location: ' . PROJECT_HOST, true, 302);