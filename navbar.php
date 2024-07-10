<?php
session_start();
if (!isset($_SESSION['nama_pasien'])) {
    header("Location: login.php");
    exit();
}

// Debug information (Remove these lines to prevent printing array data)
// print_r($_SESSION);
// print_r($_COOKIE);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <div class="fContainer"> 
        <nav class="wrapper">
            <div class="brand">
                <div class="firstname">Rumah</div>
                <div class="lastname">Sakit</div>
            </div>
            <ul class="navigator">
                <li><a href="tampil_mahasiswa.php">Data Pasien</a></li>
                <li><a href="tampil_obat.php">Data Obat</a></li>
            </ul>
        </nav>
    </div>
</body>
</html>
