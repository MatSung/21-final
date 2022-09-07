<?php
//top login line with person who is logged in
//settings page
//maybe just fill the whole page with crud tables and only show them if you have rights
// or split them into more?
?>
<?php include("classes/databaseObject_class.php"); ?>
<?php include("classes/sessionManager_class.php"); ?>
<?php include("classes/settings_class.php"); ?>


<?php
$session = new Session();
$settings = new Settings();
$session->checkSession();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./main.css" rel="stylesheet">
    <link href="styles,css" rel="stylesheet">
    <title>Dashboard</title>
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
                                Heading of the page
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
                                    Settings
                                </div>
                                <?php
                                    if(isset($_POST["toggleSetting"])){
                                        $settings->toggle($_POST["settingID"]);
                                        header("Location: dashboard.php");
                                    }
                                ?>
                                <div class="table-responsive">
                                    <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    ID
                                                </th>
                                                <th>
                                                    Name
                                                </th>
                                                <th>
                                                    Value
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                // this is where we get the settings
                                                $settings->draw();
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="main-card mb-3 card">
                                <div class="card-header">
                                    Įmonių tipai
                                </div>
                                <?php
                                    if(isset($_POST["toggleSetting"])){
                                        $settings->toggle($_POST["settingID"]);
                                        header("Location: dashboard.php");
                                    }
                                ?>
                                <div class="table-responsive">
                                    <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    ID
                                                </th>
                                                <th>
                                                    Name
                                                </th>
                                                <th>
                                                    Description
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                // this is where we get the settings
                                                $settings->draw();
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="main-card mb-3 card">
                                <div class="card-header">
                                    Settings
                                </div>
                                <?php
                                    if(isset($_POST["toggleSetting"])){
                                        $settings->toggle($_POST["settingID"]);
                                        header("Location: dashboard.php");
                                    }
                                ?>
                                <div class="table-responsive">
                                    <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    ID
                                                </th>
                                                <th>
                                                    Name
                                                </th>
                                                <th>
                                                    Value
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                // this is where we get the settings
                                                $settings->draw();
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>