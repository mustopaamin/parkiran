<?php
header('Content-Type: application/json');
require_once 'koneksi.php';
session_start();

$username = htmlspecialchars($_POST['username']) ?? '';
$password = md5(htmlspecialchars($_POST['password'])) ?? '';

try {
    $query = "SELECT * FROM users WHERE username = :username AND password = :password";
    $stmt = $pdo->prepare($query);

    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);

    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        echo json_encode(array('status'=> 200,'message'=>'ok'));
        exit;
    } else {
        echo json_encode(array('status'=> 500,'message'=>'Username atau password salah'));
        exit;
    }
} catch (PDOException $e) {
	echo json_encode(array('status'=> 500,'message'=> 'Query error: ' . $e->getMessage()));
	exit;
}
?>
