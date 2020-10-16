<?php
//Set Up Class AutoLoading
spl_autoload_register(function ($class) {
    include_once dirname(__FILE__) . '/includes/' . $class . '.Class.php';
});

//Include Database Credentials
include_once dirname(__FILE__) . 'DB.Credentials.php';

//Define Category Class
Class Category {

    //Declare Constructor
    public function __construct($name = "") {
        //Assign Category
        $this->name = $name;

        //Store Category
        $this->StoreCategory();
    }

    //Add Category to Database
    public function StoreCategory() {
        //Save Category to Database if $this->name is Set
        if ($this->name) {
            //Create MySQLi Connection
            //$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
        }
    }

}
?>