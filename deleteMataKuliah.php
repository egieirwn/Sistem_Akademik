<?php
include('koneksi.php');

if (isset($_POST['matakuliah_id'])) {
    $id = $_POST['matakuliah_id'];

    $sql = "DELETE FROM matakuliah WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        header('Location: matakuliah.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
