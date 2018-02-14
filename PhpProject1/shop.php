<html>

    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Shop</title>

    <meta charset="UTF-8">

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

            height: 240; 
            width: 240;
        }

        table {
            padding-left: 100;
            padding-right: 100;
            margin-left: 150;
            margin-right: 120;
            border: solid 1px #080808;
            background-color: #F8F4EB;


        }

        td {
            padding-right: 20;
            padding-left: 20;
            padding-top: 15;
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

        #plusqty{
            width: 20px;
            height: 20px;
            padding-left: 5px;
        }

        #minusqty {
            width: 20px;
            height: 20px;
            padding-right: 5px;
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
        //run the query to get all items in store
        $i = 1;
        $loop = mysqli_query($db, "SELECT * FROM Store")
                or die(mysql_error());
        echo "<table>";

        while ($row = mysqli_fetch_array($loop, MYSQLI_ASSOC)) {

            if ($i == 1) {
                echo "<tr>";
            }

            echo "<td>" . "<img src=\"{$row['ItemImage']}\" />    " . "<br><br><center>" . $row['ItemName'] . "</center> <center><br>£" . $row['Price'] . "</center> ";
            if ($row['Quantity'] >= 1) {
                echo "<br><center>" . "<form action='' method='post'>" . "<button type='submit' name='addItem' value='{$row['ItemName']}' class='w3-button w3-border w3-light-grey' >Add to basket </button></form>" . "</center><br></td>";
            } else {
                echo "<br><center>" . "<h4>Currently out of stock</h4>" . "</center><br></td>";
            }

            if ($i == 3) {
                $i = 1;
                echo "</tr>";
            } else {
                $i++;
            }
        }

        echo "</table>";



        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //something posted
            $value1 = $_POST['addItem'];

            if (isset($_POST['addItem'])) {
                //echo '<script type="text/javascript">alert("Data has been submitted to ' . $value1 . '");</script>';
                //Add item to cart array
                $arrlength = count($_SESSION['cart']);
                $check = false;

                if ($arrlength === 0) {
                    //basket empty add first item to it
                    $_SESSION['cart'][$arrlength] = $value1;
                    $_SESSION['cartQunatity'][$arrlength] = 1;
                } else {
                    for ($x = 0; $x < $arrlength; $x++) {
                        //item already in basket add to quantity of item in quantity array
                        if ($_SESSION['cart'][$x] === $value1) {
                            $_SESSION['cartQunatity'][$x] ++;
                            $check = true;
                            break;
                        }
                    }
                    if ($check === false) {
                        $_SESSION['cart'][$arrlength] = $value1;
                        $_SESSION['cartQunatity'][$arrlength] = 1;
                    }
                }
            } else {
                //assume btnSubmit
            }
        }
        ?>

        <img src="https://cdn.shopify.com/s/files/1/0207/4676/t/13/assets/new_cart_icon.png?16453297725671707473" onclick="location.href = 'basket.php'" alt="basket" id="basket">
        <label id="basketNum"><?php echo array_sum($_SESSION['cartQunatity']); ?></label>

    </body>

</html>

