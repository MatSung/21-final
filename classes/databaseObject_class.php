<?php
include("classes/databaseConnection_class.php");


/**
 * 
 * Function to get a table
 *
 * @param string  $table1 generic database request
 */
class databaseObject extends DatabaseConnection
{
    public $container;
    public $type;



    public function __construct($table1, $settings = [])
    {
        parent::__construct();
        $this->type = $table1;


        if ($table1 == "history") {
            $table2 = "vartotojai";
            $table1RelationCol = "user_id";
            $table2RelationCol = "id";
            $join = "LEFT";
            $cols = [
                "history.id",
                "vartotojai.slapyvardis as username",
                "history.datetime"
            ];
            $this->container = $this->selectJoinAction($table1, $table2, $table1RelationCol, $table2RelationCol, $join, $cols);
            return 1;
        }
        if ($table1 == "imones_tipas" || $table1 == "klientai_teises") {
            $this->container = $this->selectAction($table1);
            return 1;
        }
        if($table1 == "klientai"){
            $tables = ["klientai_teises","imones"];
            $tableRelationCols = [["teises_id","id"],["imones_id","id"]];
            $join = "LEFT";
            $cols = ["klientai.id","klientai.vardas", "klientai.pavarde", "klientai_teises.pavadinimas as teises", "klientai.aprasymas", "imones.pavadinimas as imone", "klientai.pridejimo_data"];
            $this->container = $this->selectJoinMultipleAction($table1, $tables, $tableRelationCols,$join,$cols);
        }
        if($table1 == "imones"){
            $tables = ["imones_tipas"];
            $tableRelationCols = [["tipas_id","id"]];
            $join = "LEFT";
            $cols = ["imones.id","imones.pavadinimas","imones_tipas.pavadinimas as imones_tipas","imones.aprasymas"];
            $this->container = $this->selectJoinMultipleAction($table1, $tables, $tableRelationCols,$join,$cols);
        }
        if($table1 == "vartotojai"){
            $tables = ["vartotojai_teises"];
            $tableRelationCols = [["teises_id","id"]];
            $join = "LEFT";
            $cols = ["vartotojai.id","vartotojai.vardas","vartotojai.pavarde","vartotojai_teises.pavadinimas as teises", "vartotojai.slaptazodis","vartotojai.registracijos_data","vartotojai.paskutinis_prisijungimas"];
            $this->container = $this->selectJoinMultipleAction($table1, $tables, $tableRelationCols,$join,$cols);

        }
        if($table1 == "vartotojai_teises"){
            $this->container = $this->selectAction($table1);
        }
        


        return 0;
    }

    public function insertEntry(){
        if($this->type == "imones_tipas"|| $this->type == "vartotojai_teises"){
            $this->insertAction($this->type,["pavadinimas","aprasymas"],["'".$_POST["pavadinimas"]."'","'".$_POST["aprasymas"]."'"]);
        } else if ($this->type == "klientai_teises" ){
            $this->insertAction($this->type,["pavadinimas","reiksme"],["'".$_POST["pavadinimas"]."'","'".$_POST["reiksme"]."'"]);
        } else if ($this->type == "klientai"){
            $this->insertAction($this->type,["vardas","pavarde","teises_id","aprasymas","imones_id","pridejimo_data"],["'".$_POST["vardas"]."'","'".$_POST["pavarde"]."'",$_POST["teises"],"'".$_POST["aprasymas"]."'",$_POST["imone"],"'".$_POST["pridejimo_data"]."'"]);
        } else if ($this->type == "vartotojai"){
            $this->insertAction($this->type, ["vardas","pavarde","teises_id","slaptazodis","registracijos_data","paskutinis_prisijungimas"],["'".$_POST["vardas"]."'","'".$_POST["pavarde"]."'",$_POST["teises"],"'".$_POST["slaptazodis"]."'",date("Y-m-d"),"'".$_POST["paskutinis_prisijungimas"]."'"]);
        }
        //foreach insert thing in the POST
    }

