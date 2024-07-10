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
    $nama = htmlentities(strip_tags(trim($_GET["nama_pasien"])));

    // filter untuk Snama untuk mencegah sql injection 
    $nama = mysqli_real_escape_string($link, $nama_pasien);

    // buat query pencarian
    $query ="SELECT * FROM pasien WHERE nama_pasien LIKE '%$nama_pasien%' ";
    $query .= "ORDER BY nama_pasien ASC";

    // buat pesan
    $pesan = "Hasil pencarian untuk nama_pasien <b>\"$nama_pasien\" </b>:";
}

else {
    // bukan dari form pencairan

    // siapkan query untuk menampilkan seluruh data dari tabel mahasiswa 
    $query = "SELECT * FROM pasien ORDER BY nama_pasien ASC";
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
            <h1 id="logo"> Sistem Rumah<span>Sakit</span></h1> 
            <p id="tanggal"> <?php echo date ("d M Y"); ?></p>
        </div>
        <nav>
            <ul>
                <li><a href="tampil mahasiswa.php">Tampil</a></li>
                <li><a href="tambah_pasien.php">Tambah</a>
                <li><a href="edit_pasien.php">Edit</a>
                <li><a href="hapus_pasien.php">Hapus</a></li>
                <li><a href="logout.php">Logout</a>
            </ul>
        </nav>
        <form id="search" action="tampil mahasiswa.php" method="get">
            <p>
                <label for="nim">Nama: </label>
                <input type="text" name="nama" id="nama" placeholder="search..."> <input type="submit" name="submit" value="Search">
            </p>
        </form>
        <h2>Data Pasien</h2>
        <?php
        // tampilkan pesan jika ada
        if (isset($pesan)) {
            echo "<div class=\"pesan\">$pesan</div>";
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
            //jalankan query
            $result = mysqli_query($link, $query);
            
            if(!$result){
                die ("Query Error: ".mysqli_errno($link).
                " - ".mysqli_error($link));
            }
            //buat perulangan untük element tabel dari data mahasiswa 
            while($data = mysqli_fetch_assoc($result)){
            
            // konversi date MySQL (yyyy-mm-dd) menjadi dd-mm-yyyy 
            $tanggal_php = strtotime($data["tanggal_lahir"]); 
            $tanggal = date("d - m - Y", $tanggal_php);
            
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



