<?php
require 'config/config.php';
require 'config/db.php';
require 'config/utilities.php';

global $db;
$current_page = basename($_SERVER['PHP_SELF']);
$session = get_sessions_var();
if ($session['logged_in'] === false) {
    if (isset($_COOKIE['remember_me'])) {
        $session = $db->login_with_token();
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?php
        switch ($current_page) {
            case 'index.php':
                echo "Welcome";
                break;
            case 'about.php':
                echo "About";
                break;
            case 'contact.php':
                echo "Contact";
                break;
            case 'login.php':
                echo "Login";
                break;
            case 'register.php':
                echo "Register";
                break;
            case 'logout.php':
                echo "Logout";
                break;
            default:
                echo "Proxy";
                break;
        }
        ?>
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
            <i class="bi bi-fingerprint">Proxy</i>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 w-100 justify-content-end">
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page === 'index.php' ? 'active' : ''; ?>" aria-current="page"
                       href="<?php echo BASE_URL; ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page === 'about.php' ? 'active' : ''; ?>"
                       href="<?php echo BASE_URL . VIEWS . 'about.php'; ?>">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page === 'contact.php' ? 'active' : ''; ?>"
                       href="<?php echo BASE_URL . VIEWS . 'contact.php'; ?>">Contact</a>
                </li>
                <?php
                if ($session['logged_in'] === true) {
                    // LoggedIN
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                           aria-expanded="false">
                            <?php echo $session['full_name']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Change Password</a></li>
                            <li><a class="dropdown-item" href="#">Change Email</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item <?php echo $current_page === 'logout.php' ? 'active' : ''; ?>"
                                   href="<?php echo BASE_URL . VIEWS . 'logout.php'; ?>">Logout</a>
                            </li>
                        </ul>
                    </li>
                    <?php
                } else {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'login.php' ? 'active' : ''; ?>"
                           href="<?php echo BASE_URL . VIEWS . 'login.php'; ?>">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'register.php' ? 'active' : ''; ?>"
                           href="<?php echo BASE_URL . VIEWS . 'register.php'; ?>">Register</a>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </div>
</nav>