<?php   
// periksa apakah user sudah login, cek kehadiran session name   
// jika tidak ada, redirect ke login.php   
session_start();   
if (!isset($_SESSION["nama_pasien"])) {      
  header("Location: login.php");   
} 
 
  // buka koneksi dengan MySQL   
  include("koneksi.php"); 
 
  // cek apakah form telah di submit (untuk menghapus data)   
  if (isset($_POST["submit"])) {     
    // form telah disubmit, proses data 

    if (isset($_POST['id_obat'])) {
      $id_obat = $_POST['id_obat'];
  } else {
      $id_obat = ''; // Nilai default jika 'nama_obat' tidak ada
  }
   
 
    // ambil nilai nim     
    $id_pasien = htmlentities(strip_tags(trim($_POST["id_pasien"])));     
    // filter data    
     $id_pasien = mysqli_real_escape_string($link,$id_pasien); 
 
    //jalankan query DELETE     
    $query = "DELETE FROM pasien WHERE id_pasien='$id_pasien' ";     
    $hasil_query = mysqli_query($link, $query); 
 
    //periksa query, tampilkan pesan kesalahan jika gagal     
    if($hasil_query) {       
      // DELETE berhasil, redirect ke tampil_mahasiswa.php + pesan         
      $pesan = "Pasien dengan id_pasien = \"<b>$id_pasien</b>\" sudah berhasil di hapus";       
      $pesan = urlencode($pesan);         
      header("Location: tampil_mahasiswa.php?pesan={$pesan}"); 
    }     
    else {       
      die ("Query gagal dijalankan: ".mysqli_errno($link).            
      " - ".mysqli_error($link));     
      }   
     } 
      ?> 
      <!DOCTYPE html> <html lang="id"> 
        <head>   
          <meta charset="UTF-8">   
          <title>Sistem Informasi Mahasiswa</title>   
          <link href="style.css" rel="stylesheet" >   
          <link rel="icon" href="favicon.png" type="image/png" > 
        </head> 
        <body> 
          <div class="container"> 
            <div id="header">   
              <h1 id="logo">Sistem Informasi <span>Kampusku</span></h1>   
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
            <form id="search" action="tampil_mahasiswa.php" method="get">     
              <p>       
                <label for="nim">Nama : </label>       
                <input type="text" name="nama" id="nama" placeholder="search..." >       
                <input type="submit" name="submit" value="Search">     
              </p>   
            </form> 
            <h2>Hapus Data Pasien</h2> 
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
  // buat query untuk menampilkan seluruh data tabel pasien   
   $query = "SELECT * FROM pasien ORDER BY nama_pasien ASC";   
   $result = mysqli_query($link, $query); 
   if(!$result){       
   die ("Query Error: ".mysqli_errno($link).            
   " - ".mysqli_error($link));   
  } 
 
  //buat perulangan untuk element tabel dari data mahasiswa   
  while($data = mysqli_fetch_assoc($result))   {    
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
     <form action="hapus_pasien.php" method="post" >       
      <input type="hidden" name="id_pasien" value="<?php echo "$data[id_pasien]"; ?>" >       
      <input type="submit" name="submit" value="Hapus" >      
     </form>     
     <?php     
     echo "</td>";     
     echo "</tr>";   } 
 
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
