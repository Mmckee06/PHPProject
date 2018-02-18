<html>

    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Admin</title>

    <meta charset="UTF-8">

    <style>
        body {
            background-image: url("https://don16obqbay2c.cloudfront.net/wp-content/themes/ecwid/images/blocks/store-background.jpg");
            background-color: #F8E5C0;
            
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
        
        //Check user is admin
        if ($user_session["user_Type"] != 1){
            header("location: LoginSuccess.php");
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
                    <a href="admin.php" class="w3-bar-item w3-button w3-light-grey">Users</a>
                    <a href="admin2.php" class="w3-bar-item w3-button">Store</a>
        </div>
       </div>
        
        <br>
        <?php
        //Form Submited
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //edit user clicked
            if (isset($_POST['editUser'])) {
                //User brought to user edit page
                header("location: editUser.php");
                //Session which holds edit user details
                $_SESSION['editUser'] = $_POST['editUser'];
            } else if (isset($_POST['deleteUser'])) {
                //Delete user clicked
                $value2 = $_POST['deleteUser'];
                //Delete user from database
                $stmt = $db->prepare("DELETE FROM CUSTOMER WHERE USERNAME=?");
                    $stmt->bind_param("s",$value2);
                    $stmt->execute();
                    $stmt->close();
                    header("Location: admin.php");
            }
        }
        
        //Rretrieve all customers from database and display in table
        $loop = mysqli_query($db, "SELECT * FROM CUSTOMER")
                or die(mysql_error());
        echo "<table>";
        echo "<th>Customer ID</th><th>Username</th><th>FirstName</th><th>LastName</th><th>Address Line 1</th><th>Address Line 2</th><th>City</th><th>POSTCODE</th><th>Phone</th><th>Admin</th><th></th>";


        while ($row = mysqli_fetch_array($loop, MYSQLI_ASSOC)) {
            echo "<tr>";
            echo "<td><center>" . $row['CUSTOMERID'] . "</center></td><td><center>" . $row['USERNAME'] . "</center></td><td> <center>" . $row['FIRSTNAME'] . "</center></td><td><center>" . $row['LASTNAME'] . "</center></td>"
                    . "<td><center>" . $row['AddressLine1'] . "</center></td><td><center>" . $row['AddressLine2'] . "</center></td><td><center>" . $row['CITY'] . "</center></td><td><center>" . $row['POSTALCODE'] . "</center></td><td><center>" . $row['PHONE'] . "</center></td>"
                    . "<td><center>" . $row['user_Type'] . "</center></td>";
            echo "<td><center>" . "<form action='' method='post'>" . "<button type='submit' name='editUser' value='{$row['USERNAME']}' class='w3-button w3-border w3-light-grey'>Edit</button>" . "</center></td></form>";
            echo "<td><center>" . "<form action='' method='post'>" . "<button type='submit' name='deleteUser' value='{$row['USERNAME']}' class='w3-button w3-border w3-light-grey'>Delete</button>" . "</center></td></form>";
            echo "</tr>";
        }

        echo "</table><br><br>";
        ?>
        
    </body>

</html>

