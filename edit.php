<?php
include 'koneksi.php';

$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id='$id'");
$data = mysqli_fetch_array($query);

if (isset($_POST['update'])) {
    $nis = $_POST['nis'];
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];
    $foto_lama = $_POST['foto_lama'];
    
    $foto = $foto_lama;
    $pesan_error = '';
    
    if (isset($_FILES['foto']) && $_FILES['foto']['name'] != '') {
        $nama_file = $_FILES['foto']['name'];
        $ukuran_file = $_FILES['foto']['size'];
        $tmp_file = $_FILES['foto']['tmp_name'];
        $error_file = $_FILES['foto']['error'];
        $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        
        if ($error_file !== 0) {
            $pesan_error = "Error upload: " . $error_file;
        }
        
        $ekstensi_allowed = array('jpg', 'jpeg', 'png', 'gif');
        
        if (!in_array($ekstensi, $ekstensi_allowed)) {
            $pesan_error = 'Ekstensi file tidak diizinkan!';
        } elseif ($ukuran_file > 2000000) {
            $pesan_error = 'Ukuran file terlalu besar! Maksimal 2MB';
        } else {
            $folder = "images/";
            if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
            }
            
            if ($foto_lama != '' && file_exists($folder . $foto_lama)) {
                unlink($folder . $foto_lama);
            }
            
            $foto = time() . '_' . str_replace(' ', '_', $nama_file);
            $path = $folder . $foto;
            
            if (!move_uploaded_file($tmp_file, $path)) {
                $pesan_error = 'Gagal mengupload foto! Pastikan folder images/ dapat ditulis';
                $foto = $foto_lama;
            }
        }
    }
    
    if ($pesan_error != '') {
        echo "<script>alert('$pesan_error');</script>";
    }
    
    $query = "UPDATE siswa SET 
              nis='$nis', 
              nama='$nama', 
              jenis_kelamin='$jenis_kelamin', 
              telp='$telp', 
              alamat='$alamat', 
              foto='$foto' 
              WHERE id='$id'";
    
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Data berhasil diupdate!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Data gagal diupdate!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Data Siswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="tel"], textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 3px;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
            height: 80px;
        }
        input[type="file"] {
            padding: 5px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-right: 5px;
        }
        .btn-update {
            background-color: #2196F3;
            color: white;
        }
        .btn-batal {
            background-color: #f44336;
            color: white;
        }
        .info {
            font-size: 12px;
            color: #666;
            font-style: italic;
        }
        .foto-preview {
            margin-top: 10px;
            border: 1px solid #ddd;
            padding: 5px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Data Siswa</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="foto_lama" value="<?php echo $data['foto']; ?>">
            
            <div class="form-group">
                <label>NIS</label>
                <input type="text" name="nis" value="<?php echo $data['nis']; ?>" required maxlength="11">
            </div>
            
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" value="<?php echo $data['nama']; ?>" required maxlength="50">
            </div>
            
            <div class="form-group">
                <label>Jenis Kelamin</label>
                <input type="radio" name="jenis_kelamin" value="Laki-laki" <?php if($data['jenis_kelamin']=='Laki-laki') echo 'checked'; ?> required> Laki-laki
                <input type="radio" name="jenis_kelamin" value="Perempuan" <?php if($data['jenis_kelamin']=='Perempuan') echo 'checked'; ?> required> Perempuan
            </div>
            
            <div class="form-group">
                <label>Telepon</label>
                <input type="tel" name="telp" value="<?php echo $data['telp']; ?>" required maxlength="15">
            </div>
            
            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" required><?php echo $data['alamat']; ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Foto (Pas Foto 3x4)</label>
                <?php if ($data['foto'] != '') { ?>
                    <div class="foto-preview">
                        <img src="images/<?php echo $data['foto']; ?>" width="90" height="120">
                        <p class="info">Foto saat ini</p>
                    </div>
                <?php } ?>
                <input type="file" name="foto" accept="image/*">
                <p class="info">Kosongkan jika tidak ingin mengubah foto | Format: JPG, JPEG, PNG, GIF | Maksimal: 2MB</p>
            </div>
            
            <div class="form-group">
                <button type="submit" name="update" class="btn btn-update">Update</button>
                <a href="index.php" class="btn btn-batal">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>