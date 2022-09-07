<?php include("classes/databaseObject_class.php"); ?>
<?php include("classes/sessionManager_class.php"); ?>
<?php include("classes/settings_class.php"); ?>


<?php
$session = new Session();
$settings = new Settings();
$session->checkSession();
//privilege level

$database = new databaseObject("history");

if (isset($_COOKIE["userSettings"])) {
    $userSettings = json_decode($_COOKIE["userSettings"], true);
}

if (isset($_POST["selectPageSizer"])) {
    $userSettings["pageSize"]["select"] = $_POST["selectPageSizer"];
}

setcookie("userSettings", json_encode($userSettings), time() + 60 * 60, "/");

?>
?>
<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./main.css" rel="stylesheet">
    <link href="styles,css" rel="stylesheet">
    <title>history</title>
</head>
<?php 
$tableType = "history";
include("scripts/pagination_script.php"); 
?>
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
                    History
                    <div class="btn-actions-pane-right">
                        <div class="btn-group-sm btn-group">
                            <form method="POST">
                                <select name="selectPageSizer" id="selectPageSizer" class="form-control-sm form-control" type="select" onchange="this.form.submit()">
                                    <option <?php if ($userSettings["pageSize"]["select"] == 20) {
                                                echo "selected";
                                            } ?> value="20">20</option>
                                    <option <?php if ($userSettings["pageSize"]["select"] == 5) {
                                                echo "selected";
                                            } ?> value="5">5</option>
                                    <option <?php if ($userSettings["pageSize"]["select"] == 0) {
                                                echo "selected";
                                            } ?> value="0">Show All</option>
                                </select>
                            </form>
                            <?php if (!$userSettings["pageSize"]["select"] == 0 && count($database->container) > $userSettings["pageSize"]["select"]) { ?>
                                <ul id="selectPagination" class="pagination" style="margin-bottom: 0;">
                                    <li id="previousPageButton" class="page-item"><a href="javascript:previousPage();" class="page-link" aria-label="Previous"><span aria-hidden="true">«</span><span class="sr-only">Previous</span></a></li>
                                    <!-- <li class="page-item active"><a href="javascript:void(0);" class="page-link">1</a></li>
                                <li class="page-item"><a href="javascript:void(0);" class="page-link">2</a></li>
                                <li class="page-item"><a href="javascript:void(0);" class="page-link">3</a></li>
                                <li class="page-item"><a href="javascript:void(0);" class="page-link">4</a></li>
                                <li class="page-item"><a href="javascript:void(0);" class="page-link">5</a></li> -->
                                    <li id="nextPageButton" class="page-item"><a href="javascript:nextPage();" class="page-link" aria-label="Next"><span aria-hidden="true">»</span><span class="sr-only">Next</span></a></li>
                                </ul>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php
                // this is where we get the history
                // paginate
                $database->drawTableHeader();
                ?>
                <!-- <div class="table-responsive">
                    <table id="selectTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Datetime</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3"><i>Loading...</i></td>
                            </tr>
                        </tbody>
                    </table>
                </div> -->

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