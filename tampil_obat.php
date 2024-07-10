<?php
// periksa apakah user sudah login, cek kehadiran session name
// jika tidak ada, redirect ke login.php 
   session_start();
if (!isset($_SESSION["nama_pasien"])) {
     header("Location: login.php");

}

// buka koneksi dengan MySQL 
     include("koneksi.php");

// ambil pesan jika ada
if (isset($_GET["pesan"])) {
    $pesan = $_GET["pesan"];
} 

// cek apakah form telah di suheit
// berasal dari form pencairan, säupkan query
if (isset($_GET["submit"])) {

    // ambil nilai nama
    $nama_obat = htmlentities(strip_tags(trim($_GET["nama_obat"])));

    // filter untuk Snama untuk mencegah sql injection 
    $nama_obat = mysqli_real_escape_string($link, $nama_obat);

    // buat query pencarian
    $query ="SELECT * FROM obat WHERE nama_obat LIKE '%$nama%' ";
    $query .= "ORDER BY nama_obat ASC";

    // buat pesan
    $pesan = "Hasil pencarian untuk obat <b>\"$nama_obat\" </b>:";
}

else {
    // bukan dari form pencairan

    // siapkan query untuk menampilkan seluruh data dari tabel mahasiswa 
    $query = "SELECT * FROM obat ORDER BY nama_obat ASC";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Rumah Sakit</title>
    <link href="style.css" rel="stylesheet" >
    <link rel="stylesheet" href="favion.png" type="image/png">
</head>
<body>
    <div class="container">
        <div id="header">
            <h1 id="logo"> Sistem Rumah <span> Sakit</span></h1> 
            <p id="tanggal"> <?php echo date ("d M Y"); ?></p>
        </div>
        <nav>
            <ul>
                <li><a href="tampil obat.php">Tampil</a></li>
                <li><a href="tambah_obat.php">Tambah</a>
                <li><a href="edit_obat.php">Edit</a>
                <li><a href="hapus_obat.php">Hapus</a></li>
                <li><a href="logout.php">Logout</a>
            </ul>
        </nav>
        <form id="search" action="tampil obat.php" method="get">
            <p>
                <label for="nim">Nama: </label>
                <input type="text" name="nama" id="nama" placeholder="search..."> <input type="submit" name="submit" value="Search">
            </p>
        </form>
        <h2>Data Obat</h2>
        <?php
        // tampilkan pesan jika ada
        if (isset($pesan)) {
            echo "<div class=\"pesan\">$pesan</div>";
        }
        ?>
        <table border="1">
            <tr>
                <th>id_obat</th>
                <th>Nama Obat</th>
                <th>Bentuk Obat</th>
                <th>Tanggal kadaluarsa</th>
                <th>Stok</th>
                
            </tr>
            <?php
            //jalankan query
            $result = mysqli_query($link, $query);
            
            if(!$result){
                die ("Query Error: ".mysqli_errno($link).
                " - ".mysqli_error($link));
            }
            //buat perulangan untük element tabel dari data mahasiswa 
            while($data = mysqli_fetch_assoc($result)){
            
            // konversi date MySQL (yyyy-mm-dd) menjadi dd-mm-yyyy 
            $tanggal_kadaluarsa = strtotime($data["tanggal_kadaluarsa"]); 
            $tanggal = date("d - m - Y", $tanggal_kadaluarsa);
            
            echo "<tr>";
            echo "<td>$data[id_obat]</td>";
            echo "<td>$data[nama_obat]</td>";
            echo "<td>$data[bentuk_obat]</td>";
            echo "<td>$tanggal</td>";
            echo "<td>$data[stok]</td>"; 
            
            echo "</tr>";
        }
        // bebaskan memory.
        mysqli_free_result($result);
        //tutup koneksi dengan database mysql 
        mysqli_close($link);
        ?>
        </table>
        <div id="footer">
            Copyright @ <?php echo date("Y"); ?> FTIK USM
        </div>
    </div>

    
</body>
</html>



