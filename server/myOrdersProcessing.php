<?php
session_start();
include('../Utils/des.php');

//checking the orders when the user clicks on the
//'My Orders' page.
$database = fopen("../database/orders.txt", "r");
$dt = "";

//checking the orders
while(!feof($database)) {
    $data = trim(fgets($database));
    $currentUserOrders = false;
    
    if(isset($data)){
        
        
        $email = "";
        $address = "";
        $city = "";
        $state = "";
        $zipCode = "";
        $products = "";
        $name = "";
        

        $count = 0;
        $currentString = "";
        $productStartIndex = 0;

        for($i = 0; $i < strlen($data); $i++){
            $currentLetter = substr($data,$i,1);
            
            if($currentLetter == ','){
                $count += 1;
                if($count == 1){
                    //we have the email now.
                    //checking if it matches the logged in user.
                    if($currentString == $_SESSION['email']){
                        $email = $currentString;
                        $currentUserOrders = true;
                    }else{
                        //break from this loop and move on to the next line
                        //when it does not match the logged in user.
                        break;
                    }
                }else if($count == 2){
                    $name = $currentString;
                }else if($count == 3){
                    $address = $currentString;
                }else if($count == 4){
                    $city = $currentString;
                }else if($count == 5){
                    $state = $currentString;
                }else if($count == 6){
                    $zipCode = $currentString;
                }else if($count == 8){
                    $productStartIndex = $i+1;
                    break;
                }

                //reset
                if($count < 8){
                    $currentString = "";
                }

            }else{
                //keep adding to the string.
                $currentString .= $currentLetter;
            }
            
        }


        if($currentUserOrders){
            //add to the entire orders to be sent to the user.
            $products = substr($data, $productStartIndex);
        
            $dt .= $email . ',' . $name . ',' . $address . ',' . $city . ',' .$state . ',' . $zipCode . ',' . $products . '()';
        }
        
    }


}

//after orders are processed.
if($dt != ""){
    //there are products to show
    //encrypt the data (message and orders)
    $enc = php_des_encryption($_SESSION['sessionKey'], $dt);
    $message = php_des_encryption($_SESSION['sessionKey'], "Order history");
    //send the user to the My Orders page with the encrypted message and data.
    header("Location: ../client/myOrders.php?data=" . $enc . "&m=".$message);
}else{
    
    //no products to show
    //only encrypt the message that user need to make orders first.
    $message = php_des_encryption($_SESSION['sessionKey'], "You haven't made any orders yet!");
    //send the user to the My Orders page with the encrypted message.
    header("Location: ../client/myOrders.php?m=".$message);

}

fclose($database);

?>