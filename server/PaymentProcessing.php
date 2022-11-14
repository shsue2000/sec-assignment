<?php 

session_start();
include('rsa.php');
include('../Utils/des.php');

if(isset($_SESSION["shopping_cart"])){
    
    //if shopping cart is set.
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //if we received a POST request.

        $publicKey = get_rsa_publickey('public.key');
        $privateKey = get_rsa_privatekey('private.key');
        $decrypted = rsa_decryption($name, $privateKey);
        $data = array();
        $products = "";
        $urlGet = "";
        $numOfProducts = 0;

        foreach($_POST as $key => $value) {
            /// loop...
            $publicKey = get_rsa_publickey('public.key');
            $privateKey = get_rsa_privatekey('private.key');
            
            //checking if it has the id of the product.
            if(is_numeric($key) && (int)$key <100){
                //this means that we have a product with this style:
                // XXX,XXX,XXX,
                $numOfProducts = $numOfProducts + 1;
                $i = 0;
                $data[$key] = [];
                $currentP = "";
                foreach($_POST[$key] as $val){

                    //decrypting the shopping cart data using RSA
                    $publicKey = get_rsa_publickey('public.key');
                    $privateKey = get_rsa_privatekey('private.key');
                    $decData = rsa_decryption($val, $privateKey);
                    $data[$key][$i] = $decData;
                    $i = $i + 1;

                    //storing the product
                    $products .= (explode("/",$decData)[0]) . ", ";
                    
                }

            }else{
                    //decrypting the shopping cart data using RSA
                    $decryptedData = rsa_decryption($value, $privateKey);

                    $data[$key] = $decryptedData;


                    if($key == "cardnumber" || $key=="cvv" || $key=="cardname" || $key == "expMonth" || $key=="expYear" || $key == "plaintext"
                        || $key == "timestamp" || $key=="fname"){}
                    else{
                        //storing AND encrypting the data to be send to the frontend to show the invoice
                        //encrypting here is done using DES with the session key.
                        $encrypted = php_des_encryption($_SESSION["sessionKey"], $decryptedData);
                        $urlGet .=  $key . "=" . $encrypted . "&";
                    }
            }
            
        }

        //storing AND encrypting the data to be send to the frontend to show the invoice
        //encrypting here is done using DES with the session key.
        $encrypted = php_des_encryption($_SESSION['sessionKey'], $products);
        $urlGet .=  "p=". $encrypted;
        //data to be sent to the frontend.
        $urlGet .=  "&num=". php_des_encryption($_SESSION['sessionKey'], $numOfProducts);
        
        //getting the timestamp to check.
        $timestamp = time();
        if(($timestamp - (int)$data["timestamp"]) < 150){
            //if the data received is within the timeframe.

            $insertData = $_SESSION['email'] . ", " . $data["fname"] . ", " . $data["address"] .", ". $data["city"] .", ". $data["state"] .", ". $data["zipCode"] .", ". $data["cardname"] .", ". $data["cardnumber"].", ". $products;
            $database = fopen("../database/orders.txt", "a"); 

            fwrite($database, $insertData . "\n");

            fclose($database);

            //empty the cart
            $_SESSION['shopping_cart'] = [];
            unset($_SESSION['shopping_cart']);
            
            header("Location: ../client/invoice.php?".$urlGet);
            
            exit();

        }else{
            //payment failure
            header("Location: ../client/failed.php?message=".php_des_encryption($_SESSION['sessionKey'], "Payment failed. Please try again!"));
            
        }        
        
      }//end of IF POST
}else{
    //payment failure
    header("Location: ../client/failed.php?message=".php_des_encryption($_SESSION['sessionKey'], "Cart is not set. Please ensure you are logged in and have your cart with products"));
}
?>

