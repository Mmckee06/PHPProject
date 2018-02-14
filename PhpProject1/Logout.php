<?php
   session_start(); //Ends session
   
   //Response Headers
        header('X-Frame-Options: SAMEORIGIN');
        header('Strict-Transport-Security: max-age=31536000');
        header_remove("X-Powered-By"); 
        
        session_regenerate_id(); // regenerate session cookie
   
   if(session_destroy()) {
      header("Location: Homepage.php");
   }
   
?>