<?php
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
echo "Name: $name<br>Email:$email<br>";
$conn=new mysqli("sql305.infinityfree.com", "if0_39252904", "Tu8sdbv1GSRK0B8", "if0_39252904_users");
$query="INSERT INTO data (name, email,password) VALUES ('$name', '$email','$password')";
$conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Form Submitted Successfully</h1>
        <a href='login.php'><p>click here to login</p></a>
    </div>
</body>
</html>