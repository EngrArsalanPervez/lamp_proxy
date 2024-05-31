<?php
require "../config/config.php";
require "../config/db.php";
require "../config/utilities.php";

global $db;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $session = get_sessions_var();
    if ($session['logged_in'] === true) {
        if (isset($_COOKIE['remember_me'])) {
            $db->forget_me($session);
        }
    }

    session_unset();
    session_destroy();

    header("Location: " . BASE_URL . VIEWS . "login.php");
} else {
    header("Location: " . BASE_URL . VIEWS . "logout.php");
}
exit();
