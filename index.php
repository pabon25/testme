<?php


$host = "db";
$user = "root";
$password = "root123";
$database = "school";

$conn = new mysqli(
    $host,
    $user,
    $password,
    $database
);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "PHP Connected Successfully to MySQL!";




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>My PHP App</h1>
    <p>Welcome to my PHP application!</p>
    <form action="" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name">
        <button type="submit">Submit</button>   
    </form>
</body>
</html>