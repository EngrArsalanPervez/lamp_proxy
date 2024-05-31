<?php
$title = "Home";
require 'header.php';

global $session;

?>

    <div class="container">
        <h1>Welcome to the Home Page</h1>
        <p>This is the main content of the home page.</p>
        <?php
        print_sessions_var($session);
        ?>
    </div>

<?php require 'footer.php'; ?>