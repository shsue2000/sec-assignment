<?php 

    session_start();

    if(!isset($_SESSION['loggedin'])){
        //ensuring that the user does not login multiple times.
        header("Location: ../client/index.php");
    }

?>
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

<?php
    //showing all the data stored in the shopping cart array
    if(isset($_SESSION['shopping_cart']) && count($_SESSION['shopping_cart']) > 0) {
        $totalQuantity = 0;
        $totalAmount = 0;
        $_SESSION['order_confirm_products'] = $_SESSION['shopping_cart'];
?>

<div id="shoppingCart">



        <div style="display:flex; flex-direction:row; align-items:center; text-align:center; margin-top:1%">
            <div style="width: 330px;">
            </div>

            <div style="width: 25%;">
                <label class="cartTitles">Product</label>
            </div>
            <div style="width: 25%;">
                <label class="cartTitles">Quantity</label>
            </div>

            <div style="width: 25%;">
                <label class="cartTitles">Price</label>
            </div>

            <div style="width: 25%;">
                <label class="cartTitles">Total</label>
            </div>
            <div style="width: 25%;">
                <label class="cartTitles"></label>
            </div>

        </div>

        <?php
        //looping through the items in the cart and showing them to the user
    foreach($_SESSION['shopping_cart'] as $cartItem) {
        $totalPrice = $cartItem['quantity'] * $cartItem['price'];
        $totalQuantity += $cartItem['quantity'];
        $totalAmount += ($cartItem['quantity'] * $cartItem['price']);
?>

        <div class="cartItem">

                <div >
                    <?php
                    echo '
                            <img class="cartProductImage" src="../assets/img/' . $cartItem["imageName"] . '.webp">
                    ';
                    ?>
                </div>
                
                <div class="itemDivs">
                    <label class="productLabels">
                        <?php echo $cartItem['name']; ?>
                    </label>
                </div>
                <div class="itemDivs">
                    <a class="addRemoveBtns" onclick="return remove1(<?php echo $cartItem['id']; ?>)" style="cursor: pointer;">-</a>
                    <label class="productLabels">
                        <?php echo $cartItem['quantity']; ?>
                    </label>
                    <a class="addRemoveBtns"  onclick="return add1(<?php echo $cartItem['id']; ?>)" style="cursor: pointer;">+</a> 
                </div>

                <div class="itemDivs">
                    <label class="productLabels">
                        $<?php echo number_format($cartItem['price'], 2, '.', ','); ?>
                    </label>
                </div>

                <div class="itemDivs">
                    <label class="productLabels">
                    $<?php echo number_format($totalPrice, 2, '.', ','); ?>
                    </label>
                </div>

                <div class="itemDivs">
                    <label class="productLabels">
                        <a onclick="return rm(<?php echo $cartItem['id'] ?>)" class="registerLink" style="cursor: pointer;">Remove Item</a>
                    </label>
                </div>

            </div>
            <?php
                        }
                    ?>
       

       <hr style="margin-top: 1%; margin-left:2%; margin-right: 2%">
            <div class="cartItem" style="margin-top: 1%">
                <div style="width: 290px;">
                        <label class="cartTitles">Total</label>
                    </div>
                <div style="width: 25%"></div>
                
                <div class="itemDivs" style="text-align:center; width:25%">
                    <label class="productLabels"><?php echo $totalQuantity; ?></label>
                </div>
                
                <div style="width: 25%"></div>
                <div class="itemDivs" style="width: 25%">
                    <label class="productLabels"> $<?php echo number_format($totalAmount, 2, '.', ','); ?></label>
                </div>
                <div style="width: 25%"></div>
            </div>

            <div class="cartItem" style="margin-top: 1%">
                <div style="width: 300px;">
                       
                    </div>
                <div style="width: 25%"></div>
                
                <div class="itemDivs" style="text-align:center">
                   
                </div>
                
                <div style="width: 25%"></div>
                <div class="itemDivs" style="width: 25%">
                    <a href="checkout.php" id="checkoutBtn">Checkout</a>
                </div>
                <div style="width: 25%"></div>
            </div>

    </div>



    <?php
            } else {
                //show this when the shopping cart is empty.
                echo '
                
                    <div style="width: 100%; text-align:center; font-size: 40px; margin-top:1%">

                    <label style=" font-weight:bold;">Your shopping cart is empty! </label>
                    <br>
                    <label style="font-size:30px">Please add products first</label>

                    </div>
                
                
                ';
            }
        ?>
<script src="des.js"></script>
<script>
//fucntions below are there to encrypt the action to be made on the
//cart products (+1, or -1, or remove the item entirely)
function rm(id){
    //getting the session key
    var key = sessionStorage.getItem("sessionKey");
    //encrypting the action
    var action = javascript_des_encryption(key, "rm");
    //encrypting the product id to have the change be done to.
    var newId = javascript_des_encryption(key, id.toString());
    window.location.href = "../server/shoppingCartProcessing.php?action="+action+"&id="+newId;
}

function add1(id){
    //getting the session key
    var key = sessionStorage.getItem("sessionKey");
    //encrypting the action
    var action = javascript_des_encryption(key, "addQuantity");
    //encrypting the product id to have the change be done to.
    var newId = javascript_des_encryption(key, id.toString());
    window.location.href = "../server/shoppingCartProcessing.php?action="+action+"&id=" + newId;

    
}

function remove1(id){
    //getting the session key
    var key = sessionStorage.getItem("sessionKey");
    //encrypting the action
    var action = javascript_des_encryption(key, "removeQuantity");
    //encrypting the product id to have the change be done to.
    var newId = javascript_des_encryption(key, id.toString());
    window.location.href = "../server/shoppingCartProcessing.php?action="+action+"&id=" + newId;
}

</script>


</body>

</html>
