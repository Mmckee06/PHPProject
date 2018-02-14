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
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //something posted
  
            //View order selected
            if (isset($_POST['viewOrder'])) {
                header("location: viewOrder.php");
                $_SESSION['viewOrder'] = $_POST['viewOrder'];
                
            }
            
        }
        
        ?>
        
        <center><h1>Your Orders</h1></center>
        
        <div class="subTabs">
        <div class="w3-bar w3-border">
                    <a href="MyAccount.php" class="w3-bar-item w3-button">My Account</a>
                    <a href="orders.php" class="w3-bar-item w3-button w3-light-grey">My Orders</a>
        </div>
       </div>

        <img src="https://cdn.shopify.com/s/files/1/0207/4676/t/13/assets/new_cart_icon.png?16453297725671707473" onclick="location.href = 'basket.php'" alt="basket" id="basket">
        <label id="basketNum"><?php echo array_sum($_SESSION['cartQunatity']); ?></label>
        
        <?php
        //Get all orders of logged in user
        $loop =  $stmt = $db->prepare("SELECT SalesID, TotalItems, Total FROM Sales_Order Where CUSTOMERID=?")
                or die(mysql_error());
        $stmt->bind_param("i", $user_session["CUSTOMERID"]);
        
        $stmt->execute();

        $stmt->store_result();
        $stmt->bind_result($salesID, $totalItems, $total);
        
        echo "<center><table>";
        echo "<th>Order ID</th><th>Total Items</th><th>Total</th>";

        //Display data in table
        while($stmt->fetch())
        {
            echo "<tr>";
            echo "<td><center>" . $salesID . "</center></td><td><center>" . $totalItems . "</center></td><td> <center>£" . $total . "&nbsp;&nbsp;&nbsp;&nbsp;</center></td>";
            echo "<td><center>" . "<form action='' method='post'>" . "<button type='submit' name='viewOrder' value='$salesID' class='w3-button w3-border w3-light-grey'>View Order</button>" . "</center></td></form>";
            echo "</tr>";
        }
        
        $stmt->close();

        echo "</table></center><br><br>";
        ?>

    </body>
    
</html>

