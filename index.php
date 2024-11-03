<?php
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

// Periksa jika ada permintaan hapus
if (isset($_POST['delete'])) {
    $id = $_POST['id']; // Ambil ID dari form hapus
    $deleteSql = "DELETE FROM penduduk WHERE id = $id"; // Asumsi ada kolom id yang unik
    if ($conn->query($deleteSql) === TRUE) {
        echo "<script>alert('Data berhasil dihapus');</script>";
    } else {
        echo "Error: " . $conn->error; 
    }
}

// Query untuk mendapatkan data
$sql = "SELECT * FROM penduduk";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penduduk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3e5f5; /* Warna latar belakang ungu muda */
            margin: 0;
            padding: 20px;
        }
        
        h2 {
            text-align: center;
            color: #4a148c; /* Warna judul ungu tua */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ce93d8; /* Border ungu muda */
        }

        th {
            background-color: #ba68c8; /* Warna header ungu sedang */
            color: white;
        }

        tr:nth-child(even) {
            background-color: #e1bee7; /* Warna baris genap ungu pastel */
        }

        tr:hover {
            background-color: #ce93d8; /* Warna saat hover ungu lebih tua */
        }

        .no-results {
            text-align: center;
            color: #888;
        }

        .delete-btn {
            background-color: #f06292; /* Warna tombol hapus pink */
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .delete-btn:hover {
            background-color: #e91e63; /* Warna pink lebih gelap saat hover */
        }

        a {
            color: #4a148c; /* Warna ungu untuk tautan */
            text-decoration: none;
        }

        a:hover {
            color: #6a1b9a; /* Warna ungu lebih gelap saat hover */
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h2>Data Penduduk</h2>

<?php
if ($result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>Kecamatan</th>
                <th>Longitude</th>
                <th>Latitude</th>
                <th>Luas (ha)</th>
                <th>Jumlah Penduduk</th>
                <th>Aksi</th>
            </tr>";
    // Menampilkan setiap baris data
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row["kecamatan"]) . "</td>
                <td>" . htmlspecialchars($row["longitude"]) . "</td>
                <td>" . htmlspecialchars($row["latitude"]) . "</td>
                <td>" . htmlspecialchars($row["luas"]) . "</td>
                <td align='right'>" . htmlspecialchars($row["jumlah_penduduk"]) . "</td>
                <td>
                    <form method='post' action=''>
                        <input type='hidden' name='id' value='" . htmlspecialchars($row["id"]) . "'>
                        <input type='submit' name='delete' value='Hapus' class='delete-btn'>
                    </form>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p class='no-results'>0 results</p>";
}

$conn->close();
?>

</body>
</html>
