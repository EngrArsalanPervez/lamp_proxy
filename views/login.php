<?php
$title = "Login";
require '../header.php';

global $session;

if ($session['logged_in'] === true) {
    header("Location: " . BASE_URL);
    exit();
}
?>

    <div class="container mt-5 ">
        <div class="row justify-content-center ">
            <div class="col-6">
                <h1 class="mb-5">Login Page</h1>
                <form action="<?php echo BASE_URL . CONTROLLER . "login.php"; ?>" method="post">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" name="exampleInputEmail1"
                               aria-describedby="emailHelp"
                               value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
                        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="exampleInputPassword"
                               name="exampleInputPassword"
                               value="<?php echo isset($_GET['password']) ? htmlspecialchars($_GET['password']) : ''; ?>">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="rememberMe"
                               name="rememberMe" <?php echo isset($_GET['rememberMe']) ? htmlspecialchars($_GET['rememberMe']) : ''; ?>>
                        <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div>
                    <?php
                    if (isset($_GET['error'])) {
                        echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_GET['error']) . '</div>';
                    }
                    if (isset($_GET['success'])) {
                        echo '<div class="alert alert-success" role="alert">' . htmlspecialchars($_GET['success']) . '</div>';
                    }
                    ?>
                    <div class="text-center">
                        <button type="submit" class="btn btn-secondary w-50">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php require '../footer.php'; ?>