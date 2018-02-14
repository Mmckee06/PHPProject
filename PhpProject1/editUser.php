<html>

    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Users Account</title>

    <style>
        body {
            background-image: url("https://don16obqbay2c.cloudfront.net/wp-content/themes/ecwid/images/blocks/store-background.jpg");
            background-color: #F8E5C0;

        }

        fieldset
        { 
            border: solid 1px #080808;
            background-color: #F8F4EB;
            opacity: 0.8;
        }

    </style>
    <body>
        <?php
        include('session.php');
        
        //Response Headers
        header('X-Frame-Options: SAMEORIGIN');
        header('Strict-Transport-Security: max-age=31536000');
        header_remove("X-Powered-By");
        
        //Check user is admin
        if ($user_session["user_Type"] != 1){
            header("location: LoginSuccess.php");
        }
        
        ?>


        <header class="w3-panel w3-center w3-opacity">
            <div class="w3-padding-32">
                <div class="w3-bar w3-border">
                    <a href="LoginSuccess.php" class="w3-bar-item w3-button w3-light-grey">Home</a>
                    <a href="MyAccount.php" class="w3-bar-item w3-button">My Account</a>
                    <a href="shop.php" class="w3-bar-item w3-button">Shop</a>
                    <?php
                    if ($user_session["user_Type"] == 1) {
                        ?>
                        <a href="admin.php" class="w3-bar-item w3-button">Admin</a>
                        <?php
                    }
                    ?>
                    <a href="logout.php" class="w3-bar-item w3-button w3-hide-small ">Log Out</a>
                </div>
            </div>

        </header>

        <?php
        //If user not logged in redirect to login page
        if (empty($_SESSION['login_user'])) {
            header('Location:Homepage.php');
        }
        //User to be edited
        $myusername = test_input($_SESSION['editUser']);
        
        //Gather users data from database
        $stmt = $db->prepare("Select PASSWORD, FIRSTNAME, LASTNAME, AddressLine1, AddressLine2, CITY, POSTALCODE, PHONE, user_Type from customer where username =?");
        $stmt->bind_param("s",$myusername);
        $stmt->execute();
        
        /* bind result variables from SQL query */
        mysqli_stmt_bind_result($stmt, $Password1, $FIRSTNAME1, $LASTNAME1, $AddressLine11, $AddressLine21, $CITY1, $POSTALCODE1, $PHONE1, $user_Type1);

        /* fetch value */
        mysqli_stmt_fetch($stmt);

        //Close query
        mysqli_stmt_close($stmt);


        $error = $error1 = $mypassword = $mypassword1 = $mypassword2 = $firstname = $lastname = $addr1 = $addr2 = $city = $pcode = $phone = $sucess = $sucess1 = $token1 = "";

        //Gnerate CSRF token if there isn't one
        if (empty($_SESSION['token'])) {
            $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
        }
            
            
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            //Gather values from form
            $mypassword = test_input(mysqli_real_escape_string($db, $_POST['password']));
            $mypassword1 = test_input(mysqli_real_escape_string($db, $_POST['password1']));
            $mypassword2 = test_input(mysqli_real_escape_string($db, $_POST['password2']));
            $firstname = test_input(mysqli_real_escape_string($db, $_POST['fname']));
            $lastname = test_input(mysqli_real_escape_string($db, $_POST['lname']));
            $addr1 = test_input(mysqli_real_escape_string($db, $_POST['addr1']));
            $addr2 = test_input(mysqli_real_escape_string($db, $_POST['addr2']));
            $city = test_input(mysqli_real_escape_string($db, $_POST['city']));
            $pcode = test_input(mysqli_real_escape_string($db, $_POST['pcode']));
            $phone = test_input(mysqli_real_escape_string($db, $_POST['phone']));
            //Check if aadmin or not and select correct value
            if (test_input($_POST['admin']) == "Yes") {
                $selected_admin = 1;
            } else if (test_input($_POST['admin'] == "No")) {
                $selected_admin = 0;
            }
            //CSRF token
            $token1 = test_input($_POST['token']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Change password clicked
            if (isset($_POST['changePassword'])) {
                //Checks
                if ($_POST['password1'] != $_POST['password2']) {
                    $error = 'New passwords should be the same<br>';
                } else if (empty($_POST["password"])) {
                    $error = "Admin password is required";
                } else if (empty($_POST["password1"])) {
                    $error = "New password is required";
                } else if (($user_session["PASSWORD"] != $_POST["password"])) {
                    $error = "Admin password not correct";
                } else  if (strlen($mypassword1) <=7) {
                $error = 'Passwords must be at least 8 characters long<br>';
            } else if (preg_match('/[A-Z]/', $mypassword1) === 0){
                $error = 'Passwords must contain at least 1 upper case characters<br>';
            } else if (preg_match('/[a-z]/', $mypassword1) === 0){
                $error = 'Passwords must contain at least 1 lower case characters<br>';
            } else if (preg_match('/[0-9]/', $mypassword1) === 0){
                $error = 'Passwords must contain at least 1 numerical characters<br>';
            }else {
                    //Set users password to new passsword
                    $stmt = $db->prepare("UPDATE CUSTOMER SET PASSWORD=? WHERE USERNAME=?");
                    $stmt->bind_param("ss", $mypassword1, $myusername);
                    $stmt->execute();

                    //Update users session password
                    $user_session['PASSWORD'] = $mypassword1;
                    $sucess = "Password changed successfully";
                    $stmt->close();
                }
            } else { //Change details button clicked
                if ($firstname === '' || $lastname === '' || $addr1 === '' || $addr2 === '' || $city === '' || $pcode === '' || $phone === '') {
                    $error1 = "All fields required";
                } else if ($token1 != $_SESSION['token']) {
                    $error1 = "Failed Request";
                } else {
                    //SQL Prepared statement, update customer
                
                    $stmt = $db->prepare("UPDATE customer SET LASTNAME=?, FIRSTNAME=?, AddressLine1=?, AddressLine2=?, CITY=?, POSTALCODE=?, PHONE=?, user_Type=? WHERE USERNAME=?");
                    $stmt->bind_param("sssssssis", $lastname, $firstname, $addr1, $addr2, $city, $pcode, $phone, $selected_admin, $myusername);
                    $stmt->execute();

                    $sucess1 = "Updated details successfully";

                    $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
                    $stmt->close();
                    header("location: admin.php");
                }
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

    <center><h1>Edit User</h1></center>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="w3-panel w3-center" >
            <fieldset>
                <legend>User Information</legend>

                Username:&nbsp;<?php echo $myusername; ?><br><br>

                Admin Password:&nbsp;&nbsp;<input type="Password" name="password">&nbsp;&nbsp;

                Users New Password:&nbsp;&nbsp;<input type="Password" name="password1">&nbsp;&nbsp;

                Users Confirm New Password:&nbsp;<input type="Password" name="password2"><br><br>

                <button type="submit" name="changePassword" class="w3-button w3-border w3-light-grey" >Change Password </button>

                <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
                <div style = "font-size:11px; color:#83f20e; margin-top:10px"><?php echo $sucess; ?></div>
            </fieldset>

            <fieldset>
                <legend>Delivery Information, Contact and Admin Status</legend>

                First Name:&nbsp;<input type="text" name="fname" value="<?php echo $FIRSTNAME1; ?>">&nbsp;

                Last Name:&nbsp;<input type="text" name="lname" value="<?php echo $LASTNAME1; ?>">

                Address Line 1:&nbsp;<input type="text" name="addr1" value="<?php echo $AddressLine11; ?>">

                Address Line 2:&nbsp;<input type="text" name="addr2" value="<?php echo $AddressLine21; ?>"><br><br>

                City:&nbsp;<input type="text" name="city" value="<?php echo $CITY1; ?>">

                Postcode:&nbsp;<input type="text" name="pcode" value="<?php echo $POSTALCODE1 ?>">

                Phone:&nbsp;<input type="text" name="phone" value="<?php echo $PHONE1; ?>"><br><br>
                
                <input type="radio" name="admin" value="Yes" <?php if ($user_Type1 == 1) {?>checked<?php }?>> Admin
                <input type="radio" name="admin" value="No"<?php if ($user_Type1 == 0) {?>checked<?php }?>> Non-Admin<br><br>

                <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?> " />

                <button type="submit" name="editInfo" class="w3-button w3-border w3-light-grey" >Submit Account Changes </button>

                <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error1; ?></div>
                <div style = "font-size:11px; color:#83f20e; margin-top:10px"><?php echo $sucess1; ?></div>
            </fieldset>

            <br>


        </form>
    </body>
</html>


