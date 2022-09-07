<?php
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
<script>
    document.addEventListener('DOMContentLoaded', init, false);


    <?php
    // $array = [
    //     array(
    //         "id" => 1,
    //         "name" => "kebab",
    //         "datetime" => "2022"
    //     ),
    //     array(
    //         "id" => 2,
    //         "name" => "kebab2",
    //         "datetime" => "20222"
    //     ),
    //     array(
    //         "id" => 3,
    //         "name" => "kebab3",
    //         "datetime" => "20223"
    //     ),
    //     array(
    //         "id" => 4,
    //         "name" => "kebab3",
    //         "datetime" => "20223"
    //     ),
    //     array(
    //         "id" => 5,
    //         "name" => "kebab3",
    //         "datetime" => "20223"
    //     ),
    //     array(
    //         "id" => 6,
    //         "name" => "kebab3",
    //         "datetime" => "20223"
    //     ),
    //     array(
    //         "id" => 7,
    //         "name" => "kebab3",
    //         "datetime" => "20223"
    //     ),
    //     array(
    //         "id" => 8,
    //         "name" => "kebab3",
    //         "datetime" => "20223"
    //     )
    // ];

    ?>

    <?php
    $array = $database->container;
    echo "var array = " . json_encode($array) . ";\n";
    ?>
    let curPage = 1;
    let pageSize = <?php echo $userSettings["pageSize"]["select"] ?>;
    let itemAmount = array.length;
    let pageAmount = Math.ceil(itemAmount / pageSize);
    if (pageSize == 0) {
        pageSize = itemAmount;
    }
    let pageList, table;
    let tableType = <?php echo "'".$tableType."'"; ?>;

    //make table select into a variable
    // make it display buttons too
    // test it first with history
    // then test it with settings
    // try putting 


    async function init() {


        // Select the table (well, tbody)
        table = document.querySelector('#' + tableType + '-table tbody');
        // get the array
        pageList = document.querySelector('#selectPagination');



        //create html
        renderTable();
    }

    function nextPage() {
        if (curPage < pageAmount) curPage++;
        if (curPage == pageAmount) document.querySelector("#nextPageButton").setAttribute("disabled", "");
        if (curPage == 2) document.querySelector("#previousPageButton").removeAttribute("disabled")
        renderTable();
    }

    function previousPage() {
        if (curPage > 1) curPage--;
        if (curPage == pageAmount - 1) document.querySelector("#nextPageButton").removeAttribute("disabled");
        if (curPage == 1) document.querySelector("#previousPageButton").setAttribute("disabled", "")
        renderTable();
    }

    function renderTable() {
        let result = '';
        array.filter((row, index) => {
            let start = (curPage - 1) * pageSize;
            let end = curPage * pageSize;
            if (index >= start && index < end) return true;
        }).forEach(c => {
            result += `<tr>
     <td>${c.id}</td>
     <td>${c.username}</td>
     <td>${c.datetime}</td>
     </tr>`;
     //it's over here that i need to make this dynamic
        });
        table.innerHTML = result;
    }
</script>