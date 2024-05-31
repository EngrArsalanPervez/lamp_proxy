<?php

function sanitizeInput($data): string
{
    return htmlspecialchars(stripslashes(trim($data)));
}

function validate_username($username): true|string
{
    // Check length
    if (strlen($username) < 3 || strlen($username) > 20) {
        return "Username must be between 3 and 20 characters long.";
    }

    // Check allowed characters
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        return "Username can only contain letters, numbers, and underscores.";
    }

    return true;
}

function validate_password($password, $confirm_password): true|string
{
    // Check if passwords match
    if ($password !== $confirm_password) {
        return "Passwords do not match.";
    }

    // Check length
    if (strlen($password) < 8) {
        return "Password must be at least 8 characters long.";
    }

    // Check for at least one uppercase letter, one lowercase letter, one number, and one special character
    if (!preg_match('/[A-Z]/', $password)) {
        return "Password must include at least one uppercase letter.";
    }
    if (!preg_match('/[a-z]/', $password)) {
        return "Password must include at least one lowercase letter.";
    }
    if (!preg_match('/[0-9]/', $password)) {
        return "Password must include at least one number.";
    }
    if (!preg_match('/[\W_]/', $password)) {
        return "Password must include at least one special character.";
    }

    return true;
}

function validate_register_form($raw_input): array
{
    if (empty($raw_input['full_name'])) {
        return array(
            'check' => false,
            'error' => "Please specify Full Name",
        );
    } else if (empty($raw_input['email'])) {
        return array(
            'check' => false,
            'error' => "Please specify Email",
        );
    } else if (empty($raw_input['password'])) {
        return array(
            'check' => false,
            'error' => "Please specify Password",
        );
    } else if (empty($raw_input['password1'])) {
        return array(
            'check' => false,
            'error' => "Please specify Repeat Password",
        );
    }

    // Sanitize input
    $full_name = sanitizeInput($raw_input['full_name']);
    $email = sanitizeInput($raw_input['email']);
    $password = sanitizeInput($raw_input['password']);
    $password1 = sanitizeInput($raw_input['password1']);

    // Validate username
    // $username_validation = validate_username($full_name);
    // if ($username_validation !== true) {
    //     return array(
    //         'check' => false,
    //         'error' => $username_validation,
    //     );
    // }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array(
            'check' => false,
            'error' => "Invalid email address",
        );
    }

    // Validate password
    $password_validation = validate_password($password, $password1);
    if ($password_validation !== true) {
        return array(
            'check' => false,
            'error' => $password_validation,
        );
    }

    return array(
        'check' => true,
        'full_name' => $full_name,
        'email' => $email,
        'password' => $password,
        'password1' => $password1,
    );
}

function validate_login_form($raw_input): array
{
    if (empty($raw_input['email'])) {
        return array(
            'check' => false,
            'error' => "Please specify Email",
        );
    } else if (empty($raw_input['password'])) {
        return array(
            'check' => false,
            'error' => "Please specify Password",
        );
    }

    // Sanitize input
    $email = sanitizeInput($raw_input['email']);
    $password = sanitizeInput($raw_input['password']);

    // Validate username
    // $username_validation = validate_username($full_name);
    // if ($username_validation !== true) {
    //     return array(
    //         'check' => false,
    //         'error' => $username_validation,
    //     );
    // }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return array(
            'check' => false,
            'error' => "Invalid email address",
        );
    }

    return array(
        'check' => true,
        'email' => $email,
        'password' => $password,
    );
}

function generate_url_args($raw_input): string
{
    $keyValuePairs = array_map(function ($key, $value) {
        return $key . '=' . $value;
    }, array_keys($raw_input), $raw_input);
    return implode('&', $keyValuePairs);
}

function save_sessions_var($session): void
{
    $_SESSION['id'] = $session['id'];
    $_SESSION['full_name'] = $session['full_name'];
    $_SESSION['email'] = $session['email'];
    $_SESSION['logged_in'] = $session['logged_in'];
}

function get_sessions_var(): array
{
    return array(
        'id' => $_SESSION['id'] ?? '',
        'full_name' => $_SESSION['full_name'] ?? '',
        'email' => $_SESSION['email'] ?? '',
        'logged_in' => isset($_SESSION['logged_in']),
    );
}

function print_sessions_var($session): void
{
    var_dump($session);
}
