<html>

    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <title>Welcome</title>

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
        
        img {
            width: 50px;
            height: 50px;
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
        //Chech user is logged in
        if (empty($_SESSION['login_user']) ) {
            header('Location:Homepage.php');
        }
        ?>

        <img src="https://cdn.shopify.com/s/files/1/0207/4676/t/13/assets/new_cart_icon.png?16453297725671707473" onclick="location.href = 'basket.php'" alt="basket" id="basket">
        <label id="basketNum"><?php echo array_sum($_SESSION['cartQunatity']); ?></label>
        
        <!-- Header -->
<header class="w3-container w3-center w3-padding-32 w3-border"> 
  <h1><b>Welcome <?php echo $user_session["USERNAME"]; ?></b></h1>
  <p><span class="w3-tag">Shop Online</span></p>

      <h5 class="w3-padding-32">Shop online for the very latest merchandise from the Premier League including the biggest teams Manchester United, Liverpool, Manchester City, Arsenal and Chelsea. Click the button below to access our store.</h5>
    
      <a href="Shop.php" class="w3-bar-item w3-button w3-light-grey">Shop Now</a>

   
   </header>  
        <br>

    </body>
</html> 