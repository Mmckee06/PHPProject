<html>

    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Admin Store</title>

    <meta charset="UTF-8">

    <style>
        body {
            background-image: url("https://don16obqbay2c.cloudfront.net/wp-content/themes/ecwid/images/blocks/store-background.jpg");
            background-color: #F8E5C0;
            
        }
        
        table {
            border: solid 1px #080808;
            background-color: #F8F4EB;
            padding-left: 30;
            padding-right: 30;
            padding-bottom: 30px;
            padding-top: 30px;
            margin-left: 50;
            margin-right: 50;

        }
        
        table {
            border: solid 1px #080808;
            background-color: #F8F4EB;
            padding-left: 30px;
            padding-right: 30px;
            padding-bottom: 30px;
            padding-top: 30px;
            margin-left: 50px;
            margin-right: 50px;

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
        
         $success1 = $error1 = '';
        
        //Check user is admin
        if ($user_session["user_Type"] != 1){
            header("location: LoginSuccess.php");
        }
        
        //Prevents XSS, encodes/strips input of any specail characters
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        
        //Form Submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Edit item clicked
            if (isset($_POST['editItem'])) {
                header("location: editItem.php");
                $_SESSION['editItem'] = $_POST['editItem'];
            } 
            
            //Delete item from store
            if (isset($_POST['deleteItem'])) {
                $value2 = $_POST['deleteItem'];
                //Delete item from database
                $stmt = $db->prepare("DELETE FROM Store WHERE ItemID=?");
                    $stmt->bind_param("i",$value2);
                    $stmt->execute();
                    $stmt->close();
                    //header("Location: admin2.php");
            } 
            
            //Generates CSRF token if hasn't already
            if (empty($_SESSION['token'])) {
            $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
            }
            
            if (isset($_POST['addItem'])) {
                //Add item clicked, gather value from form
            $itemName = test_input(mysqli_real_escape_string($db, $_POST['itemName']));
            $itemType = test_input(mysqli_real_escape_string($db, $_POST['itemType']));
            $Price = test_input(mysqli_real_escape_string($db, $_POST['Price']));
            $Quantity = test_input(mysqli_real_escape_string($db, $_POST['Quantity']));
            $imagePath = test_input(mysqli_real_escape_string($db, $_POST['imagePath']));
            $token1 = test_input($_POST['token']);
                //Checks
                if ($itemName === '' || $itemType === '' || $Price === '' || $Quantity === '' || $imagePath === '' || $token1 === '') {
                    $error1 = "All fields required";
                } else if ($token1 != $_SESSION['token']) {
                   $error1 = "Failed Request";
                } else if ((preg_match('/[A-Z]/', $Quantity)) === 1 || ((preg_match('/[a-z]/', $Quantity) === 1 )) || (preg_match('/[0-9]/', $Quantity) === 0)) {
                    $error1 = "Quantity much contains numbers only";
                } else if ((preg_match('/[A-Z]/', $Price)) === 1 || ((preg_match('/[a-z]/', $Price) === 1 )) || (preg_match('/[0-9]/', $Price) === 0)) {
                    $error1 = "Price much contains numbers only";
                }else {    
                    //Checks passed, add item too database
                    $stmt = $db->prepare("INSERT INTO Store SET ItemName=?, ItemType=?, Quantity=?, Price=?, ItemImage=?");
                    $stmt->bind_param("ssids", $itemName, $itemType, $Quantity, $Price, $imagePath);
                    $stmt->execute();

                    $sucess1 = "Item added successfully";

                    $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));//regenerate CSRF token
                    $stmt->close();
                    header("location: admin2.php");
                }
            }
        }
        
        ?>


        <header class="w3-panel w3-center w3-opacity">
            <div class="w3-padding-32">
                <div class="w3-bar w3-border">
                    <a href="LoginSuccess.php" class="w3-bar-item w3-button">Home</a>
                    <a href="MyAccount.php" class="w3-bar-item w3-button">My Account</a>
                    <a href="shop.php" class="w3-bar-item w3-button">Shop</a>
                    <?php
                    if ($user_session["user_Type"] == 1) {
                        ?>
                        <a href="admin.php" class="w3-bar-item w3-button w3-light-grey">Admin</a>
                        <?php
                    }
                    ?>
                    <a href="logout.php" class="w3-bar-item w3-button w3-hide-small ">Log Out</a>
                </div>
            </div>

        </header>
        
        <div class="subTabs">
        <div class="w3-bar w3-border">
                    <a href="admin.php" class="w3-bar-item w3-button">Users</a>
                    <a href="admin2.php" class="w3-bar-item w3-button w3-light-grey">Store</a>
        </div>
        </div>
        
        <br>
        <?php
        
        $loop = mysqli_query($db, "SELECT * FROM Store")
                or die(mysql_error());
        
        echo "<table>";
        echo "<th>Item ID</th><th>Item Name</th><th>Item Type</th><th>Price</th><th>Quantity</th><th>Image Path</th>";


        while ($row = mysqli_fetch_array($loop, MYSQLI_ASSOC)) {
            echo "<tr>";
            echo "<td><center>" . $row['ItemID'] . "</center></td><td><center>" . $row['ItemName'] . "</center></td><td> <center>" . $row['ItemType'] . "</center></td><td><center> £" . $row['Price'] . "</center></td>"
                    . "<td><center>" . $row['Quantity'] . "</center></td><td><center>" . $row['ItemImage'] . "</center></td>";
            echo "<td><center>" . "<form action='' method='post'>" . "<button type='submit' name='editItem' value='{$row['ItemID']}' class='w3-button w3-border w3-light-grey'>Edit</button>" . "</center></td></form>";
            echo "<td><center>" . "<form action='' method='post'>" . "<button type='submit' name='deleteItem' value='{$row['ItemID']}' class='w3-button w3-border w3-light-grey'>Delete</button>" . "</center></td></form>";
            echo "</tr>";
        }

        echo "</table><br>";
        
        
        ?>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="w3-panel w3-center" >
            
            <fieldset>
                <legend>Add Item</legend>

                Item Name:<input type="text" name="itemName" value="">

                Item Type:<input type="text" name="itemType" value="">

                Price:<input type="text" name="Price" value="">
                
                Quantity:<input type="text" name="Quantity" value="">

                <br><br>Image Path:<input type="text" name="imagePath" value="">
                
                <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?>" />

                <button type="submit" name="addItem" class="w3-button w3-border w3-light-grey" >Add Item </button>

                <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error1; ?></div>
                <div style = "font-size:11px; color:#83f20e; margin-top:10px"><?php echo $success1; ?></div>
            </fieldset>

            <br>


        </form>
        
    </body>

</html>