    public function updateEntry(){
        if($this->type == "imones_tipas"){
            $data = array(
                "pavadinimas" => $_POST["pavadinimas"],
                "aprasymas" => $_POST["aprasymas"]
            );
            $this->updateAction($this->type,$_POST["id"],$data);
        } else if ($this->type == "klientai_teises"){
            $data = array(
                "pavadinimas" => $_POST["pavadinimas"],
                "aprasymas" => $_POST["reiksme"]
            );
            $this->updateAction($this->type,$_POST["id"],$data);
        } else if ($this->type == "klientai"){
            $data = array(
                "vardas" => "'".$_POST["vardas"]."'",
                "pavarde" => "'".$_POST["pavarde"]."'",
                "teises_id" => $_POST["teises"],
                "aprasymas" => "'".$_POST["aprasymas"]."'",
                "imones_id" => $_POST["imone"],
                "pridejimo_data" => "'".$_POST["pridejimo_data"]."'"
            );
            $this->updateAction($this->type,$_POST["id"],$data);
        } else if ($this->type == "imones"){
            $data = array(
                "pavadinimas" => "'".$_POST["pavadinimas"]."'",
                "tipas_id" => $_POST["tipas"],
                "aprasymas" => "'".$_POST["aprasymas"]."'",
                );
            $this->updateAction($this->type,$_POST["id"],$data);
        }
    }

    public function deleteEntry(){
        $this->deleteByIDAction($this->type,$_POST["id"]);
    }

    public function draw()
    {


        if ($this->type == "history") {
            foreach ($this->container as $item) {
                echo "<tr>";
                echo "<td class='text-center'>";
                echo $item["id"];
                echo "</td>";
                echo "<td>";
                echo $item["username"];
                echo "</td>";
                echo "<td>";
                echo $item["datetime"];
                echo "</td>";
                echo "</tr>";
            }
        }
    }

    /**
     * 
     * Function to get a table
     *
     * @param bool  $allowEdit will push an "Actions" value as last key
     * @return array will return array of keys
     */
    public function getKeys($allowEdit = 1)
    {
        $keys = array_keys($this->container[0]);
        if ($allowEdit) {
            array_push($keys, "Actions");
        }
        return $keys;
    }

    public function drawTable($editRights = 0)
    {


        $keys = array_keys($this->container[0]);
        echo "<div class='table-responsive'>";
        echo "<table id='" . $this->type . "-table' class='align-middle mb-0 table table-borderless table-striped table-hover'>";

        echo "<thead>";
        foreach ($keys as $key) {
            echo "<th>";
            echo $key;
            echo "</th>";
        }
        if($editRights){
            echo "<th>";
            echo "Actions";
            echo "</th>";
        }
        echo "</thead>";

        echo "<tbody>";
        foreach ($this->container as $item) {
            echo "<tr>";
            foreach ($keys as $key) {
                echo "<td>";
                echo $item[$key];
                echo "</td>";
            }
            if($editRights){
                echo "<td>";
                echo "<form method='POST'>";
                echo "<input hidden name='id' type='text' value='".$item["id"]."'>";
                echo "<button class='btn btn-danger' type='submit' name='".$this->type."DeleteEntry'>X</button>";
                //button to actually edit
                echo "</form>";
                // echo "<button class='btn btn-info' name='".$this->type."UpdateEntry' id='".$this->type."' onclick='console.log(\"kebabas\");'   >EDIT</button>";
                echo "<button class='btn btn-info' name='".$this->type."UpdateEntry' id='".$this->type.$item["id"]."' onclick='editEntry(\"".$this->type."\",this);'>EDIT</button>";
                echo "</td>";
            }
            echo "</tr>";
        }
        //echo to create
        if ($editRights) {
            echo "<tr>";
            echo "<form method='POST'>";
            echo "<input hidden value='".$this->type."' type='text'>";
            foreach ($keys as $key) {
                echo "<td>";
                if($key == "id"){
                    echo "<button class='btn btn-primary' name='".$this->type."InsertEntry' type='submit' class=class='mb-2 mr-2 btn btn-primary'>+</button>";
                } else {
                    echo "<input required type='text' class='form-control-sm form-control' name='".$key."' placeholder='".$key."'>";
                }
                echo "</td>";
            }
            echo "</form>";
            echo "</tr>";

        }
        echo "</tbody>";

        echo "</table>";
        echo "</div>";
    }

    public function drawTableHeader()
    {
        $keys = array_keys($this->container[0]);
        echo "<div class='table-responsive'>";
        echo "<table id='" . $this->type . "-table' class='align-middle mb-0 table table-borderless table-striped table-hover'>";

        echo "<thead>";
        foreach ($keys as $key) {
            echo "<th>";
            echo $key;
            echo "</th>";
        }
        //echo "<th>";
        //echo "Actions";
        //echo "</th>";
        echo "</thead>";

        echo "<tbody>";
        echo "</tbody>";

        echo "</table>";
        echo "</div>";
    }

    public function drawCreateLine(){
        $keys = array_keys($this->container[0]);

    }
}
