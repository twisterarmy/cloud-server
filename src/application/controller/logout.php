
<?php

if (isset($_SESSION['userName'])) {
    session_destroy();
}

header('Location: ' . PROJECT_HOST, true, 302);