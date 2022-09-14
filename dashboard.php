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
if (
    isset($_POST["klientai_teisesInsertEntry"]) || isset($_POST["imones_tipasInsertEntry"])
    || isset($_POST["imones_tipasDeleteEntry"]) || isset($_POST["klientai_teisesDeleteEntry"])
    || isset($_POST["imones_tipasUpdateEntry"]) || isset($_POST["klientai_teisesUpdateEntry"])
) {
    header("Location: dashboard.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./main.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <title>Dashboard</title>

</head>
<?php include("scripts/editEntry_script.php"); ?>

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
                        <?php if ($session->session["privilegeLevel"] == 3) {
                        ?>
                            <li class="nav-item">
                                <a href="history.php" class="nav-link">
                                    History
                                </a>
                            </li>
                        <?php }
                        if (in_array($session->session["privilegeLevel"], [1], true)) {
                        ?>
                            <li class="nav-item">
                                <a href="users.php" class="nav-link">
                                    Users
                                </a>
                            </li>
                        <?php 
                        } 
                        if ($session->session["privilegeLevel"] != 3){?>
                        <li class="nav-item">
                            <a href="clients.php" class="nav-link">
                                Clients
                            </a>
                        </li>
                        <?php
                        } 
                        if ($session->session["privilegeLevel"] != 3) {
                            ?>
                        <li class="nav-item">
                            <a href="companies.php" class="nav-link">
                                Companies
                            </a>
                        </li>
                        <?php
                        }
                        ?>
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
                    <?php
                    if ($session->session["privilegeLevel"] == 1) {
                    ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-header">
                                        Settings
                                    </div>
                                    <?php
                                    if (isset($_POST["toggleSetting"])) {
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
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="main-card mb-3 card">
                                <div class="card-header">
                                    Įmonių tipai
                                </div>
                                <?php
                                $companyTypes = new databaseObject("imones_tipas");
                                if (isset($_POST["imones_tipasInsertEntry"])) {
                                    $companyTypes->insertEntry();
                                }
                                if (isset($_POST["imones_tipasDeleteEntry"])) {
                                    $companyTypes->deleteEntry();
                                }
                                if (isset($_POST["imones_tipasUpdateEntry"])) {
                                    $companyTypes->updateEntry();
                                }
                                //make edit button to display inputs maybe with javascript?

                                $companyTypes->drawTable((($session->session["privilegeLevel"] == 3) ? 0 : 1));

                                ?>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="main-card mb-3 card">
                                <div class="card-header">
                                    Klientu teises
                                </div>
                                <?php
                                $clientTypes = new databaseObject("klientai_teises");
                                if (isset($_POST["klientai_teisesInsertEntry"])) {
                                    $clientTypes->insertEntry();
                                }
                                if (isset($_POST["klientai_teisesDeleteEntry"])) {
                                    $clientTypes->deleteEntry();
                                }
                                if (isset($_POST["klientai_teisesUpdateEntry"])) {
                                    $Types->updateEntry();
                                }
                                $clientTypes->drawTable($session->session["privilegeLevel"] == 3 ? 0 : 1);
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php
                    if ($session->session["privilegeLevel"] == 3) {
                        $companies = new databaseObject("imones");
                    ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-header">
                                        Companies
                                    </div>
                                    <?php
                                    $companies->drawTable(0);
                                    ?>

                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                    <?php
                    //clients only display
                    if ($session->session["privilegeLevel"] == 3) {
                        $clients = new databaseObject("klientai");
                    ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-header">
                                        Clients
                                    </div>
                                    <?php
                                    $clients->drawTable(0);
                                    ?>

                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>