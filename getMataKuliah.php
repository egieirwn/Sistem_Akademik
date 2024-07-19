<?php
include('koneksi.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT matakuliah.id, fakultas.id AS id_fakultas, program_studi.id AS id_program_studi, matakuliah.nama_mata_kuliah, matakuliah.jumlah_sks, dosen.id AS id_dosen
                            FROM matakuliah 
                            INNER JOIN program_studi ON matakuliah.id_program_studi = program_studi.id
                            INNER JOIN dosen ON matakuliah.id_dosen = dosen.id
                            INNER JOIN fakultas ON program_studi.id_fakultas = fakultas.id
                            WHERE matakuliah.id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($id, $id_fakultas, $id_program_studi, $nama_mata_kuliah, $jumlah_sks, $id_dosen);
    $stmt->fetch();

    $mata_kuliah = array(
        "id" => $id,
        "id_fakultas" => $id_fakultas,
        "id_program_studi" => $id_program_studi,
        "nama_mata_kuliah" => $nama_mata_kuliah,
        "jumlah_sks" => $jumlah_sks,
        "id_dosen" => $id_dosen
    );

    $stmt->close();
    $conn->close();

    echo json_encode($mata_kuliah);
}
?>
