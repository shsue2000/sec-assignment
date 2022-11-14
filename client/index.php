<?php session_start(); ?>

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


<!--Navigator-->


    <!-- <div class="container"> -->



                <?php
                //reading the data
                $products = json_decode(file_get_contents('../database/products.txt'), true);

                echo
                '
                <div id="products">
            '
                ;

                // Display all products
                for ($productsRow = 0; $productsRow < count($products); $productsRow++) {
                    echo
                        '
                    <div class="product-card" id="' . $products[$productsRow]['id'] . '" >
                    
                        <img class="productImage" src="../assets/img/' . $products[$productsRow]['imageName'] . '.webp" alt="' . $products[$productsRow]['productName'] . '" />
                          
                        <div class="productInfo">
                            <label style="font-size:16px;">' . $products[$productsRow]['productName'] . '</label> <br>
                        
                            <label style="font-size:16px; margin-top:2%"><b>Price:</b><label> $ <label>' . number_format($products[$productsRow]['price'], '2', '.', ',') . '</label>
                            </div>

                        
                '
                    ;

                    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                        echo
                            '
                                <form id="addToCartForm" action="../server/shoppingCartProcessing.php?action=add&id=' . $products[$productsRow]['id'] . '" method="post">
                                    <div>
                                        <div>   
                                            <input id="productQuantities'.$products[$productsRow]['id'].'" class="productQuantities" type="number" name="productQuantities" min="1" value="1" required onchange="changeQty(this.value,'.$products[$productsRow]['id'].')">
                                        </div>

                                      
                                    </div>
                                </form>
                            
                            <div>   
                                <button class="addToCartBtn" type="button" onclick="return addToCart('. $products[$productsRow]['id'] . ')" style="cursor: pointer;">Add to cart</button>
                            </div>
                            
                               
                    '
                        ;

                    } else {
                        echo
                        '
                        <br>
                                <a class="registerLink" href="../client/login.php">Login to purchase</a>
                    '
                        ;
                    }

                    echo
                    '
                            
                            

                    </div>
                '
                    ;
                }

                echo
                '
                </div>
                </div>
                
            '
                ;

                ?>


<script src="des.js"></script>
<script>

//a function to keep track of the value of the item quantity to be added.
function changeQty(newValue, id){
    document.getElementById(id).value = newValue;

}

//function used to add items to the cart
function addToCart(id){
    //getting the session key
    var key = sessionStorage.getItem("sessionKey");
    //encrypting the action
    var action = javascript_des_encryption(key, "add");
    //encrypting the product id to have the change be done to.
    var newId = javascript_des_encryption(key, id.toString());
    //getting the quantity and encrypting it
    var qty = document.getElementById(id).value;
    if(qty == undefined || qty == null){
        qty = '1';
    }
    if(qty > 0){
        qty = javascript_des_encryption(key, qty);
    
        window.location.href = "../server/shoppingCartProcessing.php?action="+action+"&id="+newId+"&qty="+qty;
    }

}


    </script>

</body>
</html>
