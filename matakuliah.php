<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Memastikan hanya admin yang dapat mengakses halaman ini
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Memasukkan file koneksi.php
include("koneksi.php");

// Query untuk mengambil data mata kuliah beserta nama fakultas dan program studi
$sql = "SELECT matakuliah.id, fakultas.nama_fakultas, program_studi.nama_program_studi, matakuliah.nama_mata_kuliah, matakuliah.jumlah_sks, dosen.nama_dosen
        FROM matakuliah 
        INNER JOIN program_studi ON matakuliah.id_program_studi = program_studi.id
        INNER JOIN dosen ON matakuliah.id_dosen = dosen.id
        INNER JOIN fakultas ON program_studi.id_fakultas = fakultas.id";

$result = $conn->query($sql);

// Query untuk mengambil data fakultas
$sql_fakultas = "SELECT id, nama_fakultas FROM fakultas";
$result_fakultas = $conn->query($sql_fakultas);

// Query untuk mengambil data program studi
$sql_program_studi = "SELECT id, nama_program_studi FROM program_studi";
$result_program_studi = $conn->query($sql_program_studi);

// Query untuk mengambil data dosen
$sql_dosen = "SELECT id, nama_dosen FROM dosen";
$result_dosen = $conn->query($sql_dosen);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mata Kuliah - Siakad</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="matakuliah.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <h2>Siakad</h2>
        </div>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="fakultas.php"><i class="fas fa-building"></i> Fakultas</a></li>
            <li><a href="programstudi.php"><i class="fas fa-graduation-cap"></i> Program Studi</a></li>
            <li><a href="matakuliah.php" class="active"><i class="fas fa-book"></i> Mata Kuliah</a></li>
            <li><a href="dosen.php"><i class="fas fa-chalkboard-teacher"></i> Daftar Dosen</a></li>
            <li><a href="mahasiswa.php"><i class="fas fa-user-graduate"></i> Mahasiswa</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <header>
            <button id="sidebar-toggle"><i class="fas fa-bars"></i></button>
            <div class="user-wrapper">
                <img src="user.png" alt="User" width="30" height="30">
                <div>
                    <h4>Admin</h4>
                    <small>Connected</small>
                </div>
            </div>
        </header>
        <main>
            <div class="content-container card">
                <h2>Daftar Mata Kuliah</h2>
                <button class="add" onclick="openModal()">Tambah Data Mata Kuliah</button>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Fakultas</th>
                            <th>Nama Program Studi</th>
                            <th>Nama Mata Kuliah</th>
                            <th>Jumlah SKS</th>
                            <th>Dosen Pengampu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            $no = 1;
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$no}</td>
                                        <td>{$row['nama_fakultas']}</td>
                                        <td>{$row['nama_program_studi']}</td>
                                        <td>{$row['nama_mata_kuliah']}</td>
                                        <td>{$row['jumlah_sks']}</td>
                                        <td>{$row['nama_dosen']}</td>
                                        <td>
                                            <div class='action-buttons'>
                                                <button class='add' type='button' onclick=\"openModal(true, {$row['id']}, '{$row['nama_fakultas']}', '{$row['nama_program_studi']}', '{$row['nama_mata_kuliah']}', '{$row['jumlah_sks']}', '{$row['nama_dosen']}')\">Edit</button>
                                                <form action='deleteMataKuliah.php' method='post' onsubmit=\"return confirm('Apakah Anda yakin ingin menghapus data ini?')\">
                                                    <input type='hidden' name='matakuliah_id' value='{$row['id']}'>
                                                    <button class='add' type='submit' name='delete'>Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>";
                                $no++;
                            }
                        } else {
                            echo "<tr><td colspan='7'>Tidak ada data</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <div class="empty-state">
                    Tidak ada data mata kuliah saat ini.
                </div>
            </div>
        </main>
    </div>

    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modal-title">Tambah Data Mata Kuliah</h2>
            <form id="form-matakuliah" action="addMataKuliah.php" method="post">
                <input type="hidden" id="matakuliah-id" name="matakuliah_id">
                <label for="fakultas">Nama Fakultas:</label>
                <select id="fakultas" name="fakultas" required>
                    <option value="">Pilih Fakultas</option>
                    <?php
                    if ($result_fakultas->num_rows > 0) {
                        while ($row_fakultas = $result_fakultas->fetch_assoc()) {
                            echo "<option value='{$row_fakultas['id']}'>{$row_fakultas['nama_fakultas']}</option>";
                        }
                    }
                    ?>
                </select>

                <label for="program_studi">Nama Program Studi:</label>
                <select id="program_studi" name="program_studi" required>
                    <option value="">Pilih Program Studi</option>
                    <?php
                    if ($result_program_studi->num_rows > 0) {
                        while ($row_program_studi = $result_program_studi->fetch_assoc()) {
                            echo "<option value='{$row_program_studi['id']}'>{$row_program_studi['nama_program_studi']}</option>";
                        }
                    }
                    ?>
                </select>

                <label for="nama_matakuliah">Nama Mata Kuliah:</label>
                <input type="text" id="nama_matakuliah" name="nama_matakuliah" required>

                <label for="jumlah_sks">Jumlah SKS:</label>
                <input type="number" id="jumlah_sks" name="jumlah_sks" required>

                <label for="dosen">Dosen Pengampu:</label>
                <select id="dosen" name="dosen" required>
                    <option value="">Pilih Dosen</option>
                    <?php
                    if ($result_dosen->num_rows > 0) {
                        while ($row_dosen = $result_dosen->fetch_assoc()) {
                            echo "<option value='{$row_dosen['id']}'>{$row_dosen['nama_dosen']}</option>";
                        }
                    }
                    ?>
                </select>

                <button class="submit" type="submit" id="submit-button">Submit</button>
            </form>
        </div>
    </div>

    <script>
    function openModal(edit = false, id = null, fakultas = '', program_studi = '', nama_matakuliah = '', jumlah_sks = '', dosen = '') {
        document.getElementById('modal').style.display = 'block';
        document.getElementById('form-matakuliah').action = edit ? 'editMataKuliah.php' : 'addMataKuliah.php';
        document.getElementById('matakuliah-id').value = edit ? id : '';
        document.getElementById('fakultas').value = edit ? fakultas : '';
        document.getElementById('program_studi').value = edit ? program_studi : '';
        document.getElementById('nama_matakuliah').value = edit ? nama_matakuliah : '';
        document.getElementById('jumlah_sks').value = edit ? jumlah_sks : '';
        document.getElementById('dosen').value = edit ? dosen : '';
        document.getElementById('modal-title').innerText = edit ? 'Edit Data Mata Kuliah' : 'Tambah Data Mata Kuliah';
        document.getElementById('submit-button').innerText = edit ? 'Update' : 'Tambah';
    }

    document.getElementById('sidebar-toggle').addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('hidden');
        document.querySelector('.main-content').classList.toggle('shifted');
    });

    function closeModal() {
        document.getElementById('modal').style.display = 'none';
    }

    function scrollToTop() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    </script>
    
    <button class="tombol" onclick="scrollToTop()">
        <span class="svgIcon"><i class="fas fa-arrow-up"></i></span>
    </button>

</body>
<footer class="footer">
    <div class="footer-content">
        <p>💀&copy;2024 SIAKAD. All rights reserved.💀</p>
        <p>
            <a href="#">Privacy Policy</a> |
            <a href="#">Terms of Service</a> |
            <a href="#">Contact Us</a>
        </p>
    </div>
</footer>
</html>
