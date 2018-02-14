<html>

    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Your Orders</title>
    
    <meta charset="UTF-8">

    <style>
        body {
            background-image: url("https://don16obqbay2c.cloudfront.net/wp-content/themes/ecwid/images/blocks/store-background.jpg");
            background-color: #F8E5C0;

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
        
        table {
            border: solid 1px #080808;
            background-color: #F8F4EB;
            padding-left: 30px;
            padding-right: 30px;
            padding-bottom: 30px;
            padding-top: 30px;
            margin-left: 50px;
            margin-right: 50px;
            margin-top: 20px;

        }
        
        img {

            height: 100px; 
            width: 100px;
        }
        
        td {
            padding: 5px;
        }

    </style>
    <body>
        <?php
        include('session.php');
        
        //Response Headers
        header('X-Frame-Options: SAMEORIGIN');
        header('Strict-Transport-Security: max-age=31536000');
        header_remove("X-Powered-By");
        
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
        //Order selected to view
        $orderID = test_input($_SESSION['viewOrder']);
        
        ?>
        
        <center><h1>Your Order</h1></center>
        
        <div class="subTabs">
        <div class="w3-bar w3-border">
                    <a href="MyAccount.php" class="w3-bar-item w3-button">My Account</a>
                    <a href="orders.php" class="w3-bar-item w3-button w3-light-grey">My Orders</a>
        </div>
       </div>

        <img src="https://cdn.shopify.com/s/files/1/0207/4676/t/13/assets/new_cart_icon.png?16453297725671707473" onclick="location.href = 'basket.php'" alt="basket" id="basket">
        <label id="basketNum"><?php echo array_sum($_SESSION['cartQunatity']); ?></label>
        
        <br><center><span class="w3-tag">Order ID:&nbsp;<?php echo $orderID; ?></span></center><br>
        
        <?php
      
        $price1 =0;
        //Loop trough all order for those that are related to order selected
        $loop =  $stmt = $db->prepare("SELECT ItemID, Amount FROM Product_Order Where SalesID=?")
                or die(mysql_error());
        
        $stmt->bind_param("i", $orderID);
        
        $stmt->execute();

        $stmt->store_result();
        $stmt->bind_result($itemID, $amount);
        
        echo "<center><table>";
        echo "<th></th><th>Item Name</th><th>Price</th><th>Amount</th><th>Total</th>";
        
        $items = array();
        $amount1 = array();
        
        //Select item ID / Quantity of each product prder record returned and put in array
        while($stmt->fetch())
        {
            array_push($items, $itemID);
            array_push($amount1, $amount);
           
        }
        
        $stmt->close();
        
         $arrlength = count($items);
        //Gather mre info on the items that are within order
        for ($x = 0; $x < $arrlength; $x++) {
                            $value = ($items[$x]);
                            $loop = $stmt = $db->prepare("SELECT ItemName, Price, ItemImage  FROM Store WHERE ItemID =?");
                            $stmt->bind_param("s", $value);
                            
                             /* execute query */
                $stmt->execute();

                /* bind result variables from SQL query */
                mysqli_stmt_bind_result($stmt,$ItemName, $Price, $ItemImage);

                /* fetch value */
                mysqli_stmt_fetch($stmt);

                //Close query
                mysqli_stmt_close($stmt);


                echo "<tr>";
                echo "<td>" . "<img src=\"$ItemImage\" />" . "</td>";
                echo "<td><center>" . $ItemName . "</center></td>";
                echo "<td><center>£" . $Price . "</center></td>";
                echo "<td><center>" . $amount1[$x] . "</center></td>";
                echo "<td><center>£" . $amount1[$x] * $Price  . "</center></td>";
                echo "</tr>";
                
                $price1 = $price1 + ($amount1[$x] * $Price);
        }
        
        echo "</center></table>";
        echo "<br><br>";
        
        ?>
        
        
        <center><span class="w3-tag">Total:&nbsp;£<?php echo $price1; ?></span></center><br><br>
        
      
     
    </body>
    
</html>



