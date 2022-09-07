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


        if($table1 == "history"){
            $table2 = "vartotojai";
            $table1RelationCol = "user_id";
            $table2RelationCol = "id";
            $join = "LEFT";
            $cols = ["history.id", 
                    "vartotojai.slapyvardis as username", 
                    "history.datetime"];
            $this->container = $this->selectJoinAction($table1, $table2, $table1RelationCol, $table2RelationCol, $join, $cols);
            return 1;
        }


        return 0;
    }

    public function draw(){


        if($this->type == "history"){
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
     * @param bool  $allowEdit will append actions as last key
     * @return array will return array of keys
     */
    public function getKeys($allowEdit = 1){
        $keys = array_keys($this->container[0]);
        if($allowEdit){
            array_push($keys,"Actions");
        }
        return $keys;
    }

    public function drawTable(){


        $keys = array_keys($this->container[0]);
        echo "<div class='table-responsive'>";
        echo "<table id='".$this->type."-table' class='align-middle mb-0 table table-borderless table-striped table-hover'>";
        
        echo "<thead>";
        foreach($keys as $key){
            echo "<th>";
            echo $key;
            echo "</th>";
        }
        echo "</thead>";

        echo "<tbody>";
        foreach($this->container as $item){
            echo "<tr>";
            foreach($keys as $key){
                echo "<td>";
                echo $item[$key];
                echo "</td>";
            }
            echo "</tr>";
        }
        echo "</tbody>";

        echo "</table>";
        echo "</div>";
    }

    public function drawTableHeader(){
        $keys = array_keys($this->container[0]);
        echo "<div class='table-responsive'>";
        echo "<table id='".$this->type."-table' class='align-middle mb-0 table table-borderless table-striped table-hover'>";
        
        echo "<thead>";
        foreach($keys as $key){
            echo "<th>";
            echo $key;
            echo "</th>";
        }
        echo "<th>";
        echo "Actions";
        echo "</th>";
        echo "</thead>";

        echo "<tbody>";
        echo "</tbody>";

        echo "</table>";
        echo "</div>";
    }



}