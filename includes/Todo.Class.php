<?php
//Set Up Class AutoLoading
spl_autoload_register(function ($class) {
    require_once dirname(__FILE__) . '/' . $class . '.Class.php';
});

//Include Database Credentials
require_once dirname(__FILE__) . '/DB.Credentials.php';

//Define Category Class
Class Todo {

    //Declare Constructor
    public function __construct($category_id = "", $task = "", $due_date = "") {
        //Assign Category
        $this->category_id = $category_id;
        $this->task = $task;
        $this->due_date = $due_date;
        $this->message = NULL;

        //Add Todo to Database 
        $this->AddTodoToDatabase();
    }

    //Add Todo to Database
    public function AddTodoToDatabase() {
        //Add Todo to Database if $this->category_id, $this->task and $this->due_date are Set and $this->category_id is Numeric
        if ($this->category_id && $this->task && $this->due_date && is_numeric($this->category_id)) {
            //Create MySQLi Connection
            $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
            if ($mysqli->connect_error) {
                $this->message = "Connection Failed: " . $mysqli->connect_error;
            }

            //Prepare Statement, Bind and Execute
            $sql = "INSERT INTO `todo` (`UserID`, `CategoryID`, `Task`, `DueDate`) VALUES (?, ?, ?, ?)";
            if (!$mysqli_query = $mysqli->prepare($sql)) {
                $this->message = "Prepare Failed: " . $mysqli_query->error;
            }
            if (!$mysqli_query->bind_param("iiss", $_SESSION["userid"], $this->category_id, $this->task, $this->due_date)) {
                $this->message = "Bind Failed: " . $mysqli_query->error;
            }
            if (!$mysqli_query->execute()) {
                $this->message = "Query Failed: " . $mysqli_query->error;
            }

            //Close Query and Connection
            $mysqli_query->close();
            $mysqli->close();

            //Set Successful Status
            if (!$this->message) {
                $this->message = "Todo Added";
            }
        }
    }

    //Render Active Todo
    public function RenderActiveTodo() {
        //Create MySQLi Connection
        $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
        if ($mysqli->connect_error) {
            $this->message = "Connection Failed: " . $mysqli->connect_error;
        }

        //Statement and Query
        $user_id = $mysqli->real_escape_string($_SESSION["userid"]);
        $sql = "SELECT `todo`.`TodoID`, `category`.`Name` AS 'CategoryName', `todo`.`Task`, `todo`.`DueDate`, `todo`.`CompletedDate` FROM `todo` LEFT JOIN `category` USING (`CategoryID`) WHERE `todo`.`UserID` = '$user_id' AND `todo`.`DueDate` > CURDATE() AND `todo`.`CompletedDate` IS NULL AND `todo`.`Deleted` = FALSE";
        if (!$mysqli_result = $mysqli->query($sql)) {
            $this->message = "Query Failed: " . $mysqli_result->error;
        }

        //Close Connection
        $mysqli->close();

        //Render Category Options
        while ($todo = $mysqli_result->fetch_assoc()) {
            ?>
                <li>
                    <b>Category:</b> <?php echo $todo["CategoryName"]; ?>.<br />
                    <b>Task:</b> <?php echo $todo["Task"]; ?>.<br />
                    <b>Due Date:</b> <?php echo $todo["DueDate"]; ?>.<br />
                    <form method="POST" action="">
                        <input type="hidden" name="todo_id" value="<?php echo $todo["TodoID"]; ?>" required />
                        <input type="submit" name="CompleteTodo" value="Complete Todo" />
                    </form>
                    <form method="POST" action="">
                        <input type="hidden" name="todo_id" value="<?php echo $todo["TodoID"]; ?>" required />
                        <input type="submit" name="DeleteTodo" value="Delete Todo" />
                    </form>
                </li>
            <?php
        }
    }

    //Render Overdue Todo
    public function RenderOverdueTodo() {
        //Create MySQLi Connection
        $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
        if ($mysqli->connect_error) {
            $this->message = "Connection Failed: " . $mysqli->connect_error;
        }

        //Statement and Query
        $user_id = $mysqli->real_escape_string($_SESSION["userid"]);
        $sql = "SELECT `todo`.`TodoID`, `category`.`Name` AS 'CategoryName', `todo`.`Task`, `todo`.`DueDate`, `todo`.`CompletedDate` FROM `todo` LEFT JOIN `category` USING (`CategoryID`) WHERE `todo`.`UserID` = '$user_id' AND `todo`.`DueDate` <= CURDATE() AND `todo`.`CompletedDate` IS NULL AND `todo`.`Deleted` = FALSE";
        if (!$mysqli_result = $mysqli->query($sql)) {
            $this->message = "Query Failed: " . $mysqli_result->error;
        }

        //Close Connection
        $mysqli->close();

        //Render Category Options
        while ($todo = $mysqli_result->fetch_assoc()) {
            ?>
                <li>
                    <b>Category:</b> <?php echo $todo["CategoryName"]; ?>.<br />
                    <b>Task:</b> <?php echo $todo["Task"]; ?>.<br />
                    <b>Due Date:</b> <?php echo $todo["DueDate"]; ?>.<br />
                    <form method="POST" action="">
                        <input type="hidden" name="todo_id" value="<?php echo $todo["TodoID"]; ?>" required />
                        <input type="submit" name="CompleteTodo" value="Complete Todo" />
                    </form>
                    <form method="POST" action="">
                        <input type="hidden" name="todo_id" value="<?php echo $todo["TodoID"]; ?>" required />
                        <input type="submit" name="DeleteTodo" value="Delete Todo" />
                    </form>
                </li>
            <?php
        }
    }

    //Render Completed Todo
    public function RenderCompletedTodo() {
        //Create MySQLi Connection
        $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
        if ($mysqli->connect_error) {
            $this->message = "Connection Failed: " . $mysqli->connect_error;
        }

        //Statement and Query
        $user_id = $mysqli->real_escape_string($_SESSION["userid"]);
        $sql = "SELECT `todo`.`TodoID`, `category`.`Name` AS 'CategoryName', `todo`.`Task`, `todo`.`DueDate`, `todo`.`CompletedDate` FROM `todo` LEFT JOIN `category` USING (`CategoryID`) WHERE `todo`.`UserID` = '$user_id' AND `todo`.`CompletedDate` IS NOT NULL AND `todo`.`Deleted` = FALSE";
        if (!$mysqli_result = $mysqli->query($sql)) {
            $this->message = "Query Failed: " . $mysqli_result->error;
        }

        //Close Connection
        $mysqli->close();

        //Render Category Options
        while ($todo = $mysqli_result->fetch_assoc()) {
            ?>
                <li>
                    <b>Category:</b> <?php echo $todo["CategoryName"]; ?>.<br />
                    <b>Task:</b> <?php echo $todo["Task"]; ?>.<br />
                    <b>Due Date:</b> <?php echo $todo["DueDate"]; ?>.<br />
                    <b>Completed Date:</b> <?php echo $todo["CompletedDate"]; ?>.<br />
                    <form method="POST" action="">
                        <input type="hidden" name="todo_id" value="<?php echo $todo["TodoID"]; ?>" required />
                        <input type="submit" name="DeleteTodo" value="Delete Todo" />
                    </form>
                </li>
            <?php
        }
    }

    //Complete Todo
    public function CompleteTodo($todo_id) {
        //Update Category in Database if $todo_id is Set and is Numeric
        if ($todo_id && is_numeric($todo_id)) {
            //Create MySQLi Connection
            $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
            if ($mysqli->connect_error) {
                $this->message = "Connection Failed: " . $mysqli->connect_error;
            }

            //Prepare Statement, Bind and Execute
            $sql = "UPDATE `todo` SET `CompletedDate` = CURDATE() WHERE `TodoID` = ? AND `UserID` = ? AND `Deleted` = FALSE";
            if (!$mysqli_query = $mysqli->prepare($sql)) {
                $this->message = "Prepare Failed: " . $mysqli_query->error;
            }
            if (!$mysqli_query->bind_param("ii", $todo_id, $_SESSION["userid"])) {
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
                $this->message = "Todo Completed";
            }
        }
        else {
            $this->message = "Invalid Todo ID";
        }
    }

    //Delete Todo
    public function DeleteTodo($todo_id) {
        //Update Category in Database if $todo_id is Set and is Numeric
        if ($todo_id && is_numeric($todo_id)) {
            //Create MySQLi Connection
            $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
            if ($mysqli->connect_error) {
                $this->message = "Connection Failed: " . $mysqli->connect_error;
            }

            //Prepare Statement, Bind and Execute
            $sql = "UPDATE `todo` SET `Deleted` = TRUE WHERE `TodoID` = ? AND `UserID` = ? AND `Deleted` = FALSE";
            if (!$mysqli_query = $mysqli->prepare($sql)) {
                $this->message = "Prepare Failed: " . $mysqli_query->error;
            }
            if (!$mysqli_query->bind_param("ii", $todo_id, $_SESSION["userid"])) {
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
                $this->message = "Todo Deleted";
            }
        }
        else {
            $this->message = "Invalid Todo ID";
        }
    }

}
?>