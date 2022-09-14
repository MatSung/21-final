<?php
class Session extends DatabaseConnection
{
    public $sessionCookieName = "session";
    public $session = array(
        "active" => false,
        "usernameID" => 0,
        "username" => "",
        "name" => "",
        "surname" => "",
        "privilegeLevel" => 0
    );

    
    // if session exists, use existing session 
    // search for username through table
    // when username found, check if password matches the username
    // register date that it was logged in on and add a login database
    // make cookie to have a session with username ID, username, name, surname, rights.
    // logout makes cookie disappear, restate cookie every time you use the website and cookie is valid for 20min\
    public function __construct()
    {
        //get database connection for password check
        parent::__construct();

        //if cookie is unset do nothing session is unset by default
        if (isset($_COOKIE["session"])) {
            $sessionCookie = json_decode($_COOKIE["session"], true);
            if ($sessionCookie["active"] == true) {
                $this->session["active"] = true;
                $this->session["usernameID"] = $sessionCookie["usernameID"];
                $this->session["username"] = $sessionCookie["username"];
                $this->session["name"] = $sessionCookie["name"];
                $this->session["surname"] = $sessionCookie["surname"];
                $this->session["privilegeLevel"] = $sessionCookie["privilegeLevel"];
            }
        }

        

    }

    /**
     * 
     * Function to check session and reroute user back if the session is inactive
     *
     */
    public function checkSession(){
        if(!$this->session["active"]){
            header("Location: index.php");
            return 1;
        }
        

    }

    /**
     * 
     * Function to login
     *
     * @param string  $username username
     * @param string  $password password
     * @return bool success = 1, fail = 0
     */
    public function login($username, $password)
    {

        
        //check if already logged in
        if($this->session["active"]){
            echo "session already active";
            return 0;
        }

        $userDetails = $this->selectUniqueAction("vartotojai", "slapyvardis", $username);
        //var_dump($userDetails);

        //check if details are correct
        //if user does not exist or password is wrong
        if(empty($userDetails) || $userDetails["slaptazodis"] != $password){
            return 0;
        }
        //now log in
        //echo "login success as ".$userDetails["slapyvardis"];


        //fill session details
        $this->session = array(
            "active" => true,
            "usernameID" => $userDetails["id"],
            "username" => $userDetails["slapyvardis"],
            "name" => $userDetails["vardas"],
            "surname" => $userDetails["pavarde"],
            "privilegeLevel" => $userDetails["teises_id"]
        );
        //will have to check later for what level of access is granted
        //probably easier to have array of editing powers "edit" => true
        //probably easier to have a page to set these powers and a database with powers

        //var_dump($this->session);

        //fill cookie to work for an hour
        setcookie($this->sessionCookieName,json_encode($this->session),time() + 60*60, "/");

        //update databse with last connected
        $this->updateSingleAction("vartotojai","paskutinis_prisijungimas", $userDetails["id"], date("Y-m-d"));

        //fill history of connections
        $this->insertAction("history",["user_id","datetime"],["'".$userDetails["id"]."'","'".date("Y-m-d H:i:s")."'"]);

        return 1;

    }

    /**
     * 
     * Function to logout and reload the page
     *
     */
    public function logout()
    {
        setcookie($this->sessionCookieName, "", time() - 1000, '/');
        header("Location: index.php");
    }

    /**
     * 
     * Function to register and reload the page
     *
     * @param string  $username username
     * @param string  $password password
     * @return bool success = 1, fail = 0
     */
    public function register($username, $password, $name = "''", $surname = "''")
    {
        
        //if user already exists return 0
        //var_dump($this->selectUniqueAction("vartotojai", "slapyvardis", $username));
        if(!is_null($this->selectUniqueAction("vartotojai", "slapyvardis", $username))){
            //var_dump($this->selectUniqueAction("vartotojai", "slapyvardis", $username));
            return 0;
        }
        //make the user

        $cols = ["vardas","pavarde", "slapyvardis", "teises_id", "slaptazodis", "registracijos_data", "paskutinis_prisijungimas"];
        $values = ["'".$name."'", "'".$surname."'", "'".$username."'", 3, "'".$password."'", "'".date("Y-m-d")."'", "''"];
        if($this->insertAction("vartotojai",$cols,$values)){
            return 1;
        }
        echo "failed to register due to sql";
        return 0;
        
        //header("Location: index.php");
    }
}
