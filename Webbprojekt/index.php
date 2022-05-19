<?php
    session_start();

    if(isset($_POST["submit"])){
        if(isset($_POST["remember"])){
            setcookie("username", $_POST["username"]);
            setcookie("password", $_POST["password"]);
            setcookie("email", $_POST["email"]);
        }
        else{
            unset($_COOKIE["username"]);
            unset($_COOKIE["password"]);
            unset($_COOKIE["email"]);
            setcookie("username", "", time() - 3600, "/");
            setcookie("password", "", time() - 3600, "/");
            setcookie("email", "", time() - 3600, "/");
        }
    }

?>

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
        <div class="vw-100 vh-100 d-flex jusify-content-center align-items-center bigDiv">
        <div class="container w-auto h-auto d-flex justify-content-center border border-secondary rounded text-white">
            <form action="" method="POST" enctype="multipart/form-data">
                <h2 class="username">Username:</h2><br>
                <input type="text" id="username" name="username" value="<?php if (isset($_COOKIE["username"])) echo $_COOKIE["username"]; else echo "";?>">
                
                <br>

                <h2 class="password">Password:</h2><br>
                <input type="password" id="password" name="password" value="<?php if (isset($_COOKIE["password"])) echo $_COOKIE["password"]; else echo "";?>">
                <br>

                <h2 class="password">Email:</h2><br>
                <input type="email" id="email" name="email">
                
                <br>

                <label for="remember">Remember me?</label>
                <input type="checkbox" id="remember" name="remember">

                <br><input type="submit" class="submit" value="Log in" name="submit">
                <span>No account?</span><a href="register.php">Register here.</a>
            </form>
        </div>
        </div>

</body>
</html>

<?php

    if(isset($_SESSION["username"])){
        header("location:welcome.php");
        exit();
    }

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
    
    if(isset($_POST["submit"])){
        if(!empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["email"])){
            $username = $_POST["username"];
            $password = $_POST["password"];
            $email = $_POST["email"];
            $query = "SELECT * FROM Users WHERE username = '$username'";
            $result = mysqli_query($conn, $query);
            $numRows = mysqli_num_rows($result);
            
            if($numRows == 1){
                $row = mysqli_fetch_assoc($result);
                if (password_verify($password, $row["userpass"])){
                    $_SESSION["username"] = $username;
                    header("location:welcome.php");
                }
                else{
                    echo "<script>alert('It didnt work')</script>";
                }
            }
            else{
                echo "<script>alert('No users found')</script>";
            }
        }
        else{
            echo "<script>alert('Both fields required')</script>";
        }
    }        


    mysqli_close($conn);
?>