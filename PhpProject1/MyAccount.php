<html>

    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>My Account</title>

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

        #basket {
            width:25px;
            height:25px;
            position: absolute;
            top: 50px;
            right: 150px;
        }

        #basketNum {

            position: absolute;
            padding: 0.5px;
            top: 50px;
            right: 135px;

        }
        
        .subTabs{
            padding-left: 35px;
            padding-right: 35px;
        }

    </style>
    <body>
        <?php
        include('session.php');
        
        //Response Headers
        header('X-Frame-Options: SAMEORIGIN');
        header('Strict-Transport-Security: max-age=31536000');
        header_remove("X-Powered-By");
        
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

        //Decalre variables
        $error = $error1 = $myusername = $mypassword = $mypassword1 = $mypassword2 = $firstname = $lastname = $addr1 = $addr2 = $city = $pcode = $phone = $sucess = $sucess1 = $token1 = "";
        
        //Generate CSRF token if there isn't one
        if (empty($_SESSION['token'])) {
            $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
        }
        //Reqest submitted to update
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            //Get entered values
            $myusername = test_input(($_SESSION['login_user']));
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
            $token1 = test_input($_POST['token']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Changed password clicked
            if (isset($_POST['changePassword'])) {
                //Perform password checks
                if ($_POST['password1'] != $_POST['password2']) {
                    $error = 'New passwords should be the same<br>';
                } else if (empty($_POST["password"])) {
                    $error = "Old password is required";
                } else if (empty($_POST["password1"])) {
                    $error = "New password is required";
                } else if (($user_session["PASSWORD"] != $_POST["password"])) {
                    $error = "Old password not correct";
                } else  if (strlen($mypassword1) <=7) {
                $error = 'Passwords must be at least 8 characters long<br>';
            } else if (preg_match('/[A-Z]/', $mypassword1) === 0){
                $error = 'Passwords must contain at least 1 upper case characters<br>';
            } else if (preg_match('/[a-z]/', $mypassword1) === 0){
                $error = 'Passwords must contain at least 1 lower case characters<br>';
            } else if (preg_match('/[0-9]/', $mypassword1) === 0){
                $error = 'Passwords must contain at least 1 numerical characters<br>';
            } else {
                    ///Checks passed, update users password on database
                    $stmt = $db->prepare("UPDATE CUSTOMER SET PASSWORD=? WHERE USERNAME=?");
                    $stmt->bind_param("ss", $mypassword1, $myusername);
                    $stmt->execute();

                    //Update session with new password;
                    $user_session['PASSWORD'] = $mypassword1;
                    $sucess = "Password changed successfully";
                    $stmt->close();
                }
            } else { //Change details button clicked, check no data is empty
                if ($firstname === '' || $lastname === '' || $addr1 === '' || $addr2 === '' || $city === '' || $pcode === '' || $phone === '') {
                    $error1 = "All fields required";
                } else if ($token1 != $_SESSION['token']) { //Check CSRF tokens match
                    $error1 = "Failed Request"; 
                } else {
                    //SQL Prepared statement, update users account on database
                    $stmt = $db->prepare("UPDATE customer SET LASTNAME=?, FIRSTNAME=?, AddressLine1=?, AddressLine2=?, CITY=?, POSTALCODE=?, PHONE=? WHERE USERNAME=?");
                    $stmt->bind_param("ssssssss", $lastname, $firstname, $addr1, $addr2, $city, $pcode, $phone, $myusername);
                    $stmt->execute();
                    
                    //Update session with new values entered
                    $user_session['FIRSTNAME'] = $firstname;
                    $user_session['LASTNAME'] = $lastname;
                    $user_session['AddressLine1'] = $addr1;
                    $user_session['AddressLine2'] = $addr2;
                    $user_session['CITY'] = $city;
                    $user_session['POSTALCODE'] = $pcode;
                    $user_session['PHONE'] = $phone;

                    $sucess1 = "Updated details successfully";

                    $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32)); //Regenerate CSRF token
                    $stmt->close();
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

    <center><h1>Your Account</h1></center>
        
        <div class="subTabs">
        <div class="w3-bar w3-border">
                    <a href="MyAccount.php" class="w3-bar-item w3-button w3-light-grey">My Account</a>
                    <a href="orders.php" class="w3-bar-item w3-button">My Orders</a>
        </div>
       </div>

        <img src="https://cdn.shopify.com/s/files/1/0207/4676/t/13/assets/new_cart_icon.png?16453297725671707473" onclick="location.href = 'basket.php'" alt="basket" id="basket">
        <label id="basketNum"><?php echo array_sum($_SESSION['cartQunatity']); ?></label>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="w3-panel w3-center" >
            <fieldset>
                <legend>User Information</legend>

                Username:&nbsp;<?php echo $user_session["USERNAME"]; ?><br><br>

                Old Password:&nbsp;&nbsp;<input type="Password" name="password">&nbsp;&nbsp;

                New Password:&nbsp;&nbsp;<input type="Password" name="password1">&nbsp;&nbsp;

                Confirm New Password:&nbsp;<input type="Password" name="password2"><br><br>



                <button type="submit" name="changePassword" class="w3-button w3-border w3-light-grey" >Change Password </button>

                <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
                <div style = "font-size:11px; color:#83f20e; margin-top:10px"><?php echo $sucess; ?></div>
            </fieldset>

            <fieldset>
                <legend>Delivery Information and Contact</legend>

                First Name:&nbsp;<input type="text" name="fname" value="<?php echo $user_session["FIRSTNAME"]; ?>">&nbsp;

                Last Name:&nbsp;<input type="text" name="lname" value="<?php echo $user_session["LASTNAME"]; ?>">

                Address Line 1:&nbsp;<input type="text" name="addr1" value="<?php echo $user_session["AddressLine1"]; ?>">

                Address Line 2:&nbsp;<input type="text" name="addr2" value="<?php echo $user_session["AddressLine2"]; ?>"><br><br>

                City:&nbsp;<input type="text" name="city" value="<?php echo $user_session["CITY"]; ?>">

                Postcode:&nbsp;<input type="text" name="pcode" value="<?php echo $user_session["POSTALCODE"]; ?>">

                Phone:&nbsp;<input type="text" name="phone" value="<?php echo $user_session["PHONE"]; ?>"><br><br>

                <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?>" />

                <button type="submit" name="editInfo" class="w3-button w3-border w3-light-grey" >Submit Account Changes </button>

                <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error1; ?></div>
                <div style = "font-size:11px; color:#83f20e; margin-top:10px"><?php echo $sucess1; ?></div>
            </fieldset>

            <br>


        </form>
    </body>
</html>
