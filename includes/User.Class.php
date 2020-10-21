<?php
//Set Up Class AutoLoading
spl_autoload_register(function ($class) {
    require_once dirname(__FILE__) . '/' . $class . '.Class.php';
});

//Include Database Credentials
require_once dirname(__FILE__) . '/DB.Credentials.php';

//Define User Class
Class User {

    private $rawPassword;
    private $password;

    //Declare Constructor
    public function __construct($username = "", $password = "") {
        //Assign Category
        $this->username = $username;
        $this->rawPassword = $password;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->signedin = FALSE;
        $this->message = NULL;

        //Add User to Database 
        $this->AddUserToDatabase();
    }

    //Add User to Database
    public function AddUserToDatabase() {
        //Add User to Database if $this->username and $this->password are Set
        if ($this->username && $this->password) {
            //Get User From Database
            $this->GetUserFromDatabase($this->username);

            //If User Does Not Exist in Database
            if (!is_array($this->user) && empty($this->user)) {
                //Create MySQLi Connection
                $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
                if ($mysqli->connect_error) {
                    $this->message = "Connection Failed: " . $mysqli->connect_error;
                }

                //Prepare Statement, Bind and Execute
                $sql = "INSERT INTO `user` (`Username`, `Password`) VALUES (?, ?)";
                if (!$mysqli_query = $mysqli->prepare($sql)) {
                    $this->message = "Prepare Failed: " . $mysqli_query->error;
                }
                if (!$mysqli_query->bind_param("ss", $this->username, $this->password)) {
                    $this->message = "Bind Failed: " . $mysqli_query->error;
                }
                if (!$mysqli_query->execute()) {
                    $this->message = "Query Failed: " . $mysqli_query->error;
                }

                //Close Query and Connection
                $mysqli_query->close();
                $mysqli->close();

                //Set Successful Status
                $this->message = "User Added";
                $this->GetUserFromDatabase(); //Get User
                $this->signedin = TRUE; //Set $this->signedin to TRUE
                $this->SetUserSession(); //Set User $_SESSION["username"]
            }
            else {
                $this->message = "Username Already Exists";
                $this->VerifyUsernameAndPasswordInDatabase(); //Verify Username & Password for Sign In Process
            }
        }
    }

    //Get User
    public function GetUserFromDatabase() {
        //Get User from Database if $this->username is Set
        if ($this->username) {
            //Create MySQLi Connection
            $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
            if ($mysqli->connect_error) {
                $this->message = "Connection Failed: " . $mysqli->connect_error;
            }

            //Statement and Execute
            $this->username = $mysqli->real_escape_string($this->username);
            $sql = "SELECT `UserID`, `Username` FROM `user` WHERE `Username` = '$this->username' AND `Deleted` = FALSE";
            if (!$mysqli_result = $mysqli->query($sql)) {
                $this->message = "Query Failed: " . $mysqli_result->error;
            }

            //Close Query and Connection
            $mysqli->close();

            //Set $this->user
            $this->user = $mysqli_result->fetch_assoc();
        }
        else {
            $this->message = "Invalid User ID / Username";
        }
    }

    //Verify Username and Password
    public function VerifyUsernameAndPasswordInDatabase() {
        //Create MySQLi Connection
        $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
        if ($mysqli->connect_error) {
            $this->message = "Connection Failed: " . $mysqli->connect_error;
        }

        //Statement and Execute
        $this->username = $mysqli->real_escape_string($this->username);
        $sql = "SELECT `Username`, `Password` FROM `user` WHERE `Username` = '$this->username' AND `Deleted` = FALSE";
        if (!$mysqli_result = $mysqli->query($sql)) {
            $this->message = "Query Failed: " . $mysqli_result->error;
        }

        //Close Query and Connection
        $mysqli->close();

        //If User Exists and password_verify() Confirms Hash, then, Set Successful Message
        if ($mysqli_result->num_rows > 0 && password_verify($this->rawPassword, $mysqli_result->fetch_assoc()["Password"])) {
            $this->message = "Sign In Successful";
            $this->signedin = TRUE; //Set $this->signedin to TRUE
            $this->SetUserSession(); //Set User $_SESSION["username"]
        }
        else {
            $this->message = "Sign In Failed";
        }
    }

    //Set User $_SESSION["username"]
    public function SetUserSession() {
        //If $this->signedin to TRUE, Set $_SESSION["username"]
        if ($this->signedin && !empty($this->user)) {
            $_SESSION["username"] = $this->username;
            $_SESSION["userid"] = $this->user["UserID"];
        }
    }

    public function SignOut() {
        //Reset $this->signedin and Clear $_SESSION["username"]
        $this->signedin = FALSE;
        unset($_SESSION["username"]);
        unset($_SESSION["userid"]);
        session_destroy();
    }

    //Delete User From Database
    public function DeleteUserFromDatabase() {
        //Delete Username from Database if $_SESSION["username"] and $_SESSION["userid"] are Set and $_SESSION["userid"] is Numeric
        if (isset($_SESSION["username"]) && isset($_SESSION["userid"]) && is_numeric($_SESSION["userid"])) {
            //Create MySQLi Connection
            $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
            if ($mysqli->connect_error) {
                $this->message = "Connection Failed: " . $mysqli->connect_error;
            }

            //Prepare Statement, Bind and Execute
            $sql = "UPDATE `user` SET `Deleted` = TRUE WHERE `UserID` = ?";
            if (!$mysqli_query = $mysqli->prepare($sql)) {
                $this->message = "Prepare Failed: " . $mysqli_query->error;
            }
            if (!$mysqli_query->bind_param("i", $_SESSION["userid"])) {
                $this->message = "Bind Failed: " . $mysqli_query->error;
            }
            if (!$mysqli_query->execute()) {
                $this->message = "Query Failed: " . $mysqli_query->error;
            }

            //Close Query and Connection
            $mysqli_query->close();
            $mysqli->close();

            //Set Successful Message and SignOut
            if (!$this->message) {
                $this->message = "User Deleted";
                $this->SignOut(); //Sign User Out
            }
        }
        else {
            $this->message = "Invalid User ID";
        }
    }

}
?>