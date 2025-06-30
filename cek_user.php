<?php
// Menyertakan koneksi database
header('Content-Type: application/json');
require_once 'koneksi.php';

// Memulai session
session_start();

// Contoh data username dan password (misalnya dari form login)
$username = htmlspecialchars($_POST['username']) ?? '';
$password = md5(htmlspecialchars($_POST['password'])) ?? '';

try {
    // Menyiapkan query untuk memeriksa tabel user
    $query = "SELECT * FROM users WHERE username = :username AND password = :password";
    $stmt = $pdo->prepare($query);

    // Binding parameter
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);

    // Menjalankan query
    $stmt->execute();

    // Mengecek apakah data ditemukan
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC); // Mengambil data user

        // Set session untuk user
        $_SESSION['user_id'] = $user['id']; // ID user dari database
        $_SESSION['username'] = $user['username']; // Username dari database
        $_SESSION['role'] = $user['role']; // Username dari database

        // Redirect ke halaman dashboard
        //header("Location: dashboard.php");
        echo json_encode(array('status'=> 200,'message'=>'ok'));
        exit;
    } else {
        //echo "Username atau password salah.";
        echo json_encode(array('status'=> 500,'message'=>'Username atau password salah'));
        exit;
    }
} catch (PDOException $e) {
    //echo "Query error: " . $e->getMessage();
	echo json_encode(array('status'=> 500,'message'=> 'Query error: ' . $e->getMessage()));
	exit;
}
?>
