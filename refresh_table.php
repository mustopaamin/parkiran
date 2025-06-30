<?php
//error_reporting(-1);
//ini_set('display_errors', 1);

require_once 'koneksi.php';
session_start();

if (!isset($_SESSION['user_id'])) {
	echo json_encode(array('status' => 500,'message' => 'Session Habis !!!'));
	exit;
}

try {
    $sql = "SELECT a.id_transaksi, a.id_kendaraan, a.waktu_masuk, a.waktu_keluar, a.biaya, b.nomor_polisi, b.jenis_kendaraan 
            FROM transaksi_parkir a
            join kendaraan b on b.id_kendaraan=a.id_kendaraan
            ";
	if($_GET['nomor_polisi']) $sql .= "where b.nomor_polisi like '%".$_GET['nomor_polisi']."%'";
	$sql .= " ORDER BY a.id_transaksi DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Terjadi kesalahan: " . $e->getMessage());
}
?>
<?php if (!empty($data)): ?>
	<?php 
		$i = 1;
		foreach ($data as $row):
	?>
		<tr class="tr_<?= $row['id_transaksi']; ?>">
			<td><?= $i; ?></td>
			<td><?= htmlspecialchars($row['nomor_polisi']); ?></td>
			<td><?= htmlspecialchars(ucfirst($row['jenis_kendaraan'])); ?></td>
			<td><?= formatTanggalTime($row['waktu_masuk']); ?></td>
			<?php 
				if($row['waktu_keluar'])
					$button = formatTanggalTime($row['waktu_keluar']);
				else
					$button = '<button class="btn btn-danger" onClick=fnKeluar('.$row['id_transaksi'].')>Keluar <i class="fas fa-sign-out-alt"></i></button>';
			?>
			<td><?= $button; ?></td>
			<td><?= number_format($row['biaya'],0,',','.'); ?></td>
		</tr>
	<?php 
		$i++;
		endforeach; 
	?>
<?php else: ?>
	<tr>
		<td colspan="7" class="text-center">Tidak ada data yang ditemukan</td>
	</tr>
<?php endif; ?>						  
