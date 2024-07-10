<?php   
  // periksa apakah user sudah login, cek kehadiran session name   
  // jika tidak ada, redirect ke login.php   
  session_start();   
  if (!isset($_SESSION["nama_pasien"])) {      
    header("Location: login.php");   
} 
 
  // buka koneksi dengan MySQL   
  include("koneksi.php"); 

  if (isset($_GET['id_obat'])) {
    $id_obat = $_GET['id_obat'];
} else {
    // Tangani kasus jika 'id_pasien' tidak ada
    $id_obat = ''; // atau nilai default atau penanganan error
}
// Akses kunci array lainnya dengan aman
if (isset($_GET['some_key'])) {
    $some_value = $_GET['some_key'];
} else {
    $some_value = ''; // atau nilai default
}




  // cek apakah form telah di submit   
  if (isset($_POST["submit"])) {     
    // form telah disubmit, cek apakah berasal dari edit_mahasiswa.php     
    // atau update data dari form_edit.php 
    
    if ($_POST["submit"]=="Edit") {       
        //nilai form berasal dari halaman edit_mahasiswa.php 
 
      // ambil nilai nim       
      $id_obat = htmlentities(strip_tags(trim($_POST["id_obat"])));       
      // filter data       
      $id_obat = mysqli_real_escape_string($link,$id_obat); 
 
      // ambil semua data dari database untuk menjadi nilai awal form 
       
      $query = "SELECT * FROM obat WHERE id_obat='$id_obat' "; 
      $result = mysqli_query($link, $query); 
 
      if(!$result){ 
        die ("Query Error: ".mysqli_errno($link).              
        " - ".mysqli_error($link));      
       }
 
      // tidak perlu pakai perulangan while, karena hanya ada 1 record       
      $data = mysqli_fetch_assoc($result); 
 
      $nama_obat         = $data["nama_obat"];       
      $bentuk_obat       = $data["bentuk_obat"];       
      $stok              = $data["stok"];       
     
 
      // untuk tanggal harus dipecah       
      $tgl = substr($data["tanggal_kadaluarsa"],8,2);       
      $bln = substr($data["tanggal_kadaluarsa"],5,2);       
      $thn = substr($data["tanggal_kadaluarsa"],0,4); 
 
    // bebaskan memory     
    mysqli_free_result($result);     
    } 
 
    else if ($_POST["submit"]=="Update Data") {       
        // nilai form berasal dari halaman form_edit.php       
        // ambil semua nilai form      
        $id_obat               = htmlentities(strip_tags(trim($_POST["id_obat"])));     
        $nama_obat             = htmlentities(strip_tags(trim($_POST["nama_obat"])));     
        $bentuk_obat           = htmlentities(strip_tags(trim($_POST["bentuk_obat"])));
        $stok                  = htmlentities(strip_tags(trim($_POST["stok"])));
        $tgl                   = htmlentities(strip_tags(trim($_POST["tgl"])));     
        $bln                   = htmlentities(strip_tags(trim($_POST["bln"])));     
        $thn                   = htmlentities(strip_tags(trim($_POST["thn"]))); 
        } 
 
    // proses validasi form     
    // siapkan variabel untuk menampung pesan error     
    $pesan_error=""; 
 
    // cek apakah "nim" sudah diisi atau tidak     
    if (empty($id_obat)) {      
         $pesan_error .= "Id Obat belum diisi <br>";     
    }    
    // NIM harus angka dengan 8 digit     
    elseif (!preg_match("/^[0-9]{5}$/",$id_obat) ) {       
        $pesan_error .= "Id harus berupa 5 digit angka <br>";     
    } 

     // cek apakah "nama" sudah diisi atau tidak     
     if (empty($nama_obat)) {       
        $pesan_error .= "Nama belum diisi <br>";     
    }     
 
    // siapkan variabel untuk menggenerate pilihan fakultas     
    $select_kapsul=""; $select_tablet="";     
    $select_syrup=""; $select_serbuk=""; 
 
    switch($bentuk_obat) {      
        case "Kapsul"       : $select_kapsul = "selected";  break;      
        case "Tablet"      : $select_tablet      = "selected";  break;     
        case "Syrup"    : $select_syrup    = "selected";  break;      
        case "Serbuk"     : $select_serbuk     = "selected";  break;      
       
    } 
 
    // IPK harus berupa angka dan tidak boleh negatif     
    if (!is_numeric($stok) OR ($stok <=0)) {       
        $pesan_error .= "Stok harus diisi dengan angka";     
    } 
 
    // jika tidak ada error, input ke database     
    if (($pesan_error === "") AND ($_POST["submit"]=="Update Data")) { 
 
      // buka koneksi dengan MySQL       
      include("koneksi.php"); 
 
      // filter semua data       
      $id_obat           = mysqli_real_escape_string($link,$id_obat);       
      $nama_obat         = mysqli_real_escape_string($link,$nama_obat );       
      $bentuk_obat       = mysqli_real_escape_string($link,$bentuk_obat);
      $tgl               = mysqli_real_escape_string($link,$tgl);       
      $bln               = mysqli_real_escape_string($link,$bln);       
      $thn               = mysqli_real_escape_string($link,$thn);       
      $stok              = (float)$stok;
 
      //gabungkan format tanggal agar sesuai dengan date MySQL       
      $tgl_lhr = $thn."-".$bln."-".$tgl; 
 
      //buat dan jalankan query UPDATE       
      $query  = "UPDATE obat SET ";      
      $query .= "nama_obat = '$nama_obat', bentuk_obat = '$bentuk_obat', ";       
      $query .= "tanggal_kadaluarsa = '$tgl_lhr', stok='$stok ";   
      $query .= "WHERE id_obat='$id_obat'";    
      

      $result = mysqli_query($link, $query); 
 
      //periksa hasil query       
      if($result) {       
        // INSERT berhasil, redirect ke tampil_mahasiswa.php + pesan         
        $pesan = "obat dengan nama = \"<b>$nama_obat</b>\" sudah berhasil di u pdate";         
        $pesan = urlencode($pesan);         
        header("Location: tampil_obat.php?pesan={$pesan}");       
    }       
    else {       
        die ("Query gagal dijalankan: ".mysqli_errno($link).            
             " - ".mysqli_error($link));       
            }     
        }   
    }   
    else {     
        // form diakses secara langsung!     
        // redirect ke edit_mahasiswa.php     
        header("Location: edit_obat.php");   
    } 
 
  // siapkan array untuk nama bulan   
  $arr_bln = array( "1"=>"Januari",                     
                    "2"=>"Februari",                     
                    "3"=>"Maret",                     
                    "4"=>"April",                     
                    "5"=>"Mei",                     
                    "6"=>"Juni",                     
                    "7"=>"Juli",                     
                    "8"=>"Agustus",                     
                    "9"=>"September",                     
                    "10"=>"Oktober",                     
                    "11"=>"Nopember",                     
                    "12"=>"Desember" ); 
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Mahasiswa</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="favion.png" type="image/png">
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
            <li><a href="tampil_obat.php">Tampil</a></li>     
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
    <h2>Edit Data Obat</h2> 
    <?php   
    // tampilkan error jika ada   
    if ($pesan_error !== "") {       
        echo "<div class=\"error\">$pesan_error</div>";   
        } 
    ?> 
    <form id="form_obat" action="form_obat.php" method="post"> 
        <fieldset> 
            <legend>Obat Baru</legend>   
            <p>     
                <label for="id_obat">Id Obat : </label>     
                <input type="text" name="id_obat" id="id_obat" value="<?php echo $id_obat ?>" readonly >     (
                    tidak bisa diubah di menu edit)   
            </p> 
            <p>     
                <label for="nama_obat">Nama Obat: </label>     
                <input type="text" name="nama_obat" id="nama_obat" 
                value="<?php echo $nama_obat ?>">   
            </p> 
            <p>     
                <label for="bentuk_obat" >Bentuk Obat : </label>       
                <select name="bentuk_obat" id="bentuk_obat">         
                    <option value="Kapsul" <?php echo $select_kapsul ?>>Kapsul</option>         
                    <option value="Tablet" <?php echo $select_tablet ?>>        Tablet</option>         
                    <option value="Syrup" <?php echo $select_syrup ?>>         Sirup</option>         
                    <option value="Sebuk" <?php echo $select_serbuk ?>>         Serbuk</option>         
                        
                </select>   
            </p>     
              
            <p>     
                <label for="tgl" >Tanggal Lahir : </label>       
                <select name="tgl" id="tgl">         
                    <?php           
                    for ($i = 1; $i <= 31; $i++) {             
                        if ($i==$tgl){               
                            echo "<option value = $i selected>";             
                        }             
                        else {               
                            echo "<option value = $i >";            
                        }             
                        echo str_pad($i,2,"0",STR_PAD_LEFT);             
                        echo "</option>";           
                    }         
                    ?>       
                </select>         
                <select name="bln">         
                    <?php         
                    foreach ($arr_bln as $key => $value) {           
                        if ($key==$bln){             
                            echo "<option value=\"{$key}\" selected>{$value}</option>";           
                            }           
                        else {             
                            echo "<option value=\"{$key}\">{$value}</option>";           
                        }         
                    }         
                    ?>       
                </select>       
                <select name="thn">         
                    <?php           
                    for ($i = 1990; $i <= 2035; $i++) {           
                        if ($i==$thn){               
                            echo "<option value = $i selected>";             
                            }             
                        else {               
                            echo "<option value = $i >";             
                        }             
                        echo "$i </option>";           
                    }
                    ?>       
                </select>   
            </p>   
             
              
            <p >     
                <label for="stok">Stok : </label>     
                <input type="text" name="stok" id="stok" value="<?php echo $stok ?>">     
                (angka desimal dipisah dengan karakter titik ".")   
            </p> 
        </fieldset>   
        <br>   
        <p>     
            <input type="submit" name="submit" value="Update Data">   
        </p> 
    </form>

</div>

</body>
</html>
<?php
//tutup koneksi dengan database mysql
mysqli_close($link);
?>