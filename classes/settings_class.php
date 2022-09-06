<?php
class Settings extends DatabaseConnection{
    public $settings = [];

    public function __construct()
    {
        parent::__construct();
        //get settings from the database
        $settings = $this->selectAction('settings');
        foreach($settings as $setting){
            $this->settings[$setting["name"]] = $setting["value"];
        }
        //settings are set in $this->settings["name of the setting"] = "value of the setting"
        //setting names must be unique
        //var_dump($this->settings);

    }
}

?>