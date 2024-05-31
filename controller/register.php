<?php
require "../config/config.php";
require "../config/db.php";
require "../config/utilities.php";

global $db;
$urlArgs = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $raw_input = array(
        'full_name' => $_POST['exampleInputFullName'] ?? '',
        'email' => $_POST['exampleInputEmail1'] ?? '',
        'password' => $_POST['exampleInputPassword'] ?? '',
        'password1' => $_POST['exampleInputPassword1'] ?? '',
    );
    $urlArgs = generate_url_args($raw_input);

    $processed_input = validate_register_form($raw_input);
    if ($processed_input['check'] === false) {
        header("Location: " . BASE_URL . VIEWS . "register.php?error=" . $processed_input['error'] . '&' . $urlArgs);
        exit();
    }

    // Hash the password for security
    $processed_input['password'] = password_hash($processed_input['password'], PASSWORD_BCRYPT);

    // DB
    $return = $db->register_user($processed_input['full_name'], $processed_input['email'], $processed_input['password']);
    if ($return['check'] === false) {
        header("Location: " . BASE_URL . VIEWS . "register.php?error=" . $return['msg'] . '&' . $urlArgs);
    } else {
        $session = array(
            'full_name' => $processed_input['full_name'],
            'email' => $processed_input['email'],
            'logged_in' => true,
        );
        save_sessions_var($session);

        header("Location: " . BASE_URL . VIEWS . "register.php?success=" . $return['msg'] . '&' . $urlArgs);
    }
} else {
    header("Location: " . BASE_URL . VIEWS . "register.php?error=Empty Form" . '&' . $urlArgs);
}
exit();
