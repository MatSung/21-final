<?php include("classes/databaseObject_class.php"); ?>
<?php include("classes/sessionManager_class.php"); ?>
<?php include("classes/settings_class.php"); ?>


<?php
$session = new Session();
$settings = new Settings();
$session->checkSession();
//privilege level

$database = new databaseObject("history");

//get cookie for page size
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
        "select" => 0
    ]
);



if (isset($_COOKIE["userSettings"])) {
    $userSettings = json_decode($_COOKIE["userSettings"], true);
}

if (isset($_POST["selectPageSizer"])) {
    $userSettings["pageSize"]["select"] = $_POST["selectPageSizer"];
}

setcookie("userSettings", json_encode($userSettings), time() + 60 * 60, "/");

?>


<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./main.css" rel="stylesheet">
    <link href="styles,css" rel="stylesheet">
    <title>pagination test</title>
</head>

<?php 
$tableType = "history";
include("scripts/pagination_script.php"); 
?>

<body>


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


</body>

</html>