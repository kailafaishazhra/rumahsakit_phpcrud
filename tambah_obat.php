<?php   
// periksa apakah user sudah login, cek kehadiran session name   
// jika tidak ada, redirect ke login.php   
session_start();   
if (!isset($_SESSION["nama_pasien"])) {      
    header("Location: login.php");   
} 
 
  // buka koneksi dengan MySQL   
  include("koneksi.php"); 
 
  $id_obat = ""; // atau berikan nilai default
    if (isset($_POST['id_obat'])) {
    $id_obat = $_POST['id_obat'];

}
  // cek apakah form telah di submit   
  if (isset($_POST["submit"])) {     
    // form telah disubmit, proses data 

    // Periksa apakah 'nama_obat' ada dalam array $_POST
if (isset($_POST['nama_obat'])) {
    $nama_obat = $_POST['nama_obat'];
} else {
    $nama_obat = ''; // Nilai default jika 'nama_obat' tidak ada
}
 
    // ambil semua nilai form     
    $id_pasien             = htmlentities(strip_tags(trim($_POST["id_obat"])));     
    $nama_pasien           = htmlentities(strip_tags(trim($_POST["nama_obat"])));     
    $bentuk_obat           = htmlentities(strip_tags(trim($_POST["bentuk_obat"])));
    $stok                  = htmlentities(strip_tags(trim($_POST["stok"])));
    $tgl                   = htmlentities(strip_tags(trim($_POST["tgl"])));     
    $bln                   = htmlentities(strip_tags(trim($_POST["bln"])));     
    $thn                   = htmlentities(strip_tags(trim($_POST["thn"]))); 
 
    // siapkan variabel untuk menampung pesan error     
    $pesan_error=""; 

     // cek apakah "nim" sudah diisi atau tidak     
     if (empty($id_obat)) {       
        $pesan_error .= "id pasienbelum diisi <br>";     
    }     
    // NIM harus angka dengan 8 digit     
    elseif (!preg_match("/^[0-9]{5}$/",$id_obat) ) {       
        $pesan_error .= "NIM harus berupa 4 digit angka <br>";     
    } 
 
    // cek ke database, apakah sudah ada nomor NIM yang sama     
    // filter data $nim     
    
    $query = "SELECT * FROM obat WHERE id_obat ='$id_obat'";     
    $hasil_query = mysqli_query($link, $query); 
 
    // cek jumlah record (baris), jika ada, $nim tidak bisa diproses     
    $jumlah_data = mysqli_num_rows($hasil_query);      
    if ($jumlah_data >= 1 ) {        
        $pesan_error .= "id obat yang sama sudah digunakan <br>";     
    } 
 
    // cek apakah "nama" sudah diisi atau tidak     
    if (empty($nama_obat)) {       
        $pesan_error .= "Nama Obat belum diisi <br>";     
    } 
 
    // cek apakah "tempat lahir" sudah diisi atau tidak     
    if (empty($bentuk_obat)) {       
        $pesan_error .= "Bentuk Obat belum diisi <br>";     } 
 
    // siapkan variabel untuk menggenerate pilihan fakultas     $select_kedokteran=""; $select_fmipa=""; $select_ekonomi="";     $select_teknik=""; $select_sastra=""; $select_fasilkom=""; 
 
    switch($bentuk_obat) {      
        case "Kapsul"      : $select_kapsul      = "selected";  break;      
        case "Tablet"      : $select_tablet      = "selected";  break;      
        case "Syrup"       : $select_syrup       = "selected";  break;      
        case "Serbuk"      : $select_serbuk      = "selected";  break;      
        
    } 
 
    // IPK harus berupa angka dan tidak boleh negatif     
    if (!is_numeric($stok) OR ($stok <=0)) {       
        $pesan_error .= "stok harus diisi dengan angka";     
    } 
 
    // jika tidak ada error, input ke database     
    if ($pesan_error === "") { 
 
      // filter semua data       
      $id_obat           = mysqli_real_escape_string($link,$id_obat);       
      $nama_obat         = mysqli_real_escape_string($link,$nama_obat );       
      $bentuk_obat       = mysqli_real_escape_string($link,$bentuk_obat);
      $bln               = mysqli_real_escape_string($link,$bln);       
      $thn               = mysqli_real_escape_string($link,$thn);       
      $stok              = (float)$stok;
      //gabungkan format tanggal agar sesuai dengan date MySQL       
      $tgl_lhr  = $thn."-".$bln."-".$tgl; 
 
      //buat dan jalankan query INSERT       
      $query = "INSERT INTO obat VALUES ";       
      $query .= "('$id_obat', '$nama_obat', '$bentuk_obat', ";       
      $query .= "'$tanggal_kadaluarsa',$stok)"; 
 
      $result = mysqli_query($link, $query); 
 
      //periksa hasil query       
      if($result) {       
        // INSERT berhasil, redirect ke tampil_mahasiswa.php + pesan         
        $pesan = "obat = \"<b>$nama_obat</b>\" sudah berhasil di tambah";         
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
    // form belum disubmit atau halaman ini tampil untuk pertama kali     
    // berikan nilai awal untuk semua isian form 
    $pesan_error       = "";     
    $id_obat               = "";     
    $nama_obat              = "";     
    $bentuk_obat      = "";     
    $select_kapsul = "selected";     
    $select_tablet = ""; $select_syrup= ""; $select_serbuk = "";         
    $stok = "";     
    $tgl=1;$bln="1";$thn=2024;   
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
<html lang="id"> 
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
        <h2>Tambah Data Obat</h2> 
        <?php   
        // tampilkan error jika ada   
        if ($pesan_error !== "") {       
            echo "<div class=\"error\">$pesan_error</div>";   
        } ?> 
        <form id="form_obat" action="tambah_obat.php" method="post"> 
            <fieldset> 
                <legend>Daftar Obat Baru</legend>   
                <p>     
                    <label for="id_obat">Id Obat </label>     
                    <input type="text" name="id_obat" id="id_obat" value="<?php echo $id_obat ?>"     
                    placeholder="Contoh: 12345678">     (4 digit angka)   
                </p>   
                <p>     
                    <label for="nama_obat">Nama Obat : </label>     
                    <input type="text" name="nama_obat" id="nama_obat" value="<?php echo $nama_obat ?>">   
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
                    <label for="tgl" >Tanggal Kadaluarsa: </label>       
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
                        for ($i = 1990; $i <= 2005; $i++) {           
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
                <p>     
                    <label for="stok">Stok : </label>     
                    <input type="text" name="stok" id="stok" value="<?php echo $stok ?>">   
                </p>   
                
            </fieldset>   
            <br>   
            <p>     
                <input type="submit" name="submit" value="Tambah Data">   
            </p> 
        </form> 
 
  <div id="footer">     
    Copyright © <?php echo date("Y"); ?> FTIK USM 
  </div> 
 
</div> 
 
</body> 
</html> 

<?php   
// tutup koneksi dengan database mysql   
mysqli_close($link); 
?>