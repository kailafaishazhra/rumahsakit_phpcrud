<?php
// periksa apakah user sudah login, cek kehadiran session name
// jika tidak ada, redirect ke login.php
session_start();
if (!isset($_SESSION["nama_pasien"])) {
    header("Location: login.php");
}
// buka koneksi dengan MySQL
include("koneksi.php");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Sistem Rumah Sakit</title>
    <link href="style.css" rel="stylesheet">
    <link rel="icon" href="favicon.png" type="image/png">
</head>

<body>
    <div class="container">
        <div id="header">
            <h1 id="logo">Sistem Rumah<span>Sakit</span></h1>
            <p id="tanggal"><?php echo date("d M Y"); ?></p>
        </div>
        <hr>
        <nav>
            <ul>
                <li><a href="tampil_mahasiswa.php">Tampil</a></li>
                <li><a href="tambah_pasien.php">Tambah</a>
                <li><a href="edit_pasien.php">Edit</a>
                <li><a href="hapus_pasien.php">Hapus</a></li>
 <li><a href="logout.php">Logout</a>
 </ul>
 </nav>
 <form id="search" action="edit_pasien.php" method="get">
    <p>
        <label for="nama">Nama : </label>
        <input type="text" name="nama" id="nama" placeholder="search..." >
        <input type="submit" name="submit" value="Search">
    </p>
 </form>
<h2>Edit Data Pasien</h2>
<?php
 // tampilkan pesan jika ada
 if ((isset($_GET["pesan"]))) {
    echo "<div class=\"pesan\">{$_GET["pesan"]}</div>";
 }
?>
<table border="1">
    <tr>
      <th>Id Pasien</th>
      <th>Nama Pasien</th>
      <th>Tempat Lahir</th>
      <th>Tanggal Lahir</th>   
      <th>Jenis Kelamin</th>
       <th>Diagnosa</th>
      <th>Alamat</th>
      <th>Ruang Inap</th>
    <th>Nomor Kamar</th>               
 </tr>
 <?php
 // buat query untuk menampilkan seluruh data tabel mahasiswa
 $query = "SELECT * FROM pasien ORDER BY nama_pasien ASC";
 $result = mysqli_query($link, $query);

 if(!$result){
        die ("Query Error: ".mysqli_errno($link).
        " - ".mysqli_error($link));
 }

 //buat perulangan untuk element tabel dari data mahasiswa
 while($data = mysqli_fetch_assoc($result))
 {
    echo "<tr>";
    echo "<td>$data[id_pasien]</td>";
    echo "<td>$data[nama_pasien]</td>";
    echo "<td>$data[tempat_lahir]</td>";
    echo "<td>$data[tanggal_lahir]</td>";
    echo "<td>$data[jenis_kelamin]</td>";
    echo "<td>$data[diagnosa]</td>";
    echo "<td>$data[alamat]</td>";
    echo "<td>$data[ruang_inap]</td>";
    echo "<td>$data[nomor_kamar]</td>";
    echo "<td>";
    ?>
    <form action="form_edit.php" method="post" >
    <input type="hidden" name="id_pasien" value="<?php echo "$data[id_pasien]"; ?>" >
    <input type="submit" name="submit" value="Edit" >
    </form>
    <?php
    echo "</td>";
    echo "</tr>";
 }

    // bebaskan memory
    mysqli_free_result($result);
    // tutup koneksi dengan database mysql
    mysqli_close($link);
    ?>
    </table>
    <div id="footer">
        Copyright © <?php echo date("Y"); ?> FTIK USM
    </div>
 </div>
 </body>
 </html