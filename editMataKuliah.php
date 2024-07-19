<?php
include('koneksi.php');

if (isset($_POST['matakuliah_id'])) {
    $id = $_POST['matakuliah_id'];
    $program_studi = $_POST['program_studi'];
    $nama_matakuliah = $_POST['nama_matakuliah'];
    $jumlah_sks = $_POST['jumlah_sks'];
    $dosen = $_POST['dosen'];

    $sql = "UPDATE matakuliah SET id_program_studi = ?, nama_mata_kuliah = ?, jumlah_sks = ?, id_dosen = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isiii', $program_studi, $nama_matakuliah, $jumlah_sks, $dosen, $id);

    if ($stmt->execute()) {
        header('Location: matakuliah.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
