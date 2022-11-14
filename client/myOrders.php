<?php session_start(); 

//if user not logged in then take the user
//to the Home page.
if(!isset($_SESSION['loggedin'])){
    header("Location: ../client/index.php");
}

?>

<html>
<head>
    <meta charset="utf-8">
    <title>MTS Melbourne tech solution</title>
    <meta name="description" content="Best and cheapest tech support you have in Melbourne" />
    <meta name="keywords" content="Tech, support, Melbourne" />
    <!--default-->
    <link rel="stylesheet" type="text/css" href="../assets/css/reset.css?v=<?php echo time(); ?>">
    <!--common style css-->
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css?v=<?php echo time(); ?>">
    <!--index page css-->
    <link rel="stylesheet" type="text/css" href="../assets/css/index.css?v=<?php echo time(); ?>">

    <link rel="stylesheet" type="text/css" href="../assets/css/login.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="../assets/css/myOrders.css?v=<?php echo time(); ?>">
    
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

<div style="text-align: center; margin-bottom:1%; margin-top:2%">
                <label id="message" class="mainHeading" style="font-weight: bold;"></label>
</div>

<div id="ordersTableDiv">



<table id="ordersTable" style="width:100%">



</table>
</div>



<script type="text/javascript" src="des.js"></script>
<script>

//Decrypting useing DES and using the session key to show the orders.
function DES_decryption() {
		
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    var key = sessionStorage.getItem("sessionKey");
  
    var dt = urlParams.get("data");
    var message = urlParams.get("m");
  
  
    if(dt != null){
      //if data is received then decrypt it using the session key.
        dt = javascript_des_decryption(key,dt);
    }
    //decrypt the message sent (i.e. No orders made..)
    message = javascript_des_decryption(key,message);

    if(dt != null){
      //if data is received then it is processed first.
            
      var count = 0;
      var currentString = "";
      var productsStartIndex = 0;
      var productKey = 1;

      data = {};
      var email = "";
      var name = "";
      var address = "";
      var city = "";
      var state = "";
      var zipCode = "";
      for(var i = 0; i < dt.length; i++){
        
        if(dt[i] == ","){
          count += 1;

          if(count == 1){
              email = currentString;
              currentString = "";
          }else if(count == 2){
              name = currentString;
              currentString = "";
          }else if(count == 3){
              address = currentString;
              currentString = "";
          }else if(count == 4){
              city = currentString;
              currentString = "";
          }else if(count == 5){
              state = currentString;
              currentString = "";
          }
          else if(count == 6){
              zipCode = currentString;
              currentString = "";


              //reset
              productsStartIndex = i+1;
              var products = processProducts(dt, productsStartIndex);
              currentString += products[0];
              data[productKey] = [email, name, address, city, state, zipCode, currentString];
              i = products[1];
              productKey+=1;
              count = 0;

              email = "";
              name = "";
              address = "";
              city = "";
              state = "";
              zipCode = "";
              currentString = "";
          }


          }else{

          currentString += dt[i];
        }

      }
    }
    //data processing finished.


    if(dt != null){
      //if data is received and processed then add the data
      //in a table
      addTableData(data);
    }
    //show the message.
    document.getElementById('message').innerHTML = message;
    
  }

  //A helper function to process the products section ONLY of the orders
  function processProducts(dt, psi){
    var count = 0;
    var currentString = "";
    var returnIndex = 0;
    for(var i = psi; i < dt.length; i++){
      
      if(dt[i] == ","){
        count += 1;
        currentString += " ";
        if(count == 3){
          //reset
          count = 0;
          currentString += "<br>";
        }

      }else if(dt[i] + dt[i+1] == "()"){
        
        returnIndex = i+1;
        break;

      }else{
        if(count == 1 && dt[i] == " "){
          //finished adding the name,
          currentString += "x";
          
        }else if(count == 2 && dt[i] == " "){
          currentString += "$";
        }else{
          currentString += dt[i];
        }
        
      }

    }
    //return the products string and which index to continue from
    //in the main function.
    return [currentString, returnIndex];
  }

  //adding the orders table to show the products.
  function addTableData(data){

        var table = document.getElementById("ordersTable");

        var row = table.insertRow(0);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        var cell5 = row.insertCell(4);
        var cell6 = row.insertCell(5);
        var cell7 = row.insertCell(6);
        cell1.innerHTML = "Email";
        cell2.innerHTML = "Name";
        cell3.innerHTML = "Address";
        cell4.innerHTML = "City";
        cell5.innerHTML = "State";
        cell6.innerHTML = "Zip Code"
        cell7.innerHTML = "Products";

        cell1.className = "orderCellTitle";
        cell2.className = "orderCellTitle";
        cell3.className = "addressCellTitle";
        cell4.className = "orderCellTitle";
        cell5.className = "orderCellTitle";
        cell6.className = "orderCellTitle";
        cell7.className = "productsCellTitle";


        //looping through the data dictionary and adding the data to the table
        //created above.
        for (const [key, value] of Object.entries(data)) {

            row = table.insertRow(key);
            row.className = "productsRow";

            
            cell1 = row.insertCell(0);
            cell2 = row.insertCell(1);
            cell3 = row.insertCell(2);
            cell4 = row.insertCell(3);
            cell5 = row.insertCell(4);
            cell6 = row.insertCell(5);
            cell7 = row.insertCell(6);
            
            //adding the data to the table
            cell1.innerHTML = value[0];
            cell2.innerHTML = value[1];
            cell3.innerHTML = value[2];
            cell4.innerHTML = value[3];
            cell5.innerHTML = value[4];
            cell6.innerHTML = value[5];
            cell7.innerHTML = value[6];

            //giving the data a css class to handle
            //their style
            cell1.className = "orderCells";
            cell2.className = "orderCells";
            cell3.className = "addressCells";
            cell4.className = "orderCells";
            cell5.className = "orderCells";
            cell6.className = "orderCells";
            cell7.className = "productsCells";

        }
  }

//Run DES decyrption as soon as this page loads
//so that the data decrypted and shown to the user.
window.onload = function() {
  DES_decryption();
};


</script>


</body>
</html>
