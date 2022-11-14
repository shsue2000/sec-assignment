<?php session_start(); ?>

<html>
<head>
    <title>MTS Melbourne tech solution</title>
    <meta name="description" content="Best and cheapest tech support you have in Melbourne" />
    <meta name="keywords" content="Tech, support, Melbourne" />
    <!--default-->
    <link rel="stylesheet" type="text/css" href="../assets/css/reset.css">
    <!--common style css-->
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <!--index page css-->
    <link rel="stylesheet" type="text/css" href="../assets/css/index.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/shoppingCart.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="../assets/css/login.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="../assets/css/invoice.css?v=<?php echo time(); ?>">
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


<div style="text-align:center; margin-top: 1%">
    <label class="cartTitles" style="font-size: 45px;">Thank you for your purchase!</label>
    <br>
    <label class="cartTitles" style="font-size: 25px;">This is your invoice</label>
    
    </div>
    <br>
    <table id="customers">
  <tr>
    
    <th>Order Details</th>
    <th></th>

  </tr>
  <tr>
    <td>Email</td>
    <td id="email"></td>

  </tr>
  <tr>
    <td>Address</td>
    <td id="address"></td>
 
  </tr>
  <tr>
    <td>City</td>
    <td id="city"></td>
 
  </tr>
  <tr>
    <td>State</td>
    <td id="state"></td>
 
  </tr>
  <tr>
    <td>Zip Code</td>
    <td id="zipCode"></td>
 
  </tr>

  <tr style="text-align:left">
    <td>Products</td>
    <td style="text-align: left" id="products"></td>
 
  </tr>

  <tr>
    <td>Total Paid</td>
    <td id="total"></td>
 
  </tr>


  
  
</table>

<script src="rsa.js"></script>
<script type="text/javascript" src="des.js"></script>
<script>
//Decrypting data using DEC and the session key.
function DES_decryption() {
		
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    //getting the stored session key
    var key = sessionStorage.getItem("sessionKey");
    //getting the received data
    var email = urlParams.get('email');
    var address = urlParams.get('address');
    var city = urlParams.get('city');
    var state = urlParams.get('state');
    var zipCode = urlParams.get('zipCode');
    var total = urlParams.get('total');
    var num = urlParams.get('num');

    var products = urlParams.get('p');

    
    
    //decrypting the received data
    email = javascript_des_decryption(key,email);
    address = javascript_des_decryption(key,address);
    city = javascript_des_decryption(key,city);
    state = javascript_des_decryption(key,state);
    zipCode = javascript_des_decryption(key,zipCode);
    total = javascript_des_decryption(key,total);
    num = javascript_des_decryption(key,num);
    
    products = javascript_des_decryption(key,products);


    //processing the products to be shown clearly for the user.
    var count = 0;
    var currentString = "";
    for(var i = 0; i < products.length; i++){
      
      if(products[i] == ","){
        count += 1;
        currentString += " ";

        if(count == 3){
          //reset
          count = 0;
          currentString += "<br>";
        }

      }else{
        if(count == 1 && products[i] == " "){
          //finished adding the name,
          currentString += "x";
          
        }else if(count == 2 && products[i] == " "){
          currentString += "$";
        }else{
          currentString += products[i];
        }
      }

    }
    
    //showing the data to the user.
    document.getElementById("email").innerHTML = email;
    document.getElementById("address").innerHTML = address;
    document.getElementById("city").innerHTML = city;
    document.getElementById("state").innerHTML = state;
    document.getElementById("zipCode").innerHTML = zipCode;
    document.getElementById("total").innerHTML = total;
    document.getElementById("products").innerHTML = "<p>"+currentString+"</p>";
    
    
    
  }
//running the decryption function as soon as the page loads.
window.onload = function() {
  DES_decryption();
};


</script>




</body>
</html>
