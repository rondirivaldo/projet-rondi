<?php 
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    echo "<script>
    alert('Anda Belum Login!');
    location.href='../index.php';
    </script>";
    exit;
}

$userid = $_SESSION['userid'];

// Ensure proper prepared statements are used for SQL queries
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
  <link rel="icon" type="image/jpg" href="../assets/Icon.jpg">
  
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
        <a href="../config/aksi_logout.php" class="btn btn-outline-danger m-1">Keluar</a>
      </div>
    </div>
  </nav>

  <div class="container mt-3">
    <div class="row">
      <div class="col-md-4">
        <div class="card mt-2">
          <div class="card-header">Tambah Album</div>
          <div class="card-body">
            <form action="../config/aksi_album.php" method="POST">
              <label class="form-label">Nama Album</label>
              <input type="text" name="namaalbum" class="form-control" required>
              <label class="form-label">Deskripsi</label>
              <textarea class="form-control" name="deskripsi" required></textarea>
              <button type="submit" class="btn btn-primary mt-2" name="tambah">Tambah Data</button>
            </form>
          </div>
        </div>
      </div>

      <div class="col-md-8">
        <div class="card mt-2">
          <div class="card-header">Data Album</div>
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nama Album</th>
                  <th>Deskripsi</th>
                  <th>Tanggal</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                 <?php 
                $no = 1;
                $stmt = $koneksi->prepare("SELECT * FROM album WHERE userid = ?");
                $stmt->bind_param("s", $userid);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($data = $result->fetch_assoc()) {
                 ?>
                 <tr>
                  <td><?php echo htmlspecialchars($no++, ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars($data['namaalbum'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars($data['deskripsi'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars($data['tanggalbuat'], ENT_QUOTES, 'UTF-8'); ?></td>
                  <td>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edit<?php echo htmlspecialchars($data['albumid'], ENT_QUOTES, 'UTF-8'); ?>">
                      Edit
                    </button>

                    <div class="modal fade" id="edit<?php echo htmlspecialchars($data['albumid'], ENT_QUOTES, 'UTF-8'); ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h1 class="modal-title fs-5" id="editModalLabel">Edit Data</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <form action="../config/aksi_album.php" method="POST">
                              <input type="hidden" name="albumid" value="<?php echo htmlspecialchars($data['albumid'], ENT_QUOTES, 'UTF-8'); ?>">
                              <label class="form-label">Nama Album</label>
                              <input type="text" name="namaalbum" value="<?php echo htmlspecialchars($data['namaalbum'], ENT_QUOTES, 'UTF-8'); ?>" class="form-control" required>
                              <label class="form-label">Deskripsi</label>
                              <textarea class="form-control" name="deskripsi" required><?php echo htmlspecialchars($data['deskripsi'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                            <div class="modal-footer">
                              <button type="submit" name="edit" class="btn btn-primary">Edit Data</button>
                            </form>
                            </div>
                        </div>
                      </div>
                    </div>

                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#hapus<?php echo htmlspecialchars($data['albumid'], ENT_QUOTES, 'UTF-8'); ?>">
                      Hapus
                    </button>

                    <div class="modal fade" id="hapus<?php echo htmlspecialchars($data['albumid'], ENT_QUOTES, 'UTF-8'); ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h1 class="modal-title fs-5" id="deleteModalLabel">Hapus Data</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <form action="../config/aksi_album.php" method="POST">
                              <input type="hidden" name="albumid" value="<?php echo htmlspecialchars($data['albumid'], ENT_QUOTES, 'UTF-8'); ?>">
                              Apakah anda yakin akan menghapus data <strong> <?php echo htmlspecialchars($data['namaalbum'], ENT_QUOTES, 'UTF-8'); ?> </strong> ?
                            </div>
                            <div class="modal-footer">
                              <button type="submit" name="hapus" class="btn btn-primary">Hapus Data</button>
                            </form>
                            </div>
                        </div>
                      </div>
                    </div>

                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <footer class="d-flex justify-content-center border-top mt-3 bg-light fixed-bottom">
    <p>&copy; UKK RPL 2024 | RONDI</p>
  </footer>

  <script src="../assets/js/bootstrap.min.js"></script>
</body>
</html>
