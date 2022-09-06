<?php include("classes/databaseObject_class.php"); ?>
<?php include("classes/sessionCheck_class.php"); ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./main.css" rel="stylesheet">
    <link href="styles,css" rel="stylesheet">
    <title>Database</title>
</head>

<?php
//session check
$session = new Session();
$wrongPassword = false;
//session logout
if (isset($_POST["logout"])) {
    $session->logout();
}
if (isset($_POST["login"])) {
    if (!$session->login($_POST["username"], $_POST["password"])) {
        $wrongPassword = true;
    }
    // if failed do something
}
if ($session->session["active"] == true) {
    //do something here, go to a different page or something
?>
    <form method="POST">
        <div class="col-sm-10 offset-sm-2">
            <button name="logout" class="btn btn-danger">Log out</button>
        </div>
    </form>
<?php
} else {
?>

    <body>
        <div class="tab-content">
            <div class="col-md-6">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">log in</h5>
                        <form class="" method="POST">
                            <div class="position-relative row form-group"><label for="loginUsername" class="col-sm-2 col-form-label">Username</label>
                                <div class="col-sm-10"><input name="username" id="loginUsername" placeholder="username" type="text" class="form-control" required></div>
                            </div>
                            <div class="position-relative row form-group"><label for="loginPassword" class="col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-10"><input name="password" id="loginPassword" placeholder="password" type="password" class="form-control" required></div>
                            </div>
                            <?php if ($wrongPassword == true) { ?>
                                <button class="mb-2 mr-2 btn btn-danger" disabled>Wrong username or password</button>
                            <?php } ?>
                            <div class="position-relative row form-check">
                                <div class="col-sm-10 offset-sm-2">
                                    <button name="login" class="btn btn-secondary">Log in</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
<?php } ?>

</html>