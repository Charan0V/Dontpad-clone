<?php

$email = $_POST["email"] ;
$password = $_POST["password"] ;

    // Create connection
    $conn=new mysqli("sql305.infinityfree.com", "if0_39252904", "Tu8sdbv1GSRK0B8", "if0_39252904_users");
$result = $conn->query("SELECT * FROM data WHERE email='$email' AND password='$password'");

if ($result->num_rows === 1) {
        $message = "Login successfully.";
} else {
        $message = "Invalid username or password.";
}
header("Location:dash.php"); 
?>
