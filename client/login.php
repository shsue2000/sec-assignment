<?php session_start(); 

if(isset($_SESSION['loggedin'])){
    //ensuring that the user does not login multiple times.
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
                    echo'
                    <a href="shopping-cart.php">Cart</a>
                    ';
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
                            <label>Welcome, '. $_SESSION['name'] .'</label>
                            <h1>HI</h1>
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


<!--Navigator-->


<div style="text-align:center">
<div class="container bg-gradient rounded">
    <div>
        <div class="mainHeadingDiv">
            <label class="mainHeading">Login</label>
            </div>
            
        <form method="post" action="../server/login.php" onsubmit="return validateBeforeLogin()">
        <hr class="my-4" />
        <label class="inputTitle" style="color:red" id="authError"></label>
            <div class="inputDiv">
                <label class="inputTitle" for="email">Email</label>
                <label id="emailError" style="color:red; display:none" ><i class="fa fa-user"></i> Please make sure you enter a valid email address</label>
                <div>
                    <input type="text" id="email" name="email" placeholder="Email">

                </div>
            </div>

            <div class="inputDiv">
                <label class="inputTitle" for="password">Password</label>
                <label id="passwordError" style="color:red; display:none" ><i class="fa fa-user"></i> Please make sure you enter a password</label>
                <div>
                    <input type="password" id="password" name="password" placeholder="Password">

                </div>
            </div>
                <div class="inputDiv">
                  
                    <button class="btn" id="submit" type="submit" style="cursor: pointer;">Login</button>
            </div>
                
                <input type="hidden" id="sessionKey" name="sessionKey">

<script src="rsa.js"></script>
<script src="sha256.js"></script>
<script>
    //Using RSA encryption to encrypt the session key along with the email and password
    //just to increase security.
    function RSA_encryption(toEncrypt){

        var pubilc_key = "-----BEGIN PUBLIC KEY-----MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAzdxaei6bt/xIAhYsdFdW62CGTpRX+GXoZkzqvbf5oOxw4wKENjFX7LsqZXxdFfoRxEwH90zZHLHgsNFzXe3JqiRabIDcNZmKS2F0A7+Mwrx6K2fZ5b7E2fSLFbC7FsvL22mN0KNAp35tdADpl4lKqNFuF7NT22ZBp/X3ncod8cDvMb9tl0hiQ1hJv0H8My/31w+F+Cdat/9Ja5d1ztOOYIx1mZ2FD2m2M33/BgGY/BusUKqSk9W91Eh99+tHS5oTvE8CI8g7pvhQteqmVgBbJOa73eQhZfOQJ0aWQ5m2i0NUPcmwvGDzURXTKW+72UKDz671bE7YAch2H+U7UQeawwIDAQAB-----END PUBLIC KEY-----";
        
        // Encrypt with the public key...
        var encrypt = new JSEncrypt();
        encrypt.setPublicKey(pubilc_key);
        var encrypted = encrypt.encrypt(toEncrypt);
        
        return encrypted;
    }

    //hashing the password using SHA256
    function hash() {
        var password = document.getElementById('password').value;

        if (password === "") {

        } else {
            var hash = SHA256.hash(password);

            document.getElementById('password').value = hash;
            return hash;
        }
    }

    //generating a random session key everytime the user logs in
    //so that a new session key is generated at the beginning of
    //every session (communication with the backend)
    function generateSessionKey(){
        //generating a random key string.
        var key = '_SEC_A3_' + Math.random().toString(36).substr(2, 9);
        //storing it in the session of the client to easily
        //access it later in encryption and decryption
        sessionStorage.setItem("sessionKey", key);
    }
    
    //function used to ensure that all input is correct.
    //once it is correct, the data will be encrypted and sent to the backend.
    function validateBeforeLogin(){

        //do all the input validations.

        var emailVal = validateEmail('email');
        var passwordVal = validatePassword('password');

        //if validation fails then show the error message. If it succeeds,
        //then don't show the error message
        if(!emailVal){
            document.getElementById("emailError").style.display = "block";
        }else{
            document.getElementById("emailError").style.display = "none";
        }
        if(!passwordVal){
            document.getElementById("passwordError").style.display = "block";
        }else{
            document.getElementById("passwordError").style.display = "none";
        }

        //if email and password validations are successful
        if((emailVal && passwordVal)){
            //encrypt if everything is okay.
            //encrypt: Email, Password, Session key.
            var email = document.getElementById("email").value;
            //hashing.
            var password = hash();

            //generating the session key.
            generateSessionKey()
            //getting the session key to encrypt it with RSA
            var sessionKey = sessionStorage.getItem("sessionKey");


            //ecnrypt the data with RSA.
            email = RSA_encryption(email);
            password = RSA_encryption(password);
            sessionKey = RSA_encryption(sessionKey);

            //adding the encrypted data in the input to ensure
            //it is transported in the POST request.
            document.getElementById("email").value = email;
            document.getElementById("password").value = password;
            document.getElementById("sessionKey").value = sessionKey;

            //send the data
            return true;
        }else{
            return false
        }

    }

    //small functions to validate the email and password
    function validateEmail(id) {
        var regex = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
        var email =  document.getElementById(id);
        return regex.test(email.value);
    }

    function validatePassword(id) {
        
        var password =  document.getElementById(id);
        return password.value.length > 0;
    }

    //if the authentication failed, then this function
    //displays the error message received from the backend.
    //error message not encrypted as stated in the discussion form.
    function authenticationError(){
        // authError
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        var message = urlParams.get('m');
        if(message != null){
            document.getElementById('authError').innerHTML = message;
        }
        
    }
    //as soon as this page loads check for any messages received
    //from the backend to see if an error message was received or not.
    window.onload = function() {
        authenticationError();
    };

</script>
        </form>
       

        <div style="margin-top: 0.5%">
            <a class="registerLink" href="register.php">Register here</a>
        </div>
    </div>
</div>
</div>

</body>
</html>
