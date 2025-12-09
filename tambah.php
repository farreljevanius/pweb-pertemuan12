<?php
include 'koneksi.php';

if (isset($_POST['simpan'])) {
    $nis = $_POST['nis'];
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];
    
    $foto = '';
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
            $pesan_error = 'Ekstensi file tidak diizinkan! Gunakan JPG, JPEG, PNG, atau GIF';
        } elseif ($ukuran_file > 2000000) {
            $pesan_error = 'Ukuran file terlalu besar! Maksimal 2MB';
        } else {
            $folder = "images/";
            if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
            }
            
            $foto = time() . '_' . str_replace(' ', '_', $nama_file);
            $path = $folder . $foto;
            
            if (move_uploaded_file($tmp_file, $path)) {
            } else {
                $pesan_error = 'Gagal mengupload foto! Pastikan folder images/ dapat ditulis';
                $foto = '';
            }
        }
    }
    
    if ($pesan_error != '') {
        echo "<script>alert('$pesan_error');</script>";
    }
    
    $query = "INSERT INTO siswa (nis, nama, jenis_kelamin, telp, alamat, foto) 
              VALUES ('$nis', '$nama', '$jenis_kelamin', '$telp', '$alamat', '$foto')";
    
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Data berhasil disimpan!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Data gagal disimpan! " . mysqli_error($koneksi) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Data Siswa</title>
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
        .btn-simpan {
            background-color: #4CAF50;
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
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Tambah Data Siswa</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>NIS</label>
                <input type="text" name="nis" required maxlength="11">
            </div>
            
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" required maxlength="50">
            </div>
            
            <div class="form-group">
                <label>Jenis Kelamin</label>
                <input type="radio" name="jenis_kelamin" value="Laki-laki" required> Laki-laki
                <input type="radio" name="jenis_kelamin" value="Perempuan" required> Perempuan
            </div>
            
            <div class="form-group">
                <label>Telepon</label>
                <input type="tel" name="telp" required maxlength="15">
            </div>
            
            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" required></textarea>
            </div>
            
            <div class="form-group">
                <label>Foto (Pas Foto 3x4)</label>
                <input type="file" name="foto" accept="image/*">
                <p class="info">Format: JPG, JPEG, PNG, GIF | Maksimal: 2MB</p>
            </div>
            
            <div class="form-group">
                <button type="submit" name="simpan" class="btn btn-simpan">Simpan</button>
                <a href="index.php" class="btn btn-batal">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>