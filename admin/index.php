 <?php
session_start(); 
include '../config/koneksi.php';
$userid = $_SESSION['userid'];
if ($_SESSION['status'] !='login') {
  echo "<script>
  alert('Anda belum Login';
  location.href='../index.php';
  </script>";
}

?>


<!DOCTYPE html>
<html>
<head>
 <meta charset="utf-8">
 <meta name="viemport" content="width=device-width-width, intial-scale=1">
 <title>Website Galeri Foto</title>
 <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"/>
 <style>
     .bg-image {
    background-image: url('../assets/img/rondi.jpg');
    height: 100;
   }
  </style>

</head>
<body class="bg-image">
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container">
      <a class="navbar-brand" href="index.php">Website Galeri Foto</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse mt-2" id="navbarNavAltMarkup">
        <div class="navbar-nav me-auto">
          <a href="home.php" class="nav-link">Home</a>
          <a href="album.php" class="nav-link">Album</a> 
          <a href="foto.php" class="nav-link">Foto</a> 
        </div>

        <a href="../config/aksi_logout.php" class="btn btn-outline-danger m-1">keluar</a>
      </div> 
    </div>
  </nav>

  <div class="container mt-3">
    <div class="row">
      <?php
      $query = mysqli_query($koneksi, "SELECT * FROM foto INNER JOIN user ON foto.userid=user.userid INNER JOIN album ON foto.albumid=album.albumid");
      while ($data = mysqli_fetch_array($query)) {
        ?>
        <div class="col-md-3">
          <a type="button" data-bs-toggle="modal" data-bs-target="#Komentar<?php echo $data['fotoid'] ?>"> 

            <div class="card mb-2">
              <img style="height: 12rem;" src="../assets/img/<?php echo $data['lokasifile'] ?>" class="card-img-top" title="<?php echo $data['judulfoto'] ?>">
              <div class="card-footer text-center">
                <?php 
                $fotoid = $data['fotoid'];
                $ceksuka = mysqli_query($koneksi, "SELECT * FROM likefoto WHERE fotoid='$fotoid' AND userid='$userid'");
                if (mysqli_num_rows($ceksuka) == 1) { ?>
                  <a href="../config/proses_like.php?fotoid=<?php echo $data['fotoid'] ?>" type="submit" name="batalsuka"><i class="fa fa-heart"></i></a> 
                <?php } else { ?>
                  <a href="../config/proses_like.php?fotoid=<?php echo $data['fotoid'] ?>" type="submit" name="suka"><i class="fa-regular fa-heart"></i></a>
                <?php }
                $like = mysqli_query($koneksi, "SELECT * FROM likefoto WHERE fotoid='$fotoid'");
                echo mysqli_num_rows($like) . ' suka';
                ?>
                <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#Komentar<?php echo $data['fotoid'] ?>"><i class="fa-regular fa-comment"></i></a> 
                <?php 
                $jmlkomen = mysqli_query($koneksi, "SELECT * FROM komentarfoto WHERE fotoid='$fotoid'");
                echo mysqli_num_rows($jmlkomen).'komentar';
                 ?>
              </div>
            </div>
          </a>

          <!-- Modal -->
          <div class="modal fade" id="Komentar<?php echo $data['fotoid'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
              <div class="modal-content">
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-8">
                      <img src="../assets/img/<?php echo $data['lokasifile'] ?>" class="card-img-top" title="<?php echo $data['judulfoto'] ?>">
                    </div>
                    <div class="col-md-4">
                      <div class="m-2">
                        <div class="overflow-auto">
                          <div class="stikcky-top">
                            <strong><?php echo $data['judulfoto'] ?></strong>
                            <span class="badge bg-secondary"><?php echo $data['namalengkap'] ?></span>
                            <span class="badge bg-secondary"><?php echo $data['tanggalunggah'] ?></span>
                            <span class="badge bg-primary"><?php echo $data['namaalbum'] ?></span>

                          </div>
                          <hr>
                          <?php
                          $fotoid = $data['fotoid'];
                          $komentar = mysqli_query($koneksi, "SELECT * FROM komentarfoto INNER JOIN user ON komentarfoto.userid=user.userid WHERE komentarfoto.fotoid='$fotoid'");
                          while ($row = mysqli_fetch_array($komentar)){ 
                           ?>
                           <p align="left">
                             <strong><?php echo $row['namalengkap'] ?></strong>
                             <?php echo $row['isikomentar'] ?>
                           </p>
                            <?php  } ?>
                          <hr>
                          <p align="left">
                            <?php echo $data['deskripsifoto'] ?>
                          </p>
                          <hr>
                          <div class="stikcky-bottom">
                            <form action="../config/proses_komentar.php" method="POST" >
                              <div class="input-grup">
                                <input type="hidden" name="fotoid" value="<?php echo $data['fotoid'] ?>">
                                <input type="text" name="isikomentar" class="form-control" placeholder="Tambah Komentar">
                                <div class="input-grup-prepend">
                                  <button type="submit" name="kirimkomentar" class="btn btn-outline-primary">Kirim</button>
                                </div>
                              </div>
                            </form>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>


        </div>
      <?php } ?>
    </div>
  </div>


<footer class="d-flex justify-content-center border-top mt-3 bg-light fixed-bottom">
    <p>&copy; UKK RPL 2024 | Rondi</p>
  </footer>

  <script type="text/javascript" src="../assets/js/bootstrap.min.js"></script>
</body>
</html> 