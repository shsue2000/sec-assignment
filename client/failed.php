<?php session_start(); ?>
<html>
<head>
    <meta charset="utf-8">
    <title>MTS Melbourne tech solution</title>
    <meta name="description" content="Best and cheapest tech support you have in Melbourne" />
    <meta name="keywords" content="Tech, support, Melbourne" />
    <!--default-->
    <link rel="stylesheet" type="text/css" href="../assets/css/reset.css">
    <!--common style css-->
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <!--index page css-->
    <link rel="stylesheet" type="text/css" href="../assets/css/index.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/login.css?v=<?php echo time(); ?>">
    <!--Title icon-->
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/mts.ico">
</head>
<body>
<div class="topnav">

<a class="brand" href="../client/">SEC-A3</a>
  
  <!-- Left-aligned links (default) -->
  <a href="../client/" style="margin-left: 5%">Home</a>
  <?php 
                if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                        
                        $numOfItems = 0;

                        if(isset($_SESSION['shopping_cart'])){
                            $numOfItems = count($_SESSION['shopping_cart']);
                        }
                    
                        echo'
                        <a href="shopping-cart.php">Cart (' . $numOfItems . ')</a>
                        ';
                }
?>

<?php
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        echo '<a href="../server/myOrdersProcessing.php">My Orders</a>';
    }
?>

  
  <!-- Right-aligned links -->
  <div class="topnav-right">
  <?php
        if(!isset($_SESSION['loggedin'])) {
                echo'
                <a href="login.php">Login</a>
                ';
        }
    ?>
                
                <span>|</span>
<?php
    if(!isset($_SESSION['loggedin'])) {
        echo'
        <a href="register.php">Register</a>
        ';
    }
?>


            <span>|</span>
<?php 
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
            echo'
                <a id="name" style="color:white;">Welcome, '. $_SESSION['name'] .'</a>
            
            ';
    }
?>

                <span>|</span>
<?php 
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
            echo'
            <a href="../server/logout.php">Logout</a>
            ';
    }
?>


  </div>
  
</div>

<script type="text/javascript" src="des.js"></script>

<div style="text-align: center; font-size: 40px; font-weight:bold">
    <label id="message"></label>
        <div>



<script>
    //using the DES decryption to decrypt the error message sent from the backend/server
function DES_decryption() {
		
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        
        var key = sessionStorage.getItem("sessionKey");
        var message = urlParams.get('message');

        message = javascript_des_decryption(key,message);
        document.getElementById("message").innerHTML = message;
        
      }
    
    window.onload = function() {
      DES_decryption();
    };

</script>


</body>
</html>
