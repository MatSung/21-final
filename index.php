<?php include("classes/databaseObject_class.php"); ?>
<?php include("classes/sessionManager_class.php"); ?>
<?php include("classes/settings_class.php"); ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./main.css" rel="stylesheet">
    <link href="styles,css" rel="stylesheet">
    <title>Login</title>
</head>

<?php

//set some user settings in a cookie
$userSettings = array(
    "pageSize" => [
        "history" => 0,
        "company" => 0,
        "companyType" => 0,
        "user" => 0,
        "userType" => 0,
        "client" => 0,
        "clientType" => 0,
        "settings" => 0,
        "rights" => 0,
        "select" => 0]
);

//if you are on this page it sets this cookie
setcookie("userSettings",json_encode($userSettings),time() + 60*60, "/");

//session check
$session = new Session();
$settings = new Settings();
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
    // if logged in, push me to dashboard
    header("Location: dashboard.php");
    // V leftovers V
?>
    <form method="POST" >
        <div class="col-sm-10 offset-sm-2">
            <button name="logout" class="btn btn-danger">Log out</button>
        </div>
    </form>
<?php
} else {
?>

    <body>
        <div class="app-main__inner">
            <div class="tab-content">
                <div class="row">
                    <div class="col-lg-6">
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
                                        <div class="mb-2 mr-2 badge badge-danger">Wrong username or password</div>
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

                    <?php
                    //check if the setting is set to enable
                    //database for settings
                    if ($settings->settings["enableRegistration"]) {
                        $alreadyExists = false;
                        $successRegister = false;
                        if (isset($_POST["register"])) {
                            if (!$session->register($_POST["username"], $_POST["password"], $_POST["name"], $_POST["surname"])) {
                                $alreadyExists = true;
                            } else {
                                $successRegister = true;
                            }
                        }

                    ?>
                        <div class="col-lg-6">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                    <h5 class="card-title">Register</h5>
                                    <form class="" method="POST">
                                        <div class="position-relative row form-group"><label for="registerUsername" class="col-sm-2 col-form-label">Username</label>
                                            <div class="col-sm-10"><input name="username" id="registerUsername" placeholder="username" type="text" class="form-control" required></div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="registerPassword" class="col-sm-2 col-form-label">Password</label>
                                            <div class="col-sm-10"><input name="password" id="registerPassword" placeholder="password" type="password" class="form-control" required></div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="registerName" class="col-sm-2 col-form-label">Name</label>
                                            <div class="col-sm-10"><input name="name" id="registerName" placeholder="name" type="text" class="form-control" required></div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="registerSurname" class="col-sm-2 col-form-label">Surname</label>
                                            <div class="col-sm-10"><input name="surname" id="registerSurname" placeholder="surname" type="text" class="form-control" required></div>
                                        </div>
                                        <?php if ($alreadyExists) { ?>
                                            <div class="mb-2 mr-2 badge badge-danger">User already exists</div>
                                        <?php } else if ($successRegister) { ?>
                                            <div class="mb-2 mr-2 badge badge-success">Successfully made a new user</div>
                                        <?php } ?>
                                        <div class="position-relative row form-check">
                                            <div class="col-sm-10 offset-sm-2">
                                                <button name="register" class="btn btn-secondary">Register</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </body>
<?php } ?>

</html>