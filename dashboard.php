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

// Proses penambahan data
if (isset($_POST['submit'])) {
    $kecamatan = $_POST['kecamatan'];
    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];
    $luas = $_POST['luas'];
    $jumlah_penduduk = $_POST['jumlah_penduduk'];

    $sql = "INSERT INTO penduduk (kecamatan, longitude, latitude, luas, jumlah_penduduk) 
            VALUES ('$kecamatan', $longitude, $latitude, $luas, $jumlah_penduduk)";
    
    if ($conn->query($sql) === TRUE) {
        echo "Data baru berhasil ditambahkan!";
    } else {
        echo "Error: " . $conn->error;
    }
    exit;
}

// Proses pengeditan data
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $kecamatan = $_POST['kecamatan'];
    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];
    $luas = $_POST['luas'];
    $jumlah_penduduk = $_POST['jumlah_penduduk'];

    $editSql = "UPDATE penduduk SET kecamatan='$kecamatan', longitude=$longitude, latitude=$latitude, luas=$luas, jumlah_penduduk=$jumlah_penduduk WHERE id=$id";
    if ($conn->query($editSql) === TRUE) {
        echo "Data berhasil diperbarui!";
    } else {
        echo "Error: " . $conn->error;
    }
    exit;
}

// Proses penghapusan data
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $deleteSql = "DELETE FROM penduduk WHERE id = $id";
    if ($conn->query($deleteSql) === TRUE) {
        echo "<script>alert('Data berhasil dihapus');</script>";
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
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
    <title>Dashboard Data Penduduk</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3e5f5;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #4a148c;
        }
        h3 {
            text-align: center;
            color: #c2185b;
            font-weight: normal;
            margin-top: -10px;
        }
        #tableContainer {
            max-width: 100%;
            margin: 0 auto;
            padding-top: 20px;
        }
        #dataTable {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ce93d8;
        }
        th {
            background-color: #ba68c8;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #e1bee7;
        }
        .delete-btn, .edit-btn {
            background-color: #f06292;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        .delete-btn:hover {
            background-color: #e91e63;
        }
        .edit-btn {
            background-color: #64b5f6;
            margin-left: 5px;
        }
        .edit-btn:hover {
            background-color: #2196f3;
        }
        #map {
            height: 400px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<h2>Dashboard Data Penduduk</h2>
<h3>Kabupaten Sleman</h3>

<!-- Peta -->
<div id="map"></div>

<!-- Pembungkus Tabel Data Penduduk -->
<div id="tableContainer">
    <table id="dataTable">
        <tr>
            <th>Kecamatan</th>
            <th>Longitude</th>
            <th>Latitude</th>
            <th>Luas (ha)</th>
            <th>Jumlah Penduduk</th>
            <th>Aksi</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row["kecamatan"]) . "</td>
                        <td>" . htmlspecialchars($row["longitude"]) . "</td>
                        <td>" . htmlspecialchars($row["latitude"]) . "</td>
                        <td>" . htmlspecialchars($row["luas"]) . "</td>
                        <td align='right'>" . htmlspecialchars($row["jumlah_penduduk"]) . "</td>
                        <td>
                            <form method='post' action='' style='display:inline-block;'>
                                <input type='hidden' name='id' value='" . htmlspecialchars($row["id"]) . "'>
                                <input type='submit' name='delete' value='Hapus' class='delete-btn'>
                            </form>
                            <button class='edit-btn' onclick='editData(" . $row["id"] . ", \"" . addslashes($row["kecamatan"]) . "\", " . $row["longitude"] . ", " . $row["latitude"] . ", " . $row["luas"] . ", " . $row["jumlah_penduduk"] . ")'>Edit</button>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6' align='center'>Tidak ada data tersedia</td></tr>";
        }
        ?>
    </table>
</div>


