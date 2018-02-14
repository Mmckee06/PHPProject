<!DOCTYPE html>

<html>


    <head>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <meta charset="UTF-8">
        <title>Log In</title>

        <style>


            body {
                background-image: url("https://don16obqbay2c.cloudfront.net/wp-content/themes/ecwid/images/blocks/store-background.jpg");
                background-color: #F8E5C0;

            }

        </style>
    </head>
    <body>

        <?php
        
        //Response Headers
        header('X-Frame-Options: SAMEORIGIN');
        header('Strict-Transport-Security: max-age=31536000');
        header_remove("X-Powered-By"); 
             
        // define variables and set to empty values
        $name = $password = "";
        $error = "";
        $myusername = "";

        //Connect to database
        include("config.php");
        session_start();

        //Form submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // username and password sent from form
            $myusername = test_input(mysqli_real_escape_string($db, $_POST['name']));
            $mypassword = test_input(mysqli_real_escape_string($db, $_POST['password']));

            //Query to database
            $stmt = $db->prepare("SELECT customerId FROM customer WHERE username=? and BINARY password = BINARY ?");
            $stmt->bind_param("ss", $myusername, $mypassword);
            $stmt->execute();

            //Ensure 1 customer is returned or none
            $stmt->store_result();
            $count = $stmt->num_rows;

            /* close statement */
            $stmt->close();

            // If result matched $myusername and $mypassword, table row must be 1 row
            if ($count == 1) {
                if (strlen($mypassword) <=7) {
                    $_SESSION['userPasswordChange'] = $myusername;
                    header("location: ChangePassword.php");
            } else if (preg_match('/[A-Z]/', $mypassword) === 0){
                $_SESSION['userPasswordChange'] = $myusername;
                header("location: ChangePassword.php");
            } else if (preg_match('/[a-z]/', $mypassword) === 0){
                $_SESSION['userPasswordChange'] = $myusername;
                header("location: ChangePassword.php");
            } else if (preg_match('/[0-9]/', $mypassword) === 0){
                $_SESSION['userPasswordChange'] = $myusername;
                header("location: ChangePassword.php");
            } else {
                $_SESSION['login_user'] = $myusername;
                session_regenerate_id(); // regenerate session cookie
                header("location: LoginSuccess.php");
            }
            } else {
                $error = "Your Login Name or Password is invalid";
            }
        }

        //Prevents XSS, encodes/strips input of any specail characters
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        ?>

        <header class="w3-panel w3-center w3-opacity">
            <div class="w3-padding-32">
                <div class="w3-bar w3-border">

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="w3-panel">
                        Username:&nbsp;<input type="text" name="name"><br>

                        <br>

                        Password:&nbsp;&nbsp;<input type="Password" name="password"><br>

                        <br>
                        <button type="submit" class="w3-button w3-border w3-light-grey" >Log In </button>


                        <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>

                    </form>

                </div></div>

            <h6> Not a member.<a href="Register.php"> Register </a> now </h6>
        </header>
    </body>
</html>
