<?php
// Mengatur koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_formporto";

// Membuat koneksi
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Memeriksa koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Mendapatkan nilai-nilai dari formulir
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$message = $_POST['message'];

// Melakukan sanitasi data (opsional, karena kita menggunakan parameterized query)
$name = mysqli_real_escape_string($conn, $name);
$email = mysqli_real_escape_string($conn, $email);
$phone = mysqli_real_escape_string($conn, $phone);
$message = mysqli_real_escape_string($conn, $message);

// Menyimpan data ke database dengan menggunakan parameterized query
$sql = "INSERT INTO contacts (name, email, phone, message) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $phone, $message);

if (mysqli_stmt_execute($stmt)) {
    $response = array(
        'status' => 'success',
        'message' => 'Formulir berhasil dikirim!'
    );
} else {
    $response = array(
        'status' => 'error',
        'message' => 'Terjadi kesalahan saat mengirim formulir.'
    );
}

// Menutup statement
mysqli_stmt_close($stmt);

// Menutup koneksi
mysqli_close($conn);

// Mengirim respons sebagai JSON
header('Content-Type: application/json');
echo json_encode($response);
