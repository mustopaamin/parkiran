<?php
header('Content-Type: application/json');
require_once 'koneksi.php';
session_start();

if (!isset($_SESSION['user_id'])) {
	echo json_encode(array('status' => 500,'message' => 'Session Habis !!!'));
	exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $plat = $_POST['nomor_polisi'];
    $jenis = $_POST['jenis_kendaraan'];
    $waktu = date('Y-m-d H:i:s');

    if (empty($plat) || empty($jenis)) {
        echo json_encode(array('status' => 500, 'message' => 'Data tidak lengkap.'));
        exit;
    }

    $stmt = $pdo->prepare("SELECT id_kendaraan FROM kendaraan WHERE nomor_polisi = ? AND jenis_kendaraan = ?");
    $stmt->execute([$plat, $jenis]);
    $kendaraan = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($kendaraan) {
        $id_kendaraan = $kendaraan['id_kendaraan'];
        $update = $pdo->prepare("UPDATE kendaraan SET akhir_masuk = ? WHERE id_kendaraan = ?");
        $update->execute([$waktu, $id_kendaraan]);
    } else {
        $insert = $pdo->prepare("INSERT INTO kendaraan (nomor_polisi, jenis_kendaraan, akhir_masuk, created_at, created_by) VALUES (?, ?, ?, ?, ?)");
        $insert->execute([$plat, $jenis, $waktu, $waktu, 'admin']);
        $id_kendaraan = $pdo->lastInsertId();
    }

    $insert_trans = $pdo->prepare("INSERT INTO transaksi_parkir (id_kendaraan, waktu_masuk, created_at, created_by) VALUES (?, ?, ?, ?)");
    $success = $insert_trans->execute([$id_kendaraan, $waktu, $waktu, 1]);
    if ($success) {
        echo json_encode(array('status' => 200, 'message' => 'Data berhasil disimpan'));
    } else {
        echo json_encode(array('status' => 500, 'message' => 'Gagal menyimpan transaksi'));
    }    
} else {
	echo json_encode(array('status' => 500,'message' => 'METHOD Salah !!!'));
}
?>
