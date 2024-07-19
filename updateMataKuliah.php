<?php
include('koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mata_kuliah_id = $_POST['mata_kuliah_id'];
    $fakultas = $_POST['fakultas'];
    $program_studi = $_POST['program_studi'];
    $nama_mata_kuliah = $_POST['matakuliah'];
    $jumlah_sks = $_POST['jumlah_sks'];
    $dosen = $_POST['dosen'];

    $stmt = $conn->prepare("UPDATE matakuliah SET id_program_studi = ?, nama_mata_kuliah = ?, jumlah_sks = ?, id_dosen = ? WHERE id = ?");
    $stmt->bind_param('isiii', $program_studi, $nama_mata_kuliah, $jumlah_sks, $dosen, $mata_kuliah_id);
    if ($stmt->execute()) {
        header('Location: matakuliah.php');
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