<!-- Script untuk Leaflet dan SweetAlert -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<script>
    var map = L.map('map').setView([-7.7956, 110.3695], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    function addMarker(lat, lng, kecamatan) {
        var marker = L.marker([lat, lng]).addTo(map);
        marker.bindPopup(kecamatan).openPopup();
    }

    <?php
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "addMarker(" . $row['latitude'] . ", " . $row['longitude'] . ", '" . addslashes($row['kecamatan']) . "');";
        }
    }
    ?>

    function editData(id, kecamatan, longitude, latitude, luas, jumlahPenduduk) {
        Swal.fire({
            title: 'Edit Data Penduduk',
            html: `
                <input type="text" id="edit-kecamatan" class="swal2-input" placeholder="Kecamatan" value="${kecamatan}">
                <input type="number" id="edit-luas" class="swal2-input" placeholder="Luas (ha)" value="${luas}">
                <input type="number" id="edit-jumlah-penduduk" class="swal2-input" placeholder="Jumlah Penduduk" value="${jumlahPenduduk}">
            `,
            focusConfirm: false,
            preConfirm: () => {
                const newKecamatan = document.getElementById('edit-kecamatan').value;
                const newLuas = document.getElementById('edit-luas').value;
                const newJumlahPenduduk = document.getElementById('edit-jumlah-penduduk').value;

                return fetch('dashboard.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `edit=true&id=${id}&kecamatan=${newKecamatan}&longitude=${longitude}&latitude=${latitude}&luas=${newLuas}&jumlah_penduduk=${newJumlahPenduduk}`
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(response.statusText);
                    }
                    return response.text
                    return response.text();
                })
                .then(result => {
                    // Memperbarui tampilan tabel dengan data baru
                    location.reload();
                    Swal.fire('Data berhasil diperbarui!', result);
                })
                .catch(error => {
                    Swal.fire('Error', error.message, 'error');
                });
            }
        });
    }

    // Menangani klik pada peta
    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;

        // Menampilkan popup untuk input data
        Swal.fire({
            title: 'Tambah Data Penduduk',
            html: `
                <input type="text" id="kecamatan" class="swal2-input" placeholder="Kecamatan">
                <input type="number" id="luas" class="swal2-input" placeholder="Luas (ha)">
                <input type="number" id="jumlah_penduduk" class="swal2-input" placeholder="Jumlah Penduduk">
            `,
            focusConfirm: false,
            preConfirm: () => {
                const kecamatan = document.getElementById('kecamatan').value;
                const luas = document.getElementById('luas').value;
                const jumlahPenduduk = document.getElementById('jumlah_penduduk').value;

                // Kirim data ke server dengan fetch API
                return fetch('dashboard.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `submit=true&kecamatan=${kecamatan}&longitude=${lng}&latitude=${lat}&luas=${luas}&jumlah_penduduk=${jumlahPenduduk}`
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(response.statusText);
                    }
                    return response.text();
                })
                .then(result => {
                    // Menambahkan marker ke peta
                    addMarker(lat, lng, kecamatan);

                    // Menambahkan data baru ke tabel tanpa reload
                    const newRow = `
                        <tr>
                            <td>${kecamatan}</td>
                            <td>${lng}</td>
                            <td>${lat}</td>
                            <td>${luas}</td>
                            <td>${jumlahPenduduk}</td>
                            <td>
                                <form method='post' action=''>
                                    <input type='hidden' name='id' value=''>
                                    <input type='submit' name='delete' value='Hapus' class='delete-btn'>
                                </form>
                                <button class='edit-btn' onclick='editData("", "${kecamatan}", ${lng}, ${lat}, ${luas}, ${jumlahPenduduk})'>Edit</button>
                            </td>
                        </tr>
                    `;
                    document.getElementById('dataTable').insertAdjacentHTML('beforeend', newRow);
                    Swal.fire('Data berhasil ditambahkan!', result);
                })
                .catch(error => {
                    Swal.fire('Error', error.message, 'error');
                });
            }
        });
    });
</script>

</body>
</html>
