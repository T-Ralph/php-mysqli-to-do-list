<?php
//Set Up Class AutoLoading
spl_autoload_register(function ($class) {
    require_once dirname(__FILE__) . '/' . $class . '.Class.php';
});

//Include Database Credentials
require_once dirname(__FILE__) . '/DB.Credentials.php';

//Define Category Class
Class Category {

    //Declare Constructor
    public function __construct($name = "") {
        //Assign Category
        $this->name = $name;
        $this->message = NULL;

        //Add Category to Database 
        $this->AddCategoryToDatabase();
    }

    //Add Category to Database
    public function AddCategoryToDatabase() {
        //Add Category to Database if $this->name is Set
        if ($this->name) {
            //Create MySQLi Connection
            $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
            if ($mysqli->connect_error) {
                $this->message = "Connection Failed: " . $mysqli->connect_error;
            }

            //Prepare Statement, Bind and Execute
            $sql = "INSERT INTO `category` (`NAme`) VALUES (?)";
            $mysqli_query = $mysqli->prepare($sql);
            if (!$mysqli_query->bind_param("s", $this->name)) {
                $this->message = "Bind Failed: " . $mysqli_query->error;
            }
            if (!$mysqli_query->execute()) {
                $this->message = "Query Failed: " . $mysqli_query->error;
            }

            //Close Query and Connection
            $mysqli_query->close();
            $mysqli->close();

            //Set Successful Message
            if (!$this->message) {
                $this->message = "Category Added";
            }
        }
    }

    //Render Category
    public function RenderCategoryToSelectOptions() {
        //Create MySQLi Connection
        $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
        if ($mysqli->connect_error) {
            $this->message = "Connection Failed: " . $mysqli->connect_error;
        }

        //Statement and Query
        $sql = "SELECT `CategoryID`, `Name` FROM `category`";
        $mysqli_result = $mysqli->query($sql);

        //Close Connection
        $mysqli->close();

        //Render Category Options
        while( $category = $mysqli_result->fetch_assoc() ) {
            ?>
                <option value="<?php echo $category["CategoryID"]; ?>"><?php echo $category["Name"]; ?></option>
            <?php
        }
    }

}
?>