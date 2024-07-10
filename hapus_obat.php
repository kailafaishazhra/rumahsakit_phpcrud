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
    $id_obat = isset($_POST['id_obat']) ? $_POST['id_obat'] : null;
$nama_obat = isset($_POST['nama_obat']) ? $_POST['nama_obat'] : null;
$bentuk_obat = isset($_POST['bentuk_obat']) ? $_POST['bentuk_obat'] : null;
$tanggal_kadaluarsa = isset($_POST['tanggal_kadaluarsa']) ? $_POST['tanggal_kadaluarsa'] : null;
   

     // ambil nilai nim     
     $id_obat = htmlentities(strip_tags(trim($_POST["id_obat"])));     
     // filter data    
     $id_obat = mysqli_real_escape_string($link,$id_obat); 
 
    //jalankan query DELETE     
    $query = "DELETE FROM obat WHERE id_obat='$id_obat' ";     
    $hasil_query = mysqli_query($link, $query); 
 
    //periksa query, tampilkan pesan kesalahan jika gagal     
    if($hasil_query) {       
      // DELETE berhasil, redirect ke tampil_mahasiswa.php + pesan         
      $pesan = "Pasien dengan id_obat = \"<b>$id_obat</b>\" sudah berhasil di hapus";       
      $pesan = urlencode($pesan);         
      header("Location: tampil_obat.php?pesan={$pesan}"); 
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
          <title>Sistem Rumah Sakit</title>   
          <link href="style.css" rel="stylesheet" >   
          <link rel="icon" href="favicon.png" type="image/png" > 
        </head> 
        <body> 
          <div class="container"> 
            <div id="header">   
              <h1 id="logo">Sistem Rumah <span>Sakit</span></h1>   
              <p id="tanggal"><?php echo date("d M Y"); ?></p> 
            </div> 
            <hr>   
            <nav>   
              <ul>     
                <li><a href="tampil_obatphp">Tampil</a></li>     
                <li><a href="tambah_obat.php">Tambah</a>     
                <li><a href="edit_obat.php">Edit</a>     
                <li><a href="hapus_obat.php">Hapus</a></li>     
                <li><a href="logout.php">Logout</a>   
              </ul>   
            </nav>   
            <form id="search" action="tampil_obat.php" method="get">     
              <p>       
                <label for="nim">Nama : </label>       
                <input type="text" name="nama" id="nama" placeholder="search..." >       
                <input type="submit" name="submit" value="Search">     
              </p>   
            </form> 
            <h2>Hapus Data Obat</h2> 
            <?php   
            // tampilkan pesan jika ada   
            if ((isset($_GET["pesan"]))) {       
              echo "<div class=\"pesan\">{$_GET["pesan"]}</div>";   
              } 
              ?>  
              <table border="1">   
                <tr>   
                <th>id_obat</th>
                <th>Nama Obat</th>
                <th>Bentuk Obat</th>
                <th>Tanggal kadaluarsa</th>
                <th>Stok</th>
                <th></th>
               </tr>
  <?php   
  // buat query untuk menampilkan seluruh data tabel pasien   
   $query = "SELECT * FROM obat ORDER BY nama_obat ASC";   
   $result = mysqli_query($link, $query); 
   if(!$result){       
   die ("Query Error: ".mysqli_errno($link).            
   " - ".mysqli_error($link));   
  } 
  //buat perulangan untuk element tabel dari data mahasiswa   
  while($data = mysqli_fetch_assoc($result))   { 
    echo "<tr>";
    echo "<td>$data[id_obat]</td>";
    echo "<td>$data[nama_obat]</td>";
    echo "<td>$data[bentuk_obat]</td>";
    echo "<td>$data[tanggal_kadaluarsa]</td>";
    echo "<td>$data[stok]</td>";
       echo "<td>";
    
     ?>       
     <form action="hapus_obat.php" method="post" >       
      <input type="hidden" name="id_obat" value="<?php echo "$data[id_obat]"; ?>" >       
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
