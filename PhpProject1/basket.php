<html>

    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>My Basket</title>

    <meta charset="UTF-8">

    <style>
        body {
            background-image: url("https://don16obqbay2c.cloudfront.net/wp-content/themes/ecwid/images/blocks/store-background.jpg");
            background-color: #F8E5C0;
        }

        img {
            width: 100px;
            height: 100px;
        }

        td{
            padding-left: 10px;
            padding-right: 10px;
            padding-bottom: 10px;
        }

        table {
            padding-left: 30;
            padding-right: 30;
            padding-bottom: 30px;
            padding-top: 30px;
            margin-left: 50;
            border: solid 1px #080808;
            background-color: #F8F4EB;

        }

    </style>
    <body>

        <?php
        include('session.php');
        $arrlength = count($_SESSION['cart']);

        //Response Headers
        header('X-Frame-Options: SAMEORIGIN');
        header('Strict-Transport-Security: max-age=31536000');
        header_remove("X-Powered-By");

        $error = "";$price1 = 0;
        
        
         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                //something posted

                if (isset($_POST['removeItem'])) {
                    //Remove item from basket array
                    $position = $_POST['removeItem'];
                    //echo '<script type="text/javascript">alert("Data has been submitted to ' . $position . '");</script>';
                    unset($_SESSION['cart'][$position]);
                    unset($_SESSION['cartQunatity'][$position]);
                    $_SESSION['cart'] = array_values($_SESSION['cart']);
                    $_SESSION['cartQunatity'] = array_values($_SESSION['cartQunatity']);
                    $_SESSION['cartPrice'] = array_values($_SESSION['cartPrice']);
                    header("location: basket.php");
                } else if (isset($_POST['checkOut'])) {
                    $check = 1;
                    //Check enought quantity in stock
                    for ($x = 0; $x < $arrlength; $x++) {
                        $value = ($_SESSION['cart'][$x]);
                        $loop = $stmt = $db->prepare("SELECT Quantity FROM Store WHERE ItemName =?");
                        $stmt->bind_param("s", $value);

                        /* execute query */
                        $stmt->execute();

                        /* bind result variables from SQL query */
                        mysqli_stmt_bind_result($stmt, $Quantity1);

                        /* fetch value */
                        mysqli_stmt_fetch($stmt);

                        //Close query
                        mysqli_stmt_close($stmt);
                        // If quanity not enough give error
                        if (($Quantity1 - $_SESSION['cartQunatity'][$x]) < 0) {
                            $error = "Unfortunatly we haven't got sufficenet stock for your " . $_SESSION['cart'][$x] . " order.";
                            $check = 0;
                        }
                    }
                    //Quantity ok, so proceed
                    if ($check == 1) {
                        //Remove quantity in basket for each product away from the total quantity left in stock
                        for ($x = 0; $x < $arrlength; $x++) {
                            $value = ($_SESSION['cart'][$x]);
                            $loop = $stmt = $db->prepare("SELECT Quantity, PRICE FROM Store WHERE ItemName =?");
                            $stmt->bind_param("s", $value);

                            /* execute query */
                            $stmt->execute();

                            /* bind result variables from SQL query */
                            mysqli_stmt_bind_result($stmt, $Quantity2, $Price3);

                            /* fetch value */
                            mysqli_stmt_fetch($stmt);

                            //Close query
                            mysqli_stmt_close($stmt);
                            
                            $TotalPrice = $TotalPrice + ($Price3 * $_SESSION['cartQunatity'][$x]);

                            $quantity = $Quantity2 - ($_SESSION['cartQunatity'][$x]);
                            //Remove quantity from database
                            $stmt = $db->prepare("UPDATE Store SET Quantity=? WHERE ItemName=?");
                            $stmt->bind_param("is", $quantity, $value);
                            $stmt->execute();
                            $stmt->close();
                        }
                        // Create a Sales order
                        $stmt = $db->prepare("INSERT INTO Sales_Order (CUSTOMERID, TotalItems, Total) 
                        VALUES(?,?,?)");
                        $stmt->bind_param("idd", $user_session["CUSTOMERID"], array_sum($_SESSION['cartQunatity']), $TotalPrice);
                        $stmt->execute();
                        $stmt->close();
                        
                        //Get new sales ID for product table
                        $stmt1 = $db->prepare("SELECT SalesID FROM Sales_Order Where CUSTOMERID=? ORDER BY SalesID DESC LIMIT 1");
                        $stmt1->bind_param("i", $user_session["CUSTOMERID"]);
                        $stmt1->execute();

                        /* bind result variables from SQL query */
                        mysqli_stmt_bind_result($stmt1, $salesOrderID);

                        /* fetch value */
                        mysqli_stmt_fetch($stmt1);

                        //Close query
                        mysqli_stmt_close($stmt1);
                        //Create products order
                        for ($x = 0; $x < $arrlength; $x++) {
                            $value = ($_SESSION['cart'][$x]);
                            $loop = $stmt = $db->prepare("SELECT ItemID FROM Store WHERE ItemName =?");
                            $stmt->bind_param("s", $value);
                            $stmt->execute();
                            mysqli_stmt_bind_result($stmt, $itemID);
                            mysqli_stmt_fetch($stmt);
                            mysqli_stmt_close($stmt);


                            $stmt = $db->prepare("INSERT INTO Product_Order (SalesID, ItemID, Amount) 
                        VALUES(?,?,?)");

                            $stmt->bind_param("iii", $salesOrderID, $itemID, $_SESSION['cartQunatity'][$x]);
                            $stmt->execute();
                        }
                        
                        //Clear Basket
                        $_SESSION['cart'] = array();
                        $_SESSION['cartQunatity'] = array();
                        $_SESSION['cartPrice'] = array();
                        
                        //Success, redirect user
                        header("location: orderConfirmation.php");
                    }
                }
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

        <h2>Your Basket</h2> 

        <h4>You currently have <?php echo array_sum($_SESSION['cartQunatity']); ?> items in your basket</h4><br>

        <table>

            <?php
            if ($arrlength > 0) {
                ?>

                <th>Picture</th>
                <th>Item</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th></th>

                <?php
            }
            
            //Loop throuh basket array, gather information needed from store datatbase
            for ($x = 0; $x < $arrlength; $x++) {
                $value = ($_SESSION['cart'][$x]);
                $loop = $stmt = $db->prepare("SELECT Price, ItemImage FROM Store WHERE ItemName =?");
                $stmt->bind_param("s", $value);

                /* execute query */
                $stmt->execute();

                /* bind result variables from SQL query */
                mysqli_stmt_bind_result($stmt, $Price, $ItemImage);

                /* fetch value */
                mysqli_stmt_fetch($stmt);

                //Close query
                mysqli_stmt_close($stmt);

                //Display data in table

                echo "<tr>";
                echo "<td>" . "<img src=\"$ItemImage\" />" . "</td>";
                echo "<td><center>" . ($_SESSION['cart'][$x]) . "</center></td>";
                echo "<td><center>£" . $Price . "</center></td>";
                echo ("<td><center>" . $_SESSION['cartQunatity'][$x]) . "</center></td>";
                echo "<td><center>£" . ($_SESSION['cartQunatity'][$x] * $Price) . "</center></td>";
                echo "<td><center>" . "<form action='' method='post'>" . "<button type='submit' name='removeItem' value='$x' class='w3-button w3-border w3-light-grey' >Remove </button></form>" . "</center></td>";
                echo "</tr>";

                $price1 = $price1 + ($Price * $_SESSION['cartQunatity'][$x]);
            }


           
            ?>

        </table>

        <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>

        <h4>Total balance is: £<?php echo $price1; ?></h4>

<?php
if ($arrlength > 0) {
    echo "<form action='' method='post'>" . "<button type='submit' name='checkOut' value='CheckOut' class='w3-button w3-border w3-light-grey' >Check Out </button></form>";
}
?>

    </body>

</html>

