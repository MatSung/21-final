<?php
include("classes/databaseConnection_class.php");

class databaseObject extends DatabaseConnection
{
    public $container;



    public function __construct($table1, $settings = [])
    {
        parent::__construct();

    }

}