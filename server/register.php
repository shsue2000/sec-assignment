<?php session_start(); 

//processing the registration
    $email = $_POST['email'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $emailExist = 0;

    $insertData = $email . "," . $password . "," . $name;

    $database = fopen("../database/users.txt", "r");

    while(!feof($database)) {
        $data = trim(fgets($database));

        list($savedEmail, , , ) = explode(",", $data);
        
        //checking if the email exists or not.
        if($savedEmail == $email) {
            $emailExist = 1;
            break;
        }
    }

    fclose($database);

    if($emailExist == 1) {
        //if exists then send the user back to the registration page and
        //send the error message with it as well.
        header("Location: ../client/register.php?m=This email exists in the system");

    } else {
        //successfull registration
        $database = fopen("../database/users.txt", "a"); 

        fwrite($database, $insertData . "\n");

        fclose($database);
        //send the user to the login page.
        header("Location: ../client/login.php");
        
    }

?>

