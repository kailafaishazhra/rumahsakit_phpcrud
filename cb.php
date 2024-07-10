// Akses kunci array lainnya dengan aman
if (isset($_GET['some_key'])) {
    $some_value = $_GET['some_key'];
} else {
    $some_value = ''; // atau nilai default
}


/buat dan jalankan query UPDATE       
      $query  = "UPDATE obat SET ";      
      $query .= "nama_obat = '$nama_obat', bentuk_obat = '$bentuk_obat', ";       
      $query .= "tanggal_kadaluarsa = '$tgl_lhr', stok='$stok ";       
      $query .= "WHERE id_obat='$id_obat'"; 
 
      $query = "UPDATE obat SET ";       
      $query .= "('$id_obat', '$nama_obat', '$bentuk_obat', ";       
      $query .= "'$tanggal_kadaluarsa',$stok)"; 

      <?php 
 require "koneksi.php";
  // ambil pesan jika ada 
  
  if (isset($_GET["pesan"])) {       
    $pesan = $_GET["pesan"];   
} 
 
  // cek apakah form telah di submit   
  if (isset($_POST["submit"])) {     
    // form telah disubmit, proses data 
 
    // ambil nilai form     
    $username = htmlentities(strip_tags(trim($_POST["username"])));    
    $password = htmlentities(strip_tags(trim($_POST["password"]))); 
    $remember = isset($_POST["remember"]); // Cek apakah "remember" dicentang
    // siapkan variabel untuk menampung pesan error     
    $pesan_error=""; 
 
    // cek apakah "username" sudah diisi atau tidak     
    if (empty($username)) { 
      $pesan_error .= "Username belum diisi <br>";     
    } 
 
    // cek apakah "password" sudah diisi atau tidak     
    if (empty($password)) {       
      $pesan_error .= "Password belum diisi <br>";
      
    } 
    
 
    // buat koneksi ke mysql dari file connection.php     
    include("koneksi.php"); 
 
    // filter dengan mysqli_real_escape_string     
    $username = mysqli_real_escape_string($link,$username);     
    $password = mysqli_real_escape_string($link,$password); 
 
    // generate hashing     
    $password_sha1 = sha1($password); 
    //cek 
 
    // cek apakah username dan password ada di tabel admin     
    $query = "SELECT * FROM admin WHERE username = '$username'               
    AND password = '$password_sha1'";     
    $result = mysqli_query($link,$query); 
 
    if(mysqli_num_rows($result) == 0 )  {       
      // data tidak ditemukan, buat pesan error      
       $pesan_error .= "Username dan/atau Password tidak sesuai";     
      } 
 
      // bebaskan memory       
      mysqli_free_result($result); 
 
      // tutup koneksi dengan database MySQL       
      mysqli_close($link); 
//cek cookie
      if(isset($_COOKIE['cookie_username'])){
        $cookie_username = $_COOKIE['cookie_username'];
        $cookie_password = $_COOKIE['cookie_password'];
    
        $sql1 = "select * from login where username = '$cookie_username'";
        $q1   = mysqli_query($koneksi,$sql1);
        $r1   = mysqli_fetch_array($q1);
        if($r1['password'] == $cookie_password){
            $_SESSION['session_username'] = $cookie_username;
            $_SESSION['session_password'] = $cookie_password;
        }
    }
 
    // jika lolos validasi, set session     
    if ($pesan_error === "") {       
      session_start();       
      $_SESSION["nama_pasien"] = $username;
      
      header("Location: navbar.php");     
    }   
  } else {     
      // form belum disubmit atau halaman ini tampil untuk pertama kali     
      // berikan nilai awal untuk semua isian form     
      $pesan_error = "";     
      $username = "";     
      $password = "";   
    } 

 ?> 

// tampilkan pesan jika ada
            if (isset($pesan)) {
                echo "<div class=\"pesan\">$pesan</div>";
            }
            // tampilkan error jika ada
            if ($pesan_error !== "") {
                echo "<div class=\"error\">$pesan_error</div>";
            }