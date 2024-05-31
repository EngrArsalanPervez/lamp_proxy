<?php
require "../config/config.php";
require "../config/db.php";
require "../config/utilities.php";

global $db;
$urlArgs = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $raw_input = array(
        'email' => $_POST['exampleInputEmail1'] ?? '',
        'password' => $_POST['exampleInputPassword'] ?? '',
        'rememberMe' => isset($_POST['rememberMe']) ? "checked" : false,
    );
    $urlArgs = generate_url_args($raw_input);

    $processed_input = validate_login_form($raw_input);
    if ($processed_input['check'] === false) {
        header("Location: " . BASE_URL . VIEWS . "login.php?error=" . $processed_input['error'] . '&' . $urlArgs);
        exit();
    }

    // DB
    $return = $db->login_user($processed_input['email'], $processed_input['password']);
    if ($return['check'] === false) {
        header("Location: " . BASE_URL . VIEWS . "login.php?error=" . $return['msg'] . '&' . $urlArgs);
    } else {
        $session = array(
            'id' => $return['id'],
            'full_name' => $return['full_name'],
            'email' => $processed_input['email'],
            'logged_in' => true,
        );
        save_sessions_var($session);

        if ($raw_input['rememberMe']) {
            $db->remember_me($session);
        }

        setcookie('full_name', $return['full_name'], time() + (86400 * 30), "/"); // 30 days expiration

        header("Location: " . BASE_URL . VIEWS . "login.php?success=" . $return['msg'] . '&' . $urlArgs);
    }
} else {
    header("Location: " . BASE_URL . VIEWS . "login.php?error=Empty Form" . '&' . $urlArgs);
}
exit();
