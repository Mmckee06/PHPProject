<html>

    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Order Confirmation</title>

    <meta charset="UTF-8">

    <style>
        body {
            background-image: url("https://don16obqbay2c.cloudfront.net/wp-content/themes/ecwid/images/blocks/store-background.jpg");
            background-color: #F8E5C0;

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
        
        <header class="w3-container w3-center w3-padding-32 w3-border"> 
  <h1><b>Your order has been confirmed</b></h1>
  <p><span class="w3-tag">Your order is now being processed and will be with you shortly.</span></p>
  <p><span class="w3-tag">Thank you for shopping with us.</span></p>
      

   
   </header> 
        
    </body>

</html>

