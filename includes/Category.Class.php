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
            $sql = "INSERT INTO `category` (`UserID`, `Name`) VALUES (?, ?)";
            if (!$mysqli_query = $mysqli->prepare($sql)) {
                $this->message = "Prepare Failed: " . $mysqli_query->error;
            }
            if (!$mysqli_query->bind_param("is", $_SESSION["userid"], $this->name)) {
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

    //Get Category
    public function GetCategoryFromDatabase($category_id) {
        //Get Category from Database if $category_id is Set and Numeric
        if ($category_id && is_numeric($category_id)) {
            //Create MySQLi Connection
            $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
            if ($mysqli->connect_error) {
                $this->message = "Connection Failed: " . $mysqli->connect_error;
            }

            //Statement and Execute
            $category_id = $mysqli->real_escape_string($category_id);
            $user_id = $mysqli->real_escape_string($_SESSION["userid"]);
            $sql = "SELECT * FROM `category` WHERE `CategoryID` = '$category_id' AND `UserID` = '$user_id' AND `Deleted` = FALSE";
            if (!$mysqli_result = $mysqli->query($sql)) {
                $this->message = "Query Failed: " . $mysqli_result->error;
            }

            //Close Query and Connection
            $mysqli->close();

            //Set Successful Message
            if (!$this->message) {
                $this->category = $mysqli_result->fetch_assoc();
            }
        }
        else {
            $this->message = "Invalid Category ID";
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
        $user_id = $mysqli->real_escape_string($_SESSION["userid"]);
        $sql = "SELECT `CategoryID`, `Name` FROM `category` WHERE `UserID` = '$user_id' AND `Deleted` = FALSE";
        if (!$mysqli_result = $mysqli->query($sql)) {
            $this->message = "Query Failed: " . $mysqli_result->error;
        }

        //Close Connection
        $mysqli->close();

        //Render Category Options
        while ($category = $mysqli_result->fetch_assoc()) {
            ?>
                <option value="<?php echo $category["CategoryID"]; ?>"><?php echo $category["Name"]; ?></option>
            <?php
        }
    }

    //Update Category In Database
    public function UpdateCategoryInDatabase($category_id, $category_name) {
        //Update Category in Database if $category_id and $category_name is Set and $category_id is Numeric
        if ($category_id && $category_name && is_numeric($category_id)) {
            //Create MySQLi Connection
            $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
            if ($mysqli->connect_error) {
                $this->message = "Connection Failed: " . $mysqli->connect_error;
            }

            //Prepare Statement, Bind and Execute
            $sql = "UPDATE `category` SET `Name` = ? WHERE `CategoryID` = ? AND `UserID` = ? AND `Deleted` = FALSE";
            if (!$mysqli_query = $mysqli->prepare($sql)) {
                $this->message = "Prepare Failed: " . $mysqli_query->error;
            }
            if (!$mysqli_query->bind_param("sii", $category_name, $category_id, $_SESSION["userid"])) {
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
                $this->message = "Category Updated";
            }
        }
        else {
            $this->message = "Invalid Category ID or Name";
        }
    }

    //Delete Category From Database
    public function DeleteCategoryFromDatabase($category_id) {
        //Delete Category from Database if $category_id is Set and Numeric
        if ($category_id && is_numeric($category_id)) {
            //Create MySQLi Connection
            $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
            if ($mysqli->connect_error) {
                $this->message = "Connection Failed: " . $mysqli->connect_error;
            }

            //Prepare Statement, Bind and Execute
            $sql = "UPDATE `category` SET `Deleted` = TRUE WHERE `CategoryID` = ? AND `UserID` = ?";
            if (!$mysqli_query = $mysqli->prepare($sql)) {
                $this->message = "Prepare Failed: " . $mysqli_query->error;
            }
            if (!$mysqli_query->bind_param("ii", $category_id, $_SESSION["userid"])) {
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
                $this->message = "Category Deleted";
            }
        }
        else {
            $this->message = "Invalid Category ID";
        }
    }

}
?>