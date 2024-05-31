<?php
$title = "Register";
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
                <h1 class="mb-5">Register Page</h1>
                <form action="<?php echo BASE_URL . CONTROLLER . "register.php"; ?>" method="post">
                    <div class="mb-3">
                        <label for="exampleInputFullName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="exampleInputFullName" name="exampleInputFullName"
                               value="<?php echo isset($_GET['full_name']) ? htmlspecialchars($_GET['full_name']) : ''; ?>">
                    </div>
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
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Repeat Password</label>
                        <input type="password" class="form-control" id="exampleInputPassword1"
                               name="exampleInputPassword1"
                               value="<?php echo isset($_GET['password1']) ? htmlspecialchars($_GET['password1']) : ''; ?>">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1" name="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">Remember me</label>
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