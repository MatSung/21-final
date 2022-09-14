<?php
class DatabaseConnection
{
    private $dbhost = "localhost";
    private $dbuser = "root";
    private $dbpassword = "";

    protected $conn;

    public function __construct($database = "imoniu_valdymas")
    {
        try {

            $this->conn = new PDO("mysql:host=$this->dbhost;dbname=$database", $this->dbuser, $this->dbpassword);
            // not fail
        } catch (PDOException $e) {
            // fail
        }
    }

    public function __destruct()
    {
        $this->conn = null;
        // echo "disconnected";
    }

    /**
     * 
     * Function to select
     *
     * @param string  $table table name
     * @param string  $sortCol id column by which to sort, sorts by "id" by default
     * @param string  $sortDir sort direction, "ASC" by default, can be "DESC"
     * @param string  $filter introduce a custom filter, 1 by default
     */
    public function selectAction($table, $sortCol = "id", $sortDir = "ASC", $filter = "1")
    {
        try {
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT * FROM `$table` WHERE $filter ORDER BY $sortCol $sortDir";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            if (empty($result)) {
                return 0;
            }
            return $result;
        } catch (PDOException $e) {
            return "Failed: " . $e->getMessage();
        }
    }

    /**
     * 
     * Function to select only a single column from a table
     *
     * @param string  $table table name
     * @param string  $col column name to select
     * @param string  $filter introduce a custom filter, 1 by default
     */
    public function selectByColAction($table, $col, $filter = 1)
    {
        try {
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT $col FROM `$table` WHERE $filter";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_COLUMN, 0);
            $result = $stmt->fetchAll();
            if (empty($result)) {
                return 0;
            }
            return $result;
        } catch (PDOException $e) {
            return "Failed: " . $e->getMessage();
        }
    }

    public function selectIDValuePairAction($table, $col, $filter = 1){
        try {
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT id,$col FROM `$table` WHERE $filter";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            if (empty($result)) {
                return 0;
            }
            return $result;
        } catch (PDOException $e) {
            return "Failed: " . $e->getMessage();
        }
    }


    //$cols = ["products.id", "products.title", "products.description", "products.price", "categories.title as Category", products.image_url];
    /**
     * 
     * Function to select with join
     *
     * @param string  $table1 first table
     * @param string  $table2 second table
     * @param string  $table1RelationCol foreign key, the key to get the unique one
     * @param string  $table2RelationCol main key, the unique one
     * @param string  $join LEFT or RIGHT or INNER or that other one, will append JOIN
     * @param array  $cols all the columns that we want like ["table1.value", "table2.category_id as category", ...]
     * @param string  $sortCol column to sort by
     * @param string  $sortDir direction to sort ASC or DESC
     * @param string  $filter any filter
     */
    public function selectJoinAction($table1, $table2, $table1RelationCol, $table2RelationCol, $join, $cols, $sortCol = "id", $sortDir = "ASC", $filter = "1"){
        $cols = implode(",", $cols);

        try{
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql= "SELECT $cols FROM $table1 
            $join JOIN $table2
            ON $table1.$table1RelationCol = $table2.$table2RelationCol
            WHERE $filter
            ORDER BY $sortCol $sortDir";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            
            return $result; 
        } catch(PDOException $e) {
            return "Failed " . $e->getMessage();
        }
    }

    /**
     * 
     * Function to select with join multiple
     *
     * @param string  $table1 first table
     * @param array  $tables all of the foreign tables
     * @param array  $tableRelationCols foreign keys [["key1","key2"],["key1","key2"]], the key to get the unique one
     * @param string  $join LEFT or RIGHT or INNER or that other one, will append JOIN
     * @param array  $cols all the columns that we want like ["table1.value", "table2.category_id as category", ...]
     * @param string  $sortCol column to sort by
     * @param string  $sortDir direction to sort ASC or DESC
     * @param string  $filter any filter
     */
    public function selectJoinMultipleAction($table1, $tables, $tableRelationCols, $join, $cols, $sortCol = "id", $sortDir = "ASC", $filter = "1"){
        $cols = implode(",", $cols);

        try{
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql= "SELECT $cols FROM $table1 ";
            for($i = 0; $i < count($tables); $i++){
                $sql .= " $join JOIN $tables[$i] 
                        ON ".$table1.".".$tableRelationCols[$i][0]." = ".$tables[$i].".".$tableRelationCols[$i][1];
            }
            $sql.= " WHERE $filter ORDER BY $sortCol $sortDir";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            
            return $result; 
        } catch(PDOException $e) {
            return "Failed " . $e->getMessage();
        }
    }

    /**
     * 
     * Function to insert a new value into a table
     *
     * @param string  $table table name
     * @param array  $cols column names in an array E.G ["name","desc",...]
     * @param array  $values values to put into columns, amount must match cols amount, E.G ["name", "desc", ...]
     */
    public function insertAction($table, $cols, $values){
        //paima table, column, value ir ideda i sql
        $cols = implode(",", $cols);
        $values = implode(",", $values);

        try{
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "INSERT INTO `$table` ($cols) VALUES ($values)";
        $sql = "INSERT INTO `$table` ($cols) VALUES ($values)";

        $this->conn->exec($sql);
        return 1;
        } catch (PDOException $e) {
            echo "failed: " . $e->getMessage(); 
            //failed
            return 0;
        }
        
    }

    /**
     * 
     * Function to delete a single entry by their id
     *
     * @param string  $table table name
     * @param int  $id id which to delete
     */
    public function deleteByIDAction($table, $id)
    {
        try {
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "DELETE FROM `$table` WHERE id = $id";
            $this->conn->exec($sql);
        } catch (PDOException $e) {
            echo "Failed: " . $e->getMessage();
        }
    }


    public function updateAction($table, $id, $data)
    {
        $cols = array_keys($data);
        //var_dump($cols);
        $values = array_values($data);
        //var_dump($values);

        $dataString = [];
        for ($i = 0; $i < count($cols); $i++) {
            $dataString[] = $cols[$i] . " = '" . $values[$i] . "'";
        }
        $dataString = implode(",", $dataString);
        //var_dump($dataString);


        try {
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE `$table` SET $dataString WHERE id = $id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            //echo "Pavyko atnaujinti irasa";
        } catch (PDOException $e) {
            echo "Failed: " . $e->getMessage();
        }
    }

    /**
     * 
     * Function to update a single value in a column by their id
     *
     * @param string  $table table name
     * @param string  $col col name which to replace
     * @param int  $id by which id to set
     * @param string $value value by which to replace
     */
    public function updateSingleAction($table, $col, $id, $value)
    {
        try {
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE `$table` SET $col = '$value' WHERE id = $id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Failed: " . $e->getMessage();
        }
    }

    /**
     * 
     * Function to get a line from a table, is a replacement for select one
     *
     * @param string  $table table name
     * @param string  $col column name
     * @param string  $key key to look for
     * @return array
     */
    public function selectUniqueAction($table, $col, $key)
    {
        try {
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT * FROM `$table` WHERE $col = '$key'";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            if ($result == []) {
                return NULL;
            }
            return $result[0];
        } catch (PDOException $e) {
            return "Failed: " . $e->getMessage();
        }



        //returns array of "col name" => "col value"


    }
}
