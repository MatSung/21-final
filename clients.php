<?php include("classes/databaseObject_class.php"); ?>
<?php include("classes/sessionManager_class.php"); ?>
<?php include("classes/settings_class.php"); ?>


<?php
$session = new Session();
$settings = new Settings();
$session->checkSession();
//privilege level

$database = new databaseObject("klientai");
?>

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./main.css" rel="stylesheet">
    <link href="styles,css" rel="stylesheet">
    <title>Clients</title>
</head>


<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <div class="app-header header-shadow">
            <div class="app-header__content">
                <div class="app-header-left">
                    <!-- left header -->
                    <ul class="header-menu nav">
                        <li class="nav-item">
                            <a href="dashboard.php" class="nav-link">
                                Dashboard
                            </a>
                        </li>
                        <?php if($session->session["privilegeLevel"] == 3) {
                             ?>
                        <li class="nav-item">
                            <a href="history.php" class="nav-link">
                                History
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="app-header-right">
                    <!-- right header -->
                    <div class="header-custom">
                        <p style="font-style: italic;text-align: center; margin-bottom: 0; padding-right: 10px;">
                            <?php
                            echo $session->session["name"];
                            echo " ";
                            echo $session->session["surname"];
                            ?>
                        </p>
                        <p style="text-align: center; margin-bottom: 0; padding-right: 10px;">
                            logged in as:
                            <?php
                            echo $session->session["username"];
                            ?>
                        </p>
                    </div>
                    <div class="header-btn-lg">
                        <form method="POST" action=".\index.php">
                            <button name="logout" class="btn btn-danger">Log out</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
        <div class="app-main">
            <div class="app-main__outer">
                <div class="app-main__inner">
                    <div class="app-page-title">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                History of logins
                            </div>
                            <div class="page-title-actions">
                                Actions of the page
                            </div>
                        </div>
                    </div>
                    <div class="row">
        <div class="col-md-12">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    Clients
                    
                </div>
                <?php
                //var_dump($database->container);
                //check privilege
                $database->drawTable(1);
                ?>
                
                <?php

                ?>
            </div>
        </div>
    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

