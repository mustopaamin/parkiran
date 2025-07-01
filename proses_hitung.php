<?php
header('Content-Type: application/json');
require_once 'koneksi.php';
session_start();

if (!isset($_SESSION['user_id'])) {
	echo json_encode(array('status' => 500,'message' => 'Session Habis !!!'));
	exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$id_transaksi = $_POST['id_transaksi'] ?? null;
	
	if (!$id_transaksi) {
	    echo json_encode(['status' => 500, 'message' => 'ID Transaksi tidak ditemukan']);
	    exit;
	}

	$stmt = $pdo->prepare("SELECT id_kendaraan, waktu_masuk FROM transaksi_parkir WHERE id_transaksi = ?");
	$stmt->execute([$id_transaksi]);
	$transaksi = $stmt->fetch();
	
	if (!$transaksi) {
	    echo json_encode(array('status' => 500, 'message' => 'Data transaksi tidak ditemukan'));
	    exit;
	}

	$id_kendaraan = $transaksi['id_kendaraan'];
	$waktu_masuk = new DateTime($transaksi['waktu_masuk']);
	$waktu_keluar = new DateTime();
	
	$selisih_jam = ceil(($waktu_keluar->getTimestamp() - $waktu_masuk->getTimestamp()) / 3600);

	$stmt2 = $pdo->prepare("SELECT jenis_kendaraan FROM kendaraan WHERE id_kendaraan = ?");
	$stmt2->execute([$id_kendaraan]);
	$kendaraan = $stmt2->fetch();
	
	if (!$kendaraan) {
	    echo json_encode(array('status' => false, 'message' => 'Data kendaraan tidak ditemukan'));
	    exit;
	}
	
	$tarif_per_jam = strtolower($kendaraan['jenis_kendaraan']) == 'motor' ? 3000 : 5000;
	$biaya = $selisih_jam * $tarif_per_jam;	


	$stmt3 = $pdo->prepare("UPDATE transaksi_parkir SET waktu_keluar = ?, biaya = ? WHERE id_transaksi = ?");
	$updated = $stmt3->execute([$waktu_keluar->format('Y-m-d H:i:s'), $biaya, $id_transaksi]);

	if ($updated) {
	    echo json_encode(
			array(
	        'status' => true,
	        'message' => 'Biaya parkir dihitung',
	        'data' => array(
	            'waktu_masuk' => $waktu_masuk->format('Y-m-d H:i:s'),
	            'waktu_keluar' => $waktu_keluar->format('Y-m-d H:i:s'),
	            'durasi_jam' => $selisih_jam,
	            'jenis_kendaraan' => $kendaraan['jenis_kendaraan'],
	            'biaya' => $biaya
				)
			)
	    );
	} else {
	    echo json_encode(['status' => false, 'message' => 'Gagal update transaksi']);
	}

} else {
	echo json_encode(array('status' => 500,'message' => 'METHOD Salah !!!'));
}
?>
