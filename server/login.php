<?php session_start(); include('rsa.php');
    
    //receving the registration details along with the session key.


    //decrypt RSA
    $publicKey = get_rsa_publickey('public.key');
    $privateKey = get_rsa_privatekey('private.key');
    $email = rsa_decryption($_POST['email'], $privateKey);

    
    $publicKey = get_rsa_publickey('public.key');
    $privateKey = get_rsa_privatekey('private.key');
    $password = rsa_decryption($_POST['password'], $privateKey);;

    $emailExist = 0;
    $passwordExist = 0;

    $database = fopen("../database/users.txt", "r");

    //checking if the user exists.
    foreach(file('../database/users.txt') as $line) {

        list($savedEmail, $savedPassword, $name) = explode(",",$line);

        if(str_replace(' ', '', (string)$savedEmail) == str_replace(' ', '', (string)$email)) {
            $emailExist = 1;
            
            if(str_replace(' ', '', (string)$savedPassword) == str_replace(' ', '', (string)$password)) {
                $passwordExist = 1;
            
                break;
            }
            break;
        }
    }

    fclose($database);

    //checking if credentials are incorrect or not.
    if ($emailExist == 1 && $passwordExist == 0) {
        header("Location: ../client/login.php?m=Wrong credentials. Please try again");
        die();
    } else if ($emailExist == 0 || $passwordExist == 0) {
        header("Location: ../client/login.php?m=Wrong credentials. Please try again");
        die();
    } else if ($emailExist == 1 && $passwordExist == 1) {
        //successfull login.

        //if login successfull, decrypt the session key and store it for later encryptions.
        $publicKey = get_rsa_publickey('public.key');
        $privateKey = get_rsa_privatekey('private.key');
        $_SESSION['sessionKey'] = rsa_decryption($_POST['sessionKey'], $privateKey);

        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $name;
        //send the user to the home page.
        header("Location: ../client/index.php");
        die();

    }

?>
