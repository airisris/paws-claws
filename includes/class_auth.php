<?php
    class Auth{

        public $database;

        function __construct() {
            // connect to Database
            $this->database = connectToDB();
        }

        public function getUserDataByEmail($email){
            $sql = "SELECT * FROM users WHERE email = :email";
            $query = $this->database->prepare($sql);
            $query->execute([
                "email" => $email
            ]);
            $user = $query->fetch(); // return the first row of the list

            return $user;
        }

        public function setErrorMessage($message, $redirect_url){
            $_SESSION["error"] = $message;
            header("location: " . $redirect_url);
            exit;
        }

        // redirect
        public function redirect( $url ) {
            header("location: " . $url);
            exit;
        }

        public function login(){

            // get the data from the login form
            $email = $_POST["email"];
            $password = $_POST["password"];

            // error checking
            if (empty($email) || empty($password)){
                $this->setErrorMessage( "All fields are required.", "/login" );
            } else {
                // get the user data by email
                $user = $this->getUserDataByEmail($email);

                // check if the user exists
                if($user){
                // check if password is correct or not
                    if (password_verify($password, $user["password"])){
                        // store the user data in the session storage to login the user
                        $_SESSION["user"] = $user;

                        // redirect the user back to index.php
                        $this->redirect("/");
                        exit;
                    } else {
                        $this->setErrorMessage( "Password provided is incorrect.", "/login" );
                    }
                } else {
                    $this->setErrorMessage( "Email provided does not exist.", "/login" );
                }

            }
        }

        public function signup(){

            // get the data from the signup form
            $name = $_POST["name"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $confirm_password = $_POST["confirm_password"];

            // error checking
            if (empty($name) || empty($email) || empty($password) || empty($confirm_password)){
                $this->setErrorMessage( "All fields are required.", "/signup" );
            } else if ($password !== $confirm_password){
                $this->setErrorMessage( "Password is not matched.", "/signup" );
            } else {
                $sql = "INSERT INTO users (`name`, `email`, `password`) VALUES (:name, :email, :password)";
                $query = $this->database->prepare($sql);
                $query->execute([
                    "name" => $name,
                    "email" => $email,
                    "password" => password_hash($password, PASSWORD_DEFAULT)
                ]);
                // redirect
                $_SESSION["success"] = "Account created successfully. Please login.";
                $this->redirect("/login");
            }
        }
    }
?>