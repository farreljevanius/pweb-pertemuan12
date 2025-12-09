<?php
include 'koneksi.php';

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    
    $query = mysqli_query($koneksi, "SELECT foto FROM siswa WHERE id='$id'");
    $data = mysqli_fetch_array($query);
    
    if ($data['foto'] != '' && file_exists("images/" . $data['foto'])) {
        unlink("images/" . $data['foto']);
    }
    
    mysqli_query($koneksi, "DELETE FROM siswa WHERE id='$id'");
    header("location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Siswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .btn {
            padding: 5px 10px;
            text-decoration: none;
            color: white;
            border-radius: 3px;
            margin-right: 5px;
        }
        .btn-tambah {
            background-color: #4CAF50;
        }
        .btn-edit {
            background-color: #2196F3;
        }
        .btn-hapus {
            background-color: #f44336;
        }
        img {
            border: 1px solid #ddd;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <h2>Data Siswa</h2>
    <a href="tambah.php" class="btn btn-tambah">+ Tambah Data Siswa</a>
    
    <table>
        <tr>
            <th>No</th>
            <th>NIS</th>
            <th>Nama</th>
            <th>Jenis Kelamin</th>
            <th>Telepon</th>
            <th>Alamat</th>
            <th>Foto</th>
            <th>Aksi</th>
        </tr>
        
        <?php
        $no = 1;
        $query = mysqli_query($koneksi, "SELECT * FROM siswa ORDER BY id DESC");
        while ($data = mysqli_fetch_array($query)) {
        ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $data['nis']; ?></td>
            <td><?php echo $data['nama']; ?></td>
            <td><?php echo $data['jenis_kelamin']; ?></td>
            <td><?php echo $data['telp']; ?></td>
            <td><?php echo $data['alamat']; ?></td>
            <td>
                <?php if ($data['foto'] != '') { ?>
                    <img src="images/<?php echo $data['foto']; ?>" width="60" height="80">
                <?php } else { ?>
                    <span>Tidak ada foto</span>
                <?php } ?>
            </td>
            <td>
                <a href="edit.php?id=<?php echo $data['id']; ?>" class="btn btn-edit">Edit</a>
                <a href="index.php?hapus=<?php echo $data['id']; ?>" class="btn btn-hapus" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>