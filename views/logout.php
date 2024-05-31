<?php
$title = "Login";
require '../header.php';
?>

    <div class="container mt-5 ">
        <div class="row justify-content-center ">
            <div class="col-6">
                <h1 class="mb-5">Logout Page</h1>
                <form action="<?php echo BASE_URL . CONTROLLER . "logout.php"; ?>" method="post">
                    <h5 class="text-danger">Are you sure?</h5>
                    <div class="text-center">
                        <button type="submit" class="btn btn-secondary w-50">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php require '../footer.php'; ?>