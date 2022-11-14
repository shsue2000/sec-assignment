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
			<a href="../client/login.php">Login</a>
			';
	}
?>
                
                <span>|</span>
<?php
	if(!isset($_SESSION['loggedin'])) {
			echo'
			<a href="../client/register.php">Register</a>
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
	<div class="mainHeadingDiv">
            <label class="mainHeading">Register</label>
            </div>

		<form class="needs-validation" method="post" action="../server/register.php"  onsubmit="return validateBeforeRegister()">
			<hr class="my-4" />

			<label class="inputTitle" style="color:red" id="registrationError"></label>

			<div class="form-group row pt-3">
			<div class="inputDiv">
				<label class="inputTitle" for="email" class="col-md-2 col-form-label">Email</label>
				<label id="emailError" style="color:red; display:none" ><i class="fa fa-user"></i> Please make sure you enter a valid email</label>
			</div>
				<div class="col-md-10">
					<input type="text" class="form-control" id="email" name="email" placeholder="Email">

				</div>
			</div>

			<div class="form-group row pt-3">
			<div class="inputDiv">
				<label class="inputTitle" for="password" class="col-md-2 col-form-label">Password</label><br>
				<label style="font-size: 14px;">The password must be longer than 6 characters</label>
				<label id="passwordError" style="color:red; display:none" ><i class="fa fa-user"></i> Please make sure you enter a password that matches the requirements</label>
				
			</div>
				<div class="col-md-10">
					<input type="password" class="form-control" id="password" name="password" placeholder="Password">
					<br/>
					
				</div>
			</div>

			<div class="form-group row pt-3">
			<div class="inputDiv">
				<label class="inputTitle" for="name" class="col-md-2 col-form-label">Name</label>
				<label id="nameError" style="color:red; display:none" ><i class="fa fa-user"></i> Please make sure you enter a real name</label>
				
				</div>
				<div class="col-md-10">
					<input type="text" class="form-control" id="name1" name="name" placeholder="Name" >

					<div id="nameFeedback" class="validate-error text-danger"></div>

	
				</div>
			</div>


			<br>
				<div class="inputDiv">
					<button id="submit" class="addToCartBtn" style="margin-top: 0%; font-size: 1.2vw;cursor: pointer;" type="submit">Register</button>
					
				</div>

<script src="sha256.js"></script>
<script>

	//A hash function that user SHA256 to hash the password.
	function hash() {
		var password = document.getElementById('password').value;
		var hash = SHA256.hash(password);

		document.getElementById('password').innerHTML = hash;
		document.getElementById('password').value = hash;
	}


	//validation functions

	function validateEmail(id) {
		var regex = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
		var email =  document.getElementById(id);
		return regex.test(email.value);
	}


	function validateName(id) {
		var regex = /^[a-zA-Z ]{2,}$/;
		var name =  document.getElementById(id);
		return regex.test(name.value);
	}


	function validatePassword(id) {
		var pass = document.getElementById(id).value;

		if(pass.length >= 6){
			return true;
		}else{
			return false;
		}

	}

	//The main validation function that is called before submitting the form.
	function validateBeforeRegister(){

		var emailVal = validateEmail("email");
		var passVal = validatePassword("password");
		var nameVal = validateName("name1");
		
		//if an input is not valid, the below if statements decides whether
		//to show the error message or not.
		if(!emailVal){
			document.getElementById("emailError").style.display = "block";
		}else{
			document.getElementById("emailError").style.display = "none";
		}
		if(!passVal){
			document.getElementById("passwordError").style.display = "block";
		}else{
			document.getElementById("passwordError").style.display = "none";
		}
		if(!nameVal){
			document.getElementById("nameError").style.display = "block";
		}else{
			document.getElementById("nameError").style.display = "none";
		}

		//if all the input is correct, then hash the password and send the
		//data to the backend/server
		if((emailVal && passVal && nameVal)){
			hash();
			return true;
		}
		
		return false;
		

	}
	//This function is called as soon as the page loads to check if there
	//is an error message received from the server or not.
	//Errors such as "User exists".
	function registrationError(){
		// authError
		const queryString = window.location.search;
		const urlParams = new URLSearchParams(queryString);
		var message = urlParams.get('m');
		if(message != null){
			document.getElementById('registrationError').innerHTML = message;
		}
		
	}
	//running the registrationError function as soon
	//as the page loads.
	window.onload = function() {
		registrationError();
	};

</script>
		</form>

		<div style="margin-top: 0.5%">
			<p class="mt-2">Already have an account? <a class="registerLink" href="login.php">Login here</a></p>
		</div>
	</div>
</div>

</body>
</html>
