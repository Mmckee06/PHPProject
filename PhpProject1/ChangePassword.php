<html>


    <head>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <meta charset="UTF-8">
        <title>Change Password</title>

        <style>


            body {
                background-image: url("https://don16obqbay2c.cloudfront.net/wp-content/themes/ecwid/images/blocks/store-background.jpg");
                background-color: #F8E5C0;

            }
            
            form {
                
            }

        </style>
    </head>
    <body>
        
         <?php
        // define variables and set to empty values
         include('session1.php');
         
         //Response Headers
        header('X-Frame-Options: SAMEORIGIN');
        header('Strict-Transport-Security: max-age=31536000');
        header_remove("X-Powered-By");
        
        //Variables
        $name = $oldPassword = $newPassword1 = $newPassword2 = "";
        $error = "";
        $myusername = $_SESSION['userPasswordChange'];
        
        //User reuests hecnge password
         if ($_SERVER["REQUEST_METHOD"] == "POST") {
             
             //Gets users current password from dtabase
             $stmt = $db->prepare("SELECT PASSWORD FROM customer WHERE username=?");
                    $stmt->bind_param("s", $myusername);
                    $stmt->execute();

                    //Ensure 1 customer is returned or none
                    $stmt->store_result();
                    $count = $stmt->num_rows;

                    mysqli_stmt_bind_result($stmt, $currentOldPassword);

                /* fetch value */
                mysqli_stmt_fetch($stmt);

                //Close query
                mysqli_stmt_close($stmt);

                    if ($count == 1) {
                //Gather users values from form 
                $oldPassword = test_input(mysqli_real_escape_string($db, $_POST['oldPassword']));
                $myNewPassword1 = test_input(mysqli_real_escape_string($db, $_POST['newPassword1']));
                $myNewPassword2 = test_input(mysqli_real_escape_string($db, $_POST['newPassword2']));
                
                //Checks old password current, current password match and password policy is implemented
                if ($oldPassword === '' || $myNewPassword1 === '' || $myNewPassword2 === '') {
                    $error = "All fields required<br>";
                } else if ($currentOldPassword != $oldPassword) {
                    $error = "Old password is incorrect<br>";
                } else  if (strlen($myNewPassword1) <=7) {
                $error = 'New Passwords must be at least 8 characters long<br>';
            } else if (preg_match('/[A-Z]/', $myNewPassword1) === 0){
                $error = 'New Passwords must contain at least 1 upper case characters<br>';
            } else if (preg_match('/[a-z]/', $myNewPassword1) === 0){
                $error = 'New Passwords must contain at least 1 lower case characters<br>';
            } else if (preg_match('/[0-9]/', $myNewPassword1) === 0){
                $error = 'New Passwords must contain at least 1 numerical characters<br>';
            } else if ($myNewPassword1 != $myNewPassword2) {
                $error = 'New passwords do not match<br>';
            }else {
                //Checks cleared, Update users password with new Password
                $stmt = $db->prepare("UPDATE customer SET PASSWORD=? WHERE USERNAME=?");
                    $stmt->bind_param("ss", $myNewPassword1, $myusername);
                    $stmt->execute();
                    header("location: Homepage.php");

            }
                      
                    } else { // No user, locate to login
                        $_SESSION['userPasswordChange'] = null;
                        header("location: Homepage.php");
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
                        Old Password:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="Password" name="oldPassword"><br>

                        <br>

                        New Password:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="Password" name="newPassword1"><br>

                        <br>
                        
                        Confirm New Password:
                        <input type="Password" name="newPassword2"><br>
                        
                        <br>
                        <button type="submit" class="w3-button w3-border w3-light-grey" >Update Password </button>


                        <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
                        
                        <div style = "font-size:11px; color:black; margin-top:10px">
                            <center> Your password does not meet our password policy. <br>
                                Please ensure your password meets the following criteria <br>
                            1. At least 8 characters long<br>
                            2. Contain at least one upper case, lower case and <br>
                            numerical value.</center>
                        
                        </div>
                    </form>
                    

                </div></div>

           
        </header>
        
        
         </body>
</html>


