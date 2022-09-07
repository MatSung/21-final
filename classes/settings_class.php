<?php
class Settings extends DatabaseConnection
{
    public $settings = [];
    public $settingsFull;

    public function __construct()
    {
        parent::__construct();
        //get settings from the database
        $this->settingsFull = $this->selectAction('settings');
        foreach ($this->settingsFull as $setting) {
            $this->settings[$setting["name"]] = $setting["value"];
        }
        //settings are set in $this->settings["name of the setting"] = "value of the setting"
        //setting names must be unique
        //var_dump($this->settings);

    }

    public function draw()
    {
        foreach ($this->settingsFull as $item) {
            echo "<tr>";
            echo "<td class='text-center'>";
            echo $item["id"];
            echo "</td>";
            echo "<td>";
            echo $item["name"];
            echo "</td>";
            echo "<td>";
            //toggle button
            echo "<form method='POST'>";
            echo "<input type='hidden' name='settingID' value='" . $item["id"] . "'>";
            echo "<button style='display:inline;' name='toggleSetting' class='mb-2 mr-2 btn btn-secondary'>".$item["value"]."</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
    }


    /**
     * 
     * Function to toggle a setting
     *
     * @param int  $id id to toggle
     */
    public function toggle($id){
        $row = $this->selectUniqueAction("settings","id",$id);
        $valueToWrite = ($row["value"] == 0) ? 1 : 0;
        $this->updateSingleAction("settings","value",$id,$valueToWrite);

    }
}
