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

    public function selectAction($table, $sortCol = "id", $sortDir = "ASC", $filter = "1")
    {
        try {
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT * FROM `$table` WHERE $filter ORDER BY $sortCol $sortDir";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            return $result;
        } catch (PDOException $e) {
            return "Failed: " . $e->getMessage();
        }
    }

    public function selectByColAction($table, $col, $filter = 1)
    {
        try {
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT $col FROM `$table` WHERE $filter";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            return $result;
        } catch (PDOException $e) {
            return "Failed: " . $e->getMessage();
        }
    }

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
     * Function to get a line from a table
     *
     * @param string  $table table name
     * @param string  $col column name
     * @param string  $key key to look for
     * @return array
     */
    public function findUniqueAction($table, $col, $key)
    {
        try {
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT * FROM `$table` WHERE $col = '$key'";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            if (empty($result)) {
                return $result;
            }
            return $result[0];
        } catch (PDOException $e) {
            return "Failed: " . $e->getMessage();
        }



        //returns array of "col name" => "col value"


    }
}
