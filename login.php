<?php 
require "koneksi.php";
session_start(); // Start the session at the beginning

if (isset($_POST["submit"])) {
    $username = htmlentities(strip_tags(trim($_POST["username"])));    
    $password = htmlentities(strip_tags(trim($_POST["password"]))); 
    $remember = isset($_POST["remember"]); // Check if "remember" is checked
    $pesan_error = ""; // Initialize error message variable

    // Check if "username" is filled
    if (empty($username)) { 
        $pesan_error .= "Username belum diisi <br>";     
    } 

    // Check if "password" is filled
    if (empty($password)) {       
        $pesan_error .= "Password belum diisi <br>";
    }

    if ($pesan_error === "") {
        // Filter input with mysqli_real_escape_string
        $username = mysqli_real_escape_string($link, $username);     
        $password = mysqli_real_escape_string($link, $password); 

        // Generate hashing     
        $password_sha1 = sha1($password); 

        // Check if username and password exist in the admin table
        $query = "SELECT * FROM admin WHERE username = '$username' AND password = '$password_sha1'";     
        $result = mysqli_query($link, $query); 

        if (mysqli_num_rows($result) == 0) {
            // Data not found, create an error message
            $pesan_error .= "Username dan/atau Password tidak sesuai";     
        } else {
            // Data found, set session
            $_SESSION["nama_pasien"] = $username;
            
            if ($remember) {
                // Set cookies if "remember" is checked
                setcookie("cookie_username", $username, time() + (3600), "/");
                setcookie("cookie_password", $password_sha1, time() + (3600), "/");
            }

            // Redirect to navbar.php
            header("Location: navbar.php");
            exit();
        }

        // Free memory and close connection
        mysqli_free_result($result);
        mysqli_close($link);
    }
} else {
    // Form not submitted or this page is displayed for the first time
    $pesan_error = "";     
    $username = "";     
    $password = "";   
} 
?> 
<!DOCTYPE html> 
<html lang="id"> 
<head>   
    <meta charset="UTF-8">   
    <title>Sistem Rumah Sakit</title>   
    <link rel="icon" href="favicon.png" type="image/png">  
    <style>
        body {
            background-image: url(img/bg\ rmh.jpg);
            background-size: 1300px;
        }
        div.container {
            width: 480px;
            padding: 10px 50px 80px;
            background-color: black no-repeat;
            margin: 20px auto;
            box-shadow: 1px 0px 10px, -1px 0px 10px;
        }
        h1, h3 {
            text-align: center;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        }
        p {
            margin: 0;
        }
        fieldset {
            padding: 50px;
            width: 300px;
            margin: auto;
        }
        input {
            margin-bottom: 10px;
        }
        input[type=text], input[type=password] {
            width: 150px;
        }
        input[type=submit] {
            float: right;
        }
        label {
            width: 80px;
            float: left;
            margin-right: 10px;
        }
        .error {
            background-color: #ffecec;
            padding: 10px 15px;
            margin: 0 0 20px 0;
            border: 1px solid red;
            box-shadow: 1px 0px 3px red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Selamat Datang</h1>
        <h3>Sistem Rumah Sakit</h3>
        <?php
        if (!empty($pesan_error)) {
            echo "<div class='error'>$pesan_error</div>";
        }
        ?>
        <form action="login.php" method="post">
            <fieldset>
                <legend>Login</legend>
                <p>
                    <label for="username">Username: </label> 
                    <input type="text" name="username" id="username" value="<?php echo isset($username) ? $username : '' ?>">
                </p>
                <p>
                    <label for="password">Password : </label>
                    <input type="password" name="password" id="password">
                </p>
                <p>
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Remember me</label>
                </p>
                <p>
                    <input type="submit" name="submit" value="Log In">
                </p>
            </fieldset>
        </form>
    </div>
</body>
</html>
