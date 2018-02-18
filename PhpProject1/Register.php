<html>

    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Register</title>

    <style>
        body {
            background-image: url("https://don16obqbay2c.cloudfront.net/wp-content/themes/ecwid/images/blocks/store-background.jpg");
            background-color: #F8E5C0;
        } 
        h4{
            text-align: center;
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
        include("config.php");
        
        //Response Headers
        header('X-Frame-Options: SAMEORIGIN');
        header('Strict-Transport-Security: max-age=31536000');
        header_remove("X-Powered-By");

        $error = $myusername = $mypassword = $mypassword1 = $firstname = $lastname = $addr1 = $addr2 = $city = $pcode = $phone = "";

        /* Form Required Field Validation */
        foreach ($_POST as $key => $value) {
            if (empty($_POST[$key])) {
                $error = "All Fields are required";
                break;
            }

            /* Password Matching Validation */
            if ($_POST['password'] != $_POST['password1']) {
                $error = 'Passwords should be same<br>';
                break;
            }
            
            //Request sent to register
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                // Data sent from form 
                $myusername = test_input(mysqli_real_escape_string($db, $_POST['name']));
                $mypassword = test_input(mysqli_real_escape_string($db, $_POST['password']));
                $mypassword1 = test_input(mysqli_real_escape_string($db, $_POST['password1']));
                $firstname = test_input(mysqli_real_escape_string($db, $_POST['fname']));
                $lastname = test_input(mysqli_real_escape_string($db, $_POST['lname']));
                $addr1 = test_input(mysqli_real_escape_string($db, $_POST['addr1']));
                $addr2 = test_input(mysqli_real_escape_string($db, $_POST['addr2']));
                $city = test_input(mysqli_real_escape_string($db, $_POST['city']));
                $pcode = test_input(mysqli_real_escape_string($db, $_POST['pcode']));
                $phone = test_input(mysqli_real_escape_string($db, $_POST['phone']));
                
                //Checks
                if ($firstname === '' || $lastname === '' || $addr1 === '' || $addr2 === '' || $city === '' || $pcode === '' || $phone === '' || $myusername === '' || $mypassword === '' || $mypassword1 === '') {
                    $error = "All fields required";
                } else  if (strlen($mypassword) <=7) {
                $error = 'Passwords must be at least 8 characters long<br>';
            } else if (preg_match('/[A-Z]/', $mypassword) === 0){
                $error = 'Passwords must contain at least 1 upper case characters<br>';
            } else if (preg_match('/[a-z]/', $mypassword) === 0){
                $error = 'Passwords must contain at least 1 lower case characters<br>';
            } else if (preg_match('/[0-9]/', $mypassword) === 0){
                $error = 'Passwords must contain at least 1 numerical characters<br>';
            }else {

                    //Query to database, Prepared statement to check if username exists already
                    $stmt = $db->prepare("SELECT customerId FROM customer WHERE username=?");
                    $stmt->bind_param("s", $myusername);
                    $stmt->execute();

                    //1 customer is returned if username is already in use
                    $stmt->store_result();
                    $count = $stmt->num_rows;

                    /* close statement */
                    $stmt->close();

                    if ($count == 1) {
                        $error = 'User already exists<br>';
                    } else {
                        //Add user to database
                        $stmt = $db->prepare("INSERT INTO CUSTOMER (USERNAME, PASSWORD, LASTNAME, FIRSTNAME, AddressLine1, AddressLine2, CITY, POSTALCODE, PHONE, user_Type, googleAuth) 
                        VALUES(?,?,?,?,?,?,?,?,?,'0','0')");
                        //Pass SQL statement values to insert
                        $stmt->bind_param("sssssssss", $myusername, $mypassword, $lastname, $firstname, $addr1, $addr2, $city, $pcode, $phone);
                        $stmt->execute();
                        ///Redirect to homepage
                        header("location: Homepage.php");
                    }
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


        <header class="w3-panel w3-center w3-opacity">


        </header>



        <h4>Register</h4> 

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="w3-panel w3-center" >
            <fieldset>
                <legend>Login Information</legend>

                Username:&nbsp;<input type="text" name="name" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>"><br><br>

                Password:&nbsp;&nbsp;<input type="Password" name="password">&nbsp;&nbsp;

                Confirm Password:&nbsp;<input type="Password" name="password1"><br><br>
            </fieldset>

            <fieldset>
                <legend>Delivery Information</legend>

                First Name:&nbsp;<input type="text" name="fname" value="<?php if (isset($_POST['fname'])) echo $_POST['fname']; ?>">&nbsp;

                Last Name:&nbsp;<input type="text" name="lname" value="<?php if (isset($_POST['lname'])) echo $_POST['lname']; ?>">

                Address Line 1:&nbsp;<input type="text" name="addr1" value="<?php if (isset($_POST['addr1'])) echo $_POST['addr1']; ?>">

                Address Line 2:&nbsp;<input type="text" name="addr2" value="<?php if (isset($_POST['addr2'])) echo $_POST['addr2']; ?>"><br><br>

                City:&nbsp;<input type="text" name="city" value="<?php if (isset($_POST['city'])) echo $_POST['city']; ?>">

                Postcode:&nbsp;<input type="text" name="pcode" value="<?php if (isset($_POST['pcode'])) echo $_POST['pcode']; ?>">

            </fieldset>

            <fieldset>
                <legend>Contact</legend>

                Phone:&nbsp;<input type="text" name="phone" value="<?php if (isset($_POST['phone'])) echo $_POST['phone']; ?>"><br><br>

            </fieldset>

            <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
            <br>
            <button type="submit" class="w3-button w3-border w3-light-grey" >Register </button>
            <h6> Already a member.<a href="Homepage.php" class="w3-center"> Login </a> now </h6>

        </form>


    </body>
</html>
