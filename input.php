<?php
// Pastikan data POST tersedia
$kecamatan = isset($_POST['kecamatan']) ? $_POST['kecamatan'] : '';
$longitude = isset($_POST['longitude']) ? $_POST['longitude'] : '';
$latitude = isset($_POST['latitude']) ? $_POST['latitude'] : '';
$luas = isset($_POST['luas']) ? $_POST['luas'] : '';
$jumlah_penduduk = isset($_POST['jumlah_penduduk']) ? $_POST['jumlah_penduduk'] : '';

// Cek apakah semua data diisi
if (empty($kecamatan) || empty($longitude) || empty($latitude) || empty($luas) || empty($jumlah_penduduk)) {
    die("Semua field harus diisi!");
}

// Konfigurasi MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pgwebacara8";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query untuk memasukkan data
$sql = "INSERT INTO penduduk (kecamatan, longitude, latitude, luas, jumlah_penduduk) 
        VALUES ('$kecamatan', $longitude, $latitude, $luas, $jumlah_penduduk)";

if ($conn->query($sql) === TRUE) {
    echo "Data baru berhasil ditambahkan";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>