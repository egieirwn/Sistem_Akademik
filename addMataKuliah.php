<?php
include('koneksi.php');

if (isset($_POST['nama_matakuliah'])) {
    $program_studi = $_POST['program_studi'];
    $nama_matakuliah = $_POST['nama_matakuliah'];
    $jumlah_sks = $_POST['jumlah_sks'];
    $dosen = $_POST['dosen'];

    $sql = "INSERT INTO matakuliah (id_program_studi, nama_mata_kuliah, jumlah_sks, id_dosen) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isii', $program_studi, $nama_matakuliah, $jumlah_sks, $dosen);

    if ($stmt->execute()) {
        header('Location: matakuliah.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>