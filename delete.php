<?php
// Menghubungkan ke database
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "pgwebacara8"; 

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mendapatkan kecamatan yang akan dihapus melalui metode GET
if (isset($_GET['kecamatan'])) {
    $kecamatan = $conn->real_escape_string($_GET['kecamatan']); // Mengamankan input dari pengguna

    // Membuat query DELETE
    $sql = "DELETE FROM kab_sleman WHERE kecamatan = ?";

    // Menyiapkan statement
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $kecamatan); // Menggunakan 's' untuk string

        // Mengeksekusi statement
        if ($stmt->execute()) {
            echo "Data berhasil dihapus. <a href='index.php'>Kembali ke halaman utama</a>";
        } else {
            echo "Gagal menghapus data: " . $stmt->error; // Menampilkan error dari statement
        }

        // Menutup statement
        $stmt->close();
    } else {
        echo "Gagal menyiapkan statement: " . $conn->error;
    }
} else {
    echo "Kecamatan tidak valid.";
}

// Menutup koneksi
$conn->close();
?>
