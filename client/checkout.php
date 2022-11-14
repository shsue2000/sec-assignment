<?php 

   
session_start();

//if the shopping cart is empty (meaning either user deleted everything in the cart or has completed the payment)
//and the user clicks on the back button, then the user will be taken to the Home page instead of going back to an empty
//page.
if(count($_SESSION['shopping_cart']) == 0){
    header("Location: ../client/index.php");
}
?>


<!DOCTYPE html>
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
    <link rel="stylesheet" type="text/css" href="../assets/css/checkout.css?v=<?php echo time(); ?>">
    <!--Title icon-->
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/mts.ico">
</head>

<body>
<div style="text-align:center; font-weight:bold; font-size: 35px; margin-bottom: 1%; margin-top:2%">
<label>Payment Page</label>
</div>
<div class="row">
  <div class="col-75">
    <div class="container">
      <form id="form" action="../server/PaymentProcessing.php" onsubmit="return validateBeforePay()" method="POST">
      
        <div class="row">
          <div class="col-50">
            <h3>Billing Address</h3>
            <label for="fname"><i class="fa fa-user"></i> Full Name</label>
            <label id="nameError" style="color:red; display:none" for="fname"><i class="fa fa-user"></i> Please make sure you enter your full name</label>
            <input type="text" id="fname" name="fname" placeholder="XXXX XXXX">
            
            
            <label for="email"><i class="fa fa-envelope"></i> Email</label>
            <label id="emailError" style="color:red; display:none" for="email"><i class="fa fa-user"></i> Please make sure you enter a valid email</label>
            <input type="text" id="email" name="email" placeholder="XXXX@XXXXX.XXX">
            
            <label for="adr"><i class="fa fa-address-card-o"></i> Address</label>
            <label id="addressError" style="color:red; display:none" ><i class="fa fa-user"></i> Please make sure you enter a valid address</label>
            <input type="text" id="adress" name="address" placeholder="XXX XXXXXX">
            
            <label for="city"><i class="fa fa-institution"></i> City</label>
            <label id="cityError" style="color:red; display:none" ><i class="fa fa-user"></i> Please make sure you enter a valid city</label>
            <input type="text" id="city" name="city" placeholder="XXXXXXXX">

            <div class="row">
              <div class="col-50">
                <label for="state">State</label>
                <label id="stateError" style="color:red; display:none" ><i class="fa fa-user"></i> Please make sure you enter a valid state</label>
                <input type="text" id="state" name="state" placeholder="XXX">
              </div>
              <div class="col-50">
                <label for="zip">Zip</label>
                <label id="zipCodeError" style="color:red; display:none" ><i class="fa fa-user"></i> Please make sure you enter a Australian valid zip code</label>
                <input type="text" id="zipCode" name="zipCode" placeholder="XXXX">
              </div>
            </div>
          </div>

          <div class="col-50">
            <h3>Payment</h3>

            <label for="cname">Name on Card</label>
            <label id="cnameError" style="color:red; display:none" for="fname"><i class="fa fa-user"></i> Please make sure you enter a valid name</label>
            <input type="text" id="cname" name="cardname" placeholder="XXXX XXXX">
            <label for="ccnum">Credit card number (Visa or MasterCard)</label>
            <label id="ccError" style="color:red; display:none" for="fname"><i class="fa fa-user"></i> Please make sure you enter a valid credit card number without spaces</label>
            <input type="text" id="ccnum" name="cardnumber" placeholder="XXXXXXXXXXXXX">
            
            <div style="display:flex; flex-direction:row; flex-wrap:wrap">
                <div class="flexItemDivs">
                    <label >Exp Date</label>
                    <label id="dateError" style="color:red; display:none" ><i class="fa fa-user"></i> Please make sure you enter a valid expiration date</label>
                        <div>
                            <input style="height:40%; width: 20%" type="text" id="expMonth" name="expMonth"  placeholder="XX">
                            <input style="height:40%; width: 40%" type="text" id="expYear" name="expYear"  placeholder="XXXX">
                        </div>
                </div>
                
                <div class="flexItemDivs">
                    <label for="cvv">CVV</label>
                    <label id="cvvError" style="color:red; display:none" for="fname"><i class="fa fa-user"></i> Please make sure you enter a valid CVV number</label>
                    <input style="height:40%; width: 30%" type="text" id="cvv" name="cvv" placeholder="XXX">
                  </div>
            </div>
            
            
            </div>
          </div>
          
        </div>
        
       
      
    </div>
  </div>
  <div class="col-25">
    <div class="container">
        <label class="cartTitles">Final Review</label>
        <!-- <h4>Cart <span class="price" style="color:black"><i class="fa fa-shopping-cart"></i> </span></h4> -->

        <div style="display:flex; flex-direction:row; align-items:center; text-align:center; margin-top:1%; margin-bottom:1%">
            <div style="width: 180px;">
            </div>

            <div style="width: 25%;">
                <label class="cartTitles">Product</label>
            </div>
            <div style="width: 25%;">
                <label class="cartTitles">Quantity</label>
            </div>


            <div style="width: 25%;">
                <label class="cartTitles">Total</label>
            </div>

        </div>

        <?php
        //all the code below is to show the products to be purchased in the 'Review' section of the page
    if(isset($_SESSION['shopping_cart'])) {
        $totalQuantity = 0;
        $totalAmount = 0;
        $_SESSION['order_confirm_products'] = $_SESSION['shopping_cart'];
        ?>
    <div style="display:flex; flex-direction:column;">
        <?php
        foreach($_SESSION['shopping_cart'] as $cartItem) {
            $totalPrice = $cartItem['quantity'] * $cartItem['price'];
            $totalQuantity += $cartItem['quantity'];
            $totalAmount += ($cartItem['quantity'] * $cartItem['price']);
        ?>


      <div style="display:flex; flex-direction:row; align-content:center; justify-content:center;">

        <div style="">
            <?php
                        echo '
                                <img style="object-fit: cover; width:80px; height:80px" src="../assets/img/' . $cartItem["imageName"] . '.webp">
                        ';
                ?>
        </div>

        <div class="itemDivs">
            <label> 
                <?php echo $cartItem['name']; ?>
            </label>
        </div>

        <div class="itemDivs">
            <label> 
                <?php echo $cartItem['quantity']; ?>
            </label>
        </div>


        <div class="itemDivs">
            <label class="productLabels">
            $<?php echo number_format($totalPrice, 2, '.', ','); ?>
            </label>
        </div>

      </div>

      <br>
      
        <?php
            //Adding the products in the POST request that will be sent to the backend/server to store it.
            echo '
                    <input type="hidden" name="' . $cartItem["id"] . '[]" value= "' . $cartItem["name"] . '/"/>
                    <input type="hidden" name="' . $cartItem["id"] . '[]" value= ' . $cartItem["quantity"] . '/>
                    <input type="hidden" name="' . $cartItem["id"] . '[]" value= ' . $totalPrice . '/>
                    
            ';

        ?>

      <?php
    }
}
      ?>
       </div>
      <hr>
        <p class="cartTitles">Total <span class="price" style="color:black"><b>$<?php echo number_format($totalAmount, 2, '.', ','); ?></b></span></p>
        
        <?php 
            echo '
                <input type="hidden" name="total" value= ' . number_format($totalAmount, 2, ".", ",") . '>
            '
        ?>

      <input type="submit" value="Pay" class="btn"><br><br>
      <div id="cancel"  onclick="cancel()">
        <a style="color: white;" href="shopping-cart.php">Cancel</a>
    </div>
    <!-- <button type = "button" onclick="validateBeforePay()">Check Validations</button> -->
    </div>
    <input type="hidden" name="timestamp" id="timestamp" value="11">


    </form>
  </div>


    

</div>
<script src="rsa.js"></script>

<script>

    function RSA_encryption(){

    
    var elements = document.getElementById("form").elements;
    var pubilc_key = "-----BEGIN PUBLIC KEY-----MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAzdxaei6bt/xIAhYsdFdW62CGTpRX+GXoZkzqvbf5oOxw4wKENjFX7LsqZXxdFfoRxEwH90zZHLHgsNFzXe3JqiRabIDcNZmKS2F0A7+Mwrx6K2fZ5b7E2fSLFbC7FsvL22mN0KNAp35tdADpl4lKqNFuF7NT22ZBp/X3ncod8cDvMb9tl0hiQ1hJv0H8My/31w+F+Cdat/9Ja5d1ztOOYIx1mZ2FD2m2M33/BgGY/BusUKqSk9W91Eh99+tHS5oTvE8CI8g7pvhQteqmVgBbJOa73eQhZfOQJ0aWQ5m2i0NUPcmwvGDzURXTKW+72UKDz671bE7YAch2H+U7UQeawwIDAQAB-----END PUBLIC KEY-----";
    // Encrypt with the public key...
    var encrypt = new JSEncrypt();
    
    //getting all the <input> tags and encrypting the values using RSA
    for(let i = 0; i < elements.length; i++){
        
        if(elements[i].name != "" && elements[i].name != "timestamp"){

            //encrypting the data

            encrypt.setPublicKey(pubilc_key);
            var encrypted = encrypt.encrypt(elements[i].value);
            
            //Updating the value in the <input> tage
            elements[i].value = encrypted;


        }else if (elements[i].name == "timestamp"){

            //encrypting the timestamp

            var timestamp = String(Math.floor(new Date().getTime() / 1000));
            elements[i].innerHTML = timestamp;
            elements[i].value = timestamp;
            var ecnTimestamp = encrypt.encrypt(elements[i].value);
            //Updating the value in the <input> tage
            elements[i].value = ecnTimestamp;
        }
        
    }

    }




    function cancel(){
        //when the user clicks on cancel.
        location.href = "shopping-cart.php";
    }
    function validateBeforePay(){

        //a validation function that calls other functions to know if
        //the input that those smaller functions are assigned to is 
        //correct or not. (Returns a boolean)

        var nameVal = validateName("fname");
        var emailVal = validateEmail("email");
        var addressVal = validateAddress("adress");
        var cityVal = validateCity("city");
        var stateVal = validateCity("state");
        var zipVal = validateZipCode("zipCode")
        var cnameVal = validateName("cname");
        var cardVal = validateCreditCard("ccnum");
        var cvvVal = validateCVV("cvv");
        var dateMonthVal = validateMonth("expMonth");
        var dateYearVal = validateYear("expYear");
        
        //Checking if the input is valid or not. If NOT VALID then show the error message
        //if VALID then don't show the message just hide it.
        if(!nameVal){
            document.getElementById("nameError").style.display = "block";
        }else{
            document.getElementById("nameError").style.display = "none";
        }
        if(!cnameVal){
            document.getElementById("cnameError").style.display = "block";
        }else{
            document.getElementById("cnameError").style.display = "none";
        }

        if(!emailVal){
            document.getElementById("emailError").style.display = "block";
        }else{
            document.getElementById("emailError").style.display = "none";
        }

        if(!addressVal){
            document.getElementById("addressError").style.display = "block";
        }else{
            document.getElementById("addressError").style.display = "none";
        }

        if(!cityVal){
            document.getElementById("cityError").style.display = "block";
        }else{
            document.getElementById("cityError").style.display = "none";
        }

        if(!stateVal){
            document.getElementById("stateError").style.display = "block";
        }else{
            document.getElementById("stateError").style.display = "none";
        }
        if(!zipVal){
            document.getElementById("zipCodeError").style.display = "block";
        }else{
            document.getElementById("zipCodeError").style.display = "none";
        }
        if(!cardVal){
            document.getElementById("ccError").style.display = "block";
        }else{
            document.getElementById("ccError").style.display = "none";
        }
        if(!cvvVal){
            document.getElementById("cvvError").style.display = "block";
        }else{
            document.getElementById("cvvError").style.display = "none";
        }
        if(!dateMonthVal || !dateYearVal){
            document.getElementById("dateError").style.display = "block";
        }else if(!dateMonthVal && !dateYearVal){
            document.getElementById("dateError").style.display = "block";
        }
        else{
            document.getElementById("dateError").style.display = "none";
        }

        //if all input is correct, then ecnrypt and send to the backend/server
        if((nameVal && emailVal && addressVal && cityVal && stateVal && zipVal && cnameVal && cardVal && cvvVal && dateYearVal && dateMonthVal)){
            RSA_encryption();
            return true;
        }

        return false;
        
      
    }

    //below are functions that are used in the above function to check the inputs.
    //Some of them use regular expressions and some don't.

    function validateName(id) {
        var regex = /^[a-zA-Z ]{2,}$/;
        var name =  document.getElementById(id);
        return regex.test(name.value);
    }

    function validateEmail(id) {
        var regex = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
        var email =  document.getElementById(id);
        return regex.test(email.value);
    }

    function validateAddress(id) {
        
        var regex = /^\s*\S+(?:\s+\S+){1}/;
        var address =  document.getElementById(id);
        return regex.test(address.value);
        
    }

    function validateCity(id) {
        
        var regex = /^[a-zA-Z',.\s-]{2,}$/;
        var city =  document.getElementById(id);
        return regex.test(city.value);
        
    }

    
    
    function validateZipCode(id) {
        
        var regex = /^(?:(?:[2-8]\d|9[0-7]|0?[28]|0?9(?=09))(?:\d{2}))$/;
        var zip =  document.getElementById(id);
        return regex.test(zip.value);
        
    }

    
    function validateCreditCard(id) {
        
        var regex = /^((?:4[0-9]{12}(?:[0-9]{3})?)|(?:5[1-5][0-9]{2}|222[1-9]|22[3-9][0-9]|2[3-6][0-9]{2}|27[01][0-9]|2720)[0-9]{12})$/;
        var card =  document.getElementById(id);
        return regex.test(card.value);
        
    }

    
    function validateCVV(id) {
        
        var regex = /^[0-9]{3,4}$/;
        var cvv =  document.getElementById(id);
        return regex.test(cvv.value);
    }

    function validateYear(id){
        var value = document.getElementById(id).value;
       if(!isNaN(value) && parseInt(Number(value)) == value){

            if(parseInt(value) >= new Date().getFullYear()){
                return true
            }else{
                return false;
            }

       }else{
           return false;
       }
    }

    function validateMonth(id){
        var value = document.getElementById(id).value;
        var year = document.getElementById("expYear").value;
        if(!isNaN(value) && parseInt(Number(value)) == value){
            if(parseInt(value) > 0 && parseInt(value) <= 12){

                if(parseInt(year) == new Date().getFullYear()){
 
                    if(value > (new Date().getMonth()+1)){
                        return true;
                    }else{
                        return false;
                    }

                }else{
                    return true;
                }
            }else{
                return false;
            }
            
           
            
        }else{
            
            return false;
        }
        
    }


    </script>

</body>
</html>