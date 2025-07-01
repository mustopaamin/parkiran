<?php
require_once 'koneksi.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

try {
    $sql = "SELECT id_kendaraan, nomor_polisi, jenis_kendaraan, akhir_masuk, akhir_keluar, created_at 
            FROM kendaraan";
	
	$sql .= " ORDER BY id_kendaraan DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Terjadi kesalahan: " . $e->getMessage());
}
require_once 'header.php';
?>
<body class="layout-top-nav control-sidebar-slide-open layout-navbar-fixed">

<div class="wrapper">

  <!-- Navbar -->
<?php menu_navbar('kendaraan'); ?>  
  <!-- /.navbar -->

  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container">
        <div class="row mb-1">
          <div class="col-sm-6">
            <h1 class="m-0">Data Kendaraan</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container">
        <div class="row">
          <div class="col-12">
			  
            <div class="card">
              <div class="card-body">
				  <table id="data_pasien" class="table table-bordered table-hover" style="width:100%">
					  <thead>
						  <tr>
							  <th width="5%">No</th>
							  <th width="20%">Nomor Kendaraan</th>
							  <th width="27%">Jenis Kendaraan</th>
							  <th width="16%">Akhir Masuk</th>
							  <th width="16%">Akhir Keluar</th>
							  <th width="16%">Awal Masuk</th>
						  </tr>
					  </thead>
					  <tbody>
        <?php if (!empty($data)): ?>
            <?php 
				$i = 1;
				foreach ($data as $row):
			?>
                <tr>
                    <td><?= $i; ?></td>
                    <td><?= htmlspecialchars($row['nomor_polisi']); ?></td>
                    <td><?= htmlspecialchars(ucfirst($row['jenis_kendaraan'])); ?></td>
                    <td><?= formatTanggalTime($row['akhir_masuk']); ?></td>
                    <td><?= formatTanggalTime($row['akhir_keluar']); ?></td>
                    <td><?= formatTanggalTime($row['created_at']); ?></td>
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
					  </tbody>
				  </table>
              </div>
            </div>

          </div>

        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      Kelompok 7
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2024 All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- DataTables  & Plugins -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<script>
  $(function () {
    $('#data_pasien').DataTable({
      "order": [[1, 'desc']],
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>

</body>
</html>
