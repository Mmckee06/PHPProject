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
        
        img {

            height: 100; 
            width: 100;
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
        
        $success1 = $error1 = '';
        
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
        //Item ID to be edited
        $itemID = test_input($_SESSION['editItem']);
   
        //Get info about item from database
        $stmt = $db->prepare("Select ItemName, ItemType, Quantity, Price, ItemImage from Store where ItemID =?");
        $stmt->bind_param("i",$itemID);
        $stmt->execute();
        
         /* bind result variables from SQL query */
        mysqli_stmt_bind_result($stmt, $ItemName1, $ItemType1, $Quantity1, $Price1, $ItemImage1);

        /* fetch value */
        mysqli_stmt_fetch($stmt);

        //Close query
        mysqli_stmt_close($stmt);
        
        //Generate CSRF token if one doe not exist
        if (empty($_SESSION['token'])) {
            $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
        }
        
        //Prevents XSS, encodes/strips input of any specail characters
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            //Gather values from form
            $itemName = test_input(mysqli_real_escape_string($db, $_POST['itemName']));
            $itemType = test_input(mysqli_real_escape_string($db, $_POST['itemType']));
            $Price = test_input(mysqli_real_escape_string($db, $_POST['Price']));
            $Quantity = test_input(mysqli_real_escape_string($db, $_POST['Quantity']));
            $imagePath = test_input(mysqli_real_escape_string($db, $_POST['imagePath']));
            $token1 = test_input($_POST['token']);
        

            if (isset($_POST['editItem'])) {
                //Checks
                if ($itemName === '' || $itemType === '' || $Price === '' || $Quantity === '' || $imagePath === '' || $token1 === '') {
                    $error1 = "All fields required";
                } else if ($token1 != $_SESSION['token']) {
                    $error1 = "Failed Request";
                } else if ((preg_match('/[A-Z]/', $Quantity)) === 1 || ((preg_match('/[a-z]/', $Quantity) === 1 )) || (preg_match('/[0-9]/', $Quantity) === 0)) {
                    $error1 = "Quantity much contains numbers only";
                } else if ((preg_match('/[A-Z]/', $Price)) === 1 || ((preg_match('/[a-z]/', $Price) === 1 )) || (preg_match('/[0-9]/', $Price) === 0)) {
                    $error1 = "Price much contains numbers only";
                } else {
                    //Update item in database
                    $stmt = $db->prepare("UPDATE Store SET ItemName=?, ItemType=?, Quantity=?, Price=?, ItemImage=? WHERE ItemID=?");
                    $stmt->bind_param("ssidsi", $itemName, $itemType, $Quantity, $Price, $imagePath, $itemID);
                    $stmt->execute();

                    $sucess1 = "Updated details successfully";

                    $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
                    $stmt->close();
                    header("location: admin2.php");
                    
                }
                
                
            }
        }
            
        
        ?>
        
    <center><h1>Edit Item</h1> </center>
        <a href="admin2.php"><<< Back to Items List</a>


        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="w3-panel w3-center" >
            
            <fieldset>
                <legend>Item Information</legend>
                
                Item ID:&nbsp;<?php echo $itemID;?><br><br>

                Item Name:&nbsp;<input type="text" name="itemName" value="<?php echo $ItemName1; ?>">

                Item Type:&nbsp;<input type="text" name="itemType" value="<?php echo $ItemType1; ?>">

                Price:&nbsp;<input type="text" name="Price" value="<?php echo $Price1; ?>">
                
                Quantity:&nbsp;<input type="text" name="Quantity" value="<?php echo $Quantity1; ?>">

                <br><br>Image Path:&nbsp;<input type="text" name="imagePath" value="<?php echo $ItemImage1; ?>"><br><br>

                <img src="<?php echo $ItemImage1; ?>"><br><br>
                
                <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?> " />

                <button type="submit" name="editItem" class="w3-button w3-border w3-light-grey" >Submit Item Changes </button>

                <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error1; ?></div>
                <div style = "font-size:11px; color:#83f20e; margin-top:10px"><?php echo $success1; ?></div>
            </fieldset>

            <br>


        </form>

    </body>
    
</html>

