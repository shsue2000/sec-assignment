<?php 
//process the actions to make on the shopping cart

    session_start();
    include('../Utils/des.php');


    if(!empty($_GET['action'])) {
        //if we recived a get request that is not empty.

        //decrypting the action and the id
        $action = php_des_decryption($_SESSION['sessionKey'], $_GET['action']);
        $id = intval(php_des_decryption($_SESSION['sessionKey'], $_GET['id']));
        
        //removing the white spaces if there is any.
        $action = trim((string)$action);
       
        //if the action is to add a new product to the shopping cart
        if(strcmp($action, "add") == 0){
            $qty = intval(trim(php_des_decryption($_SESSION['sessionKey'], $_GET['qty'])));

                if(!empty($qty)) {
                    $products = json_decode(file_get_contents('../database/products.txt'), true);
                    
                    $productId = array_search($id, array_column($products, 'id'));
  

                    $productItem = array(
                        
                        'id' => $products[$productId]['id'],
                        'name' => $products[$productId]['productName'],
                        'quantity' => $qty,
                        'price' => $products[$productId]['price'],
                        'imageName' => $products[$productId]['imageName'],
                        
                    );
                    
                    
                    
                    if(!empty($_SESSION['shopping_cart'])) {
                        if(in_array($products[$productId]['id'], array_keys($_SESSION['shopping_cart']))) {
                            foreach($_SESSION['shopping_cart'] as $key => $value) {
                                if($products[$productId]['id'] == $key) {
                                    if(empty($_SESSION['shopping_cart'][$key]['quantity'])) {
                                        $_SESSION['shopping_cart'][$key]['quantity'] = 0;
                                    }
                                
                                    $_SESSION['shopping_cart'][$key]['quantity'] += $qty;
                                }
                            }
                        } else {

                            $_SESSION['shopping_cart'][$productItem['id']] = $productItem;
                        }
                    } else {

                        $_SESSION['shopping_cart'] = array();
                        $_SESSION['shopping_cart'][$productItem['id']] = $productItem;

                    }
                }
                
            }
            
            //if the action is to update (add 1) the quantity of a product in the shopping cart
            else if(strcmp($action, "addQuantity") == 0){
                
                if(!empty($_SESSION['shopping_cart'])) {
                    foreach($_SESSION['shopping_cart'] as $key => $value) {
                        if($id == $key) {
                            $_SESSION['shopping_cart'][$key]['quantity'] += 1;
                        }
                    }
                }
                
            }
            //if the action is to update (remove 1) the quantity of a product in the shopping cart
            else if(strcmp($action, "removeQuantity") == 0){
            
                if(!empty($_SESSION['shopping_cart'])) {
                    foreach($_SESSION['shopping_cart'] as $key => $value) {
                        if($id == $key) {
                            if($_SESSION['shopping_cart'][$key]['quantity'] == 1) {
                                unset($_SESSION['shopping_cart'][$id]);
                            } else {
                                $_SESSION['shopping_cart'][$key]['quantity'] -= 1;
                            }
                        }
                    }
                }
                
            }
            //if the action is to remove the entire product from the shopping cart
            else if(strcmp($action, "rm") == 0){
                if(!empty($_SESSION['shopping_cart'])) {
                    foreach($_SESSION['shopping_cart'] as $key => $value) {
                        if($id == $key) {
                            
                            unset($_SESSION['shopping_cart'][$id]);

                        }
                    }
                }

        }
        
    }
    //after processing the shopping cart request, then the user is taken back
    //to the shopping cart page again with the update values.
    //the backend/server keeps track of all the data.
    header("Location: ../client/shopping-cart.php");
?>