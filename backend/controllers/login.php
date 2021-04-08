<?php

require_once '../config/config.php';


// POST's
$email         = $_POST['email'];
$password     = $_POST['password'];
$remember      = $_POST['remember']; 

// Check if they're not empty
if (strlen($email) > 0 &&
    strlen($password) > 0) {

    // Getting the hashed password from the databse
    $user = $link->prepare('SELECT `password` FROM `user` WHERE `email` = ?');
    $user->bind_param('s', $email);
    $user->execute();
    $result = mysqli_fetch_array($user->get_result());

    $hashPassword = $result[0];
    
    // Checking if the passwords matchup
    if (password_verify($password, $hashPassword)) {

        // Checking if the user wanted to be remembered
        if ($remember) {
            session_start();
            $token = bin2hex(openssl_random_pseudo_bytes(32));
            setcookie('token', $token, time() + (86400 * 7), "/");
            $_SESSION["email"] = $email;

            header('Location: ../../index.php');
            exit;
        } else {
            header('Location: ../../index.php');
        }
    } else {
        echo 'Wachtwoord is verkeerd!';
    }
} else {
    echo 'Niet alle velden waren ingevuld!';
}

?>