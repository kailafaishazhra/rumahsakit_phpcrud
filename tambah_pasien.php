<?php   
// periksa apakah user sudah login, cek kehadiran session name   
// jika tidak ada, redirect ke login.php 

session_start();   
if (!isset($_SESSION["nama_pasien"])) {      
    header("Location: login.php");   
} 
 
  // buka koneksi dengan MySQL   
  include("koneksi.php"); 
 
  // cek apakah form telah di submit   
  if (isset($_POST["submit"])) {     
    // form telah disubmit, proses data 
 
    // ambil semua nilai form     
    $id_pasien           = htmlentities(strip_tags(trim($_POST["id_pasien"])));     
    $nama_pasien         = htmlentities(strip_tags(trim($_POST["nama_pasien"])));     
    $tempat_lahir        = htmlentities(strip_tags(trim($_POST["tempat_lahir"])));     
    $jenis_kelamin       = htmlentities(strip_tags(trim($_POST["jenis_kelamin"]))); 
    $diagnosa            = htmlentities(strip_tags(trim($_POST["diagnosa"])));
    $alamat              = htmlentities(strip_tags(trim($_POST["alamat"])));
    $ruang_inap          = htmlentities(strip_tags(trim($_POST["ruang_inap"])));             
    $nomor_kamar         = htmlentities(strip_tags(trim($_POST["nomor_kamar"])));     
    $tgl                 = htmlentities(strip_tags(trim($_POST["tgl"])));     
    $bln                 = htmlentities(strip_tags(trim($_POST["bln"])));     
    $thn                 = htmlentities(strip_tags(trim($_POST["thn"]))); 
 
    // siapkan variabel untuk menampung pesan error     
    $pesan_error=""; 

     // cek apakah "nim" sudah diisi atau tidak     
     if (empty($id_pasien)) {       
        $pesan_error .= "id pasien belum diisi <br>";     
    }     
    // NIM harus angka dengan 8 digit     
    elseif (!preg_match("/^[0-9]{4}$/",$id_pasien) ) {       
        $pesan_error .= "id_pasien harus berupa 4 digit angka <br>";     
    } 
 
    // cek ke database, apakah sudah ada nomor NIM yang sama     
    // filter data $nim     
    $id_pasien= mysqli_real_escape_string($link,$id_pasien);     
    $query = "SELECT * FROM pasien WHERE id_pasien='$id_pasien'";     
    $hasil_query = mysqli_query($link, $query); 
 
    // cek jumlah record (baris), jika ada, $nim tidak bisa diproses     
    $jumlah_data = mysqli_num_rows($hasil_query);      
    if ($jumlah_data >= 1 ) {        
        $pesan_error .= "NIM yang sama sudah digunakan <br>";     
    } 
 
    // cek apakah "nama" sudah diisi atau tidak     
    if (empty($nama_pasien)) {       
        $pesan_error .= "Nama belum diisi <br>";     
    } 
 
    // cek apakah "tempat lahir" sudah diisi atau tidak     
    if (empty($tempat_lahir)) {       
        $pesan_error .= "Tempat lahir belum diisi <br>";     } 
    
    //siapkan variabel untuk menggenerate pilihan jenis kelamin
    $select_lakilaki=""; $select_perempuan="";
    switch($ruang_inap) {      
        case "lakilaki"       : $select_lakilaki = "selected";  break;      
        case "perempuan"      : $select_perempuan      = "selected";  break;      
    }
    // cek apakah "diagnosa" sudah diisi atau tidak     
    if (empty($diagnosa)) {       
        $pesan_error .= "Tempat lahir belum diisi <br>";     }

    // cek apakah "alamat" sudah diisi atau tidak     
    if (empty($alamat)) {       
        $pesan_error .= "alamat belum diisi <br>";     }  

    
 
    // siapkan variabel untuk menggenerate pilihan fakultas     
    $select_nakula=""; $select_arjuna=""; $select_sadewa="";     
    $select_dewishinta=""; $select_bima=""; $select_yudistira=""; 
 
    switch($ruang_inap) {      
        case "Nakula" : $select_nakula = "selected";  break;      
        case "Arjuna"      : $select_arjuna      = "selected";  break;      
        case "Sadewa"    : $select_sadewa   = "selected";  break;      
        case "DewiShinta"     : $select_dewishinta     = "selected";  break;      
        case "Bima"     : $select_bima     = "selected";  break;      
        case "Yudistira"   : $select_yudistira      = "selected";  break; 
    } 

     

    // IPK harus berupa angka dan tidak boleh negatif     
    if (!is_numeric($nomor_kamar) OR ($nomor_kamar <=0)) {       
        $pesan_error .= "nomor kamar harus diisi dengan angka";     
    } 
 
    // jika tidak ada error, input ke database     
    if ($pesan_error === "") { 
 
      // filter semua data       
      $id_pasien          = mysqli_real_escape_string($link,$id_pasien);       
      $nama_pasien        = mysqli_real_escape_string($link,$nama_pasien );       
      $tempat_lahir       = mysqli_real_escape_string($link,$tempat_lahir);       
      $jenis_kelamin      = mysqli_real_escape_string($link,$jenis_kelamin);
      $alamat             = mysqli_real_escape_string($link,$alamat);       
      $diagnosa           = mysqli_real_escape_string($link,$diagnosa);
      $ruang_inap         = mysqli_real_escape_string($link,$ruang_inap);              
      $tgl                = mysqli_real_escape_string($link,$tgl);       
      $bln                = mysqli_real_escape_string($link,$bln);       
      $thn                = mysqli_real_escape_string($link,$thn);       
      $nomor_kamar        = (float) $nomor_kamar; 
 
      //gabungkan format tanggal agar sesuai dengan date MySQL       
      $tgl_lhr = $thn."-".$bln."-".$tgl; 
 
      //buat dan jalankan query INSERT       
      $query = "INSERT INTO pasien VALUES ";       
      $query .= "('$id_pasien', '$nama_pasien', '$tempat_lahir', ";       
      $query .= "'$tgl_lhr','$jenis_kelamin','$diagnosa','$alamat','$ruang_inap',$nomor_kamar)"; 
 
      $result = mysqli_query($link, $query); 
 
      //periksa hasil query       
      if($result) {       
        // INSERT berhasil, redirect ke tampil_mahasiswa.php + pesan         
        $pesan = "Pasien dengan nama_pasien = \"<b>$nama_pasien</b>\" sudah berhasil di t ambah";         
        $pesan = urlencode($pesan);         
        header("Location: tampil_mahasiswa.php?pesan={$pesan}");       
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
    $id_pasien              = "";     
    $nama_pasien              = "";     
    $tempat_lahir      = ""; 
    $select_lakilaki= "selected";
    $select_perempuan="";   
    $diagnosa= ""; 
    $alamat = "";
    $select_nakula = "selected";     
    $select_arjuna = ""; $select_bima= ""; $select_dewishinta = "";     
    $select_sadewa = ""; $select_yudistira = "";     
    $nomor_kamar = "";     
    $tgl=1;$bln="1";$thn=1996;   
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
        <h2>Tambah Data pasien</h2> 
        <?php   
        // tampilkan error jika ada   
        if ($pesan_error !== "") {       
            echo "<div class=\"error\">$pesan_error</div>";   
        } ?> 
        <form id="form_mahasiswa" action="tambah_pasien.php" method="post"> 
            <fieldset> 
                <legend>pasien Baru</legend>   
                <p>     
                    <label for="id_pasien">id pasien : </label>     
                    <input type="text" name="id_pasien" id="id_pasien" value="<?php echo $id_pasien ?>"     
                    placeholder="Contoh: 0786">     (4 digit angka)   
                </p>   
                <p>     
                    <label for="nama_pasien">Nama Pasien: </label>     
                    <input type="text" name="nama_pasien" id="nama_pasien" value="<?php echo $nama_pasien ?>">   
                </p>   
                <p>     
                    <label for="tempat_lahir">Tempat Lahir : </label>     
                    <input type="text" name="tempat_lahir" id="tempat_lahir"     
                    value="<?php echo $tempat_lahir ?>">   
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
                    <label for="jenis_kelamin">Jenis Kelamin : </label> 
                    <select name="jenis_kelamin" id="jenis_kelamin">
                    <option value="lakilaki" <?php echo $select_lakilaki ?>>Laki - laki </option>         
                    <option value="perempuan" <?php echo $select_perempuan ?>>  Perempuan</option>        
                    </select>
                </p>   
                <p>     
                    <label for="diagnosa">Diagnosa : </label>     
                    <input type="text" name="diagnosa" id="diagnosa"     
                    value="<?php echo $diagnosa ?>">

                </p>
                <p>     
                    <label for="alamat">Alamat : </label>     
                    <input type="text" name="alamat" id="alamat"     
                    value="<?php echo $alamat ?>">   
                </p>  
                
                <p>     
                    <label for="ruang_inap" >Ruang Inap : </label>       
                    <select name="ruang_inap" id="ruang_inap">         
                        <option value="Nakula" <?php echo $select_nakula ?>>Nakula </option>         
                        <option value="Arjuna" <?php echo $select_arjuna ?>>        Arjuna</option>         
                        <option value="Sadewa" <?php echo $select_sadewa ?>>Sadewa</option>         
                        <option value="DewiShinta" <?php echo $select_dewishinta ?>>        Dewi Shinta</option>         
                        <option value="Bima" <?php echo $select_bima ?>>         Bima</option>         
                        <option value="Yudistira" <?php echo $select_yudistira ?>>        Yudistira</option>       
                    </select>   
                </p>   
                 
                <p >     
                    <label for="nomor_kamar">Nomer Kamar : </label>     
                    <input type="text" name="nomor_kamar" id="nomor_kamar" value="<?php echo $nomor_kamar ?>"     
                    placeholder="Contoh: 01">     
                </p> 
            </fieldset>   
            <br>   
            <p>     
                <input type="submit" name="submit" value="Tambah Data">   
            </p> 
        </form> 
 
  <div id="footer">     
    Copyright © <?php echo date("Y"); ?> Rumah sakit Permata bunda
  </div> 
 
</div> 
 
</body> 
</html> 

<?php   
// tutup koneksi dengan database mysql   
mysqli_close($link); 
?>