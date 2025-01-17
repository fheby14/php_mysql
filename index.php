<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "akademik";

$koneksi = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$koneksi) {
    die("Tidak bisa terkoneksi ke database!");
}

$nim        = "";
$nama       = "";
$alamat     = "";
$fakultas   = "";
$sukses     = "";
$error      = "";
$id         = "";

// Untuk tombol simpan (insert/update)
if (isset($_POST['simpan'])) {
    $id        = $_POST['id']; // Ambil ID dari form
    $nim       = $_POST['nim'];
    $nama      = $_POST['nama'];
    $alamat    = $_POST['alamat'];
    $fakultas  = $_POST['fakultas'];

    if ($nim && $nama && $alamat && $fakultas) {
        if ($id) {
            // Update data
            $sql1 = "UPDATE mahasiswa SET nim='$nim', nama='$nama', alamat='$alamat', fakultas='$fakultas' WHERE id='$id'";
            $q1   = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Data berhasil diperbarui";
            } else {
                $error = "Gagal memperbarui data";
            }
        } else {
            // Insert data baru
            $sql1 = "INSERT INTO mahasiswa (nim, nama, alamat, fakultas) VALUES ('$nim', '$nama', '$alamat', '$fakultas')";
            $q1   = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Berhasil memasukkan data baru";
            } else {
                $error = "Gagal memasukkan data";
            }
        }
    } else {
        $error = "Silakan masukkan semua data";
    }
}

// Untuk tombol Edit
if (isset($_GET['op']) && $_GET['op'] == 'edit') {
    $id    = $_GET['id'];
    $sql1  = "SELECT * FROM mahasiswa WHERE id = '$id'";
    $q1    = mysqli_query($koneksi, $sql1);
    $r1    = mysqli_fetch_array($q1);

    $nim       = $r1['nim'];
    $nama      = $r1['nama'];
    $alamat    = $r1['alamat'];
    $fakultas  = $r1['fakultas'];
}

// Untuk tombol Delete
if (isset($_GET['op']) && $_GET['op'] == 'delete') {
    $id    = $_GET['id'];
    $sql1  = "DELETE FROM mahasiswa WHERE id = '$id'";
    $q1    = mysqli_query($koneksi, $sql1);
    if ($q1) {
        $sukses = "Data berhasil dihapus";
    } else {
        $error = "Gagal menghapus data";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data mahasiswa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        .mx-auto { width: 800px; }
        .card { margin-top: 10px; }
    </style>
</head>
<body>
    <div class="mx-auto">
        <!-- untuk memasukan data -->
        <div class="card">
            <div class="card-header">
                Create / Edit Data
            </div>
            <div class="card-body">
                <?php if ($error) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
                <?php } ?>
                <?php if ($sukses) { ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses; ?>
                    </div>
                <?php } ?>
                <form action="" method="POST">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="form-group row">
                        <label for="nim" class="col-sm-2 col-form-label">NIM</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nim" name="nim" value="<?php echo $nim; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $alamat; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fakultas" class="col-sm-2 col-form-label">Fakultas</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="fakultas" id="fakultas">
                                <option value="">- Pilih Fakultas -</option>
                                <option value="saintek" <?php if ($fakultas == "saintek") echo "selected"; ?>>Saintek</option>
                                <option value="shosum" <?php if ($fakultas == "shosum") echo "selected"; ?>>Shosum</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary" />
                    </div>
                </form>
            </div>
        </div>

        <!-- untuk mengeluarkan data -->
        <div class="card">
            <div class="card-header text-white bg-secondary">
                Data Mahasiswa
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Fakultas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2 = "SELECT * FROM mahasiswa ORDER BY id ASC";
                        $q2   = mysqli_query($koneksi, $sql2);
                        $no   = 1;

                        while ($r2 = mysqli_fetch_array($q2)) {
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $r2['nim']; ?></td>
                                <td><?php echo $r2['nama']; ?></td>
                                <td><?php echo $r2['alamat']; ?></td>
                                <td><?php echo $r2['fakultas']; ?></td>
                                <td>
                                    <a href="?op=edit&id=<?php echo $r2['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="?op=delete&id=<?php echo $r2['id']; ?>" onclick="return confirm('Yakin mau delete data?')" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
