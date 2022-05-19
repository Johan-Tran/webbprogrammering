<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log in</title>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
        <div class="vw-100 vh-100 d-flex jusify-content-center align-items-center bigDiv2 text-white">
        <div class="container w-auto h-auto d-flex justify-content-center border border-secondary rounded">
            <form action="" method="post" enctype="multipart/form-data">
                <h2 class="username">Username:</h2><br>
                <input type="text" id="username" name="username">
                
                <br>

                <h2 class="password">Password:</h2><br>
                <input type="password" id="password" name="password">
                <br>

                <h2 class="password">Email:</h2><br>
                <input type="email" id="email" name="email">

                <br><input type="submit" id="submit" value="Register" name="submit"><span>Already have an acccount? <a href="index.php">Login here!</a></span>
            </form>
        </div>
        </div>
</body>
</html>

<?php
    if(isset($_POST["submit"])){
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $email = $_POST['email'];

    $dbName = "mydb";
    $myDb = "CREATE DATABASE IF NOT EXISTS $dbName";
    
    $conn = mysqli_connect("localhost", "root", "");
        
    if (!$conn) {
    die("Connection failed: ".mysqli_connect_error());
    }

    mysqli_query($conn, $myDb);

    $conn = mysqli_connect("localhost", "root", "", $dbName);

    $myTable = "CREATE TABLE IF NOT EXISTS Users(
        id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL,
        userpass VARCHAR(255) NOT NULL,
        email VARCHAR(50) NOT NULL, 
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

    mysqli_query($conn,$myTable);

    $pass = password_hash($pass, PASSWORD_DEFAULT);

    $query = "SELECT * FROM Users WHERE username = '$user'";
    $result = mysqli_query($conn, $query);
    $numRows = mysqli_num_rows($result);

    if($numRows >= 1){
        echo "<script>alert('Username already taken')</script>";
    }
    else{
        $myData = "INSERT INTO Users (username, userpass, email)
        VALUES ('$user', '$pass', '$email')";
        mysqli_query($conn, $myData);
        header("location:index.php");
    }

    mysqli_close($conn);
    }
?>