<?php
require_once 'koneksi.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
require_once 'header.php';
try {
    $sql = "SELECT a.id_transaksi, a.id_kendaraan, a.waktu_masuk, a.waktu_keluar, a.biaya, b.nomor_polisi, b.jenis_kendaraan 
            FROM transaksi_parkir a
            join kendaraan b on b.id_kendaraan=a.id_kendaraan
            ";
	
	$sql .= " ORDER BY a.id_transaksi DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Terjadi kesalahan: " . $e->getMessage());
}
?>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<body class="layout-top-nav control-sidebar-slide-open layout-navbar-fixed">
<div class="wrapper">

  <!-- Navbar -->
<?php menu_navbar('dashboard'); ?>  
  <!-- /.navbar -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container">
        <div class="row mb-1">
          <div class="col-sm-6">
            <h1 class="m-0">Halaman Utama</h1>
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
			  
<!--			  
			<div class="card collapsed-card">
              <div class="card-header bg-gradient-info border-0 ui-sortable-handle" style="cursor: move;">
                <h3 class="card-title">
                  <i class="fas fa-search mr-1"></i>
                  Cari Kendaraan
                </h3>
                <div class="card-tools">
                  <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                    <i class="fas fa-plus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body" style="display: none;">
              </div>
              <div class="card-footer bg-transparent">

              </div>
            </div>			  
-->			  
            <div class="card">
              <div class="card-header">
				  <h3 class="card-title">Data Kendaraan Masuk Keluar</h3>
              </div>
              <div class="card-body">
				  <div class="row">
					  <div class="col-md-6"><button class="btn btn-success" data-toggle="modal" data-target="#modalFormParkir"><i class="fas fa-sign-in-alt"></i> Masuk</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
					  <div class="col-md-6">
						  <div class="form-group row">
							  <div class="col-md-8">
								  <input type="text" id="nopol" class="form-control" placeholder="Masukan Nomor Polisi">
							  </div>
							  <div class="col-md-4"><button class="btn btn-primary btn-block" onClick="cari_kendaraan()"><i class="fas fa-search mr-1"></i> Cari</button></div>
						  </div>
					  </div>
				  </div>
				  
<!--
				  <button class="btn btn-danger">Keluar <i class="fas fa-sign-out-alt"></i></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
-->
				  <table id="data_pasien" class="table table-bordered table-hover mt-2" style="width:100%">
					  <thead>
						  <tr>
							  <th width="5%">No</th>
							  <th width="15%">Nomor Kendaraan</th>
							  <th width="15%">Jenis Kendaraan</th>
							  <th width="25%">Waktu Masuk</th>
							  <th width="25%">Waktu Keluar</th>
							  <th width="15%">Biaya</th>
						  </tr>
					  </thead>
					  <tbody class="data_kendaraan">
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

<!-- Modal -->
<div class="modal fade" id="modalFormParkir" tabindex="-1" role="dialog" aria-labelledby="modalLabelParkir" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="formKendaraan">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="modalLabelParkir">Form Kendaraan Masuk</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          
          <!-- Nomor Polisi -->
          <div class="form-group">
            <label for="nomor_polisi">Nomor Polisi</label>
            <input type="text" class="form-control" id="nomor_polisi" name="nomor_polisi" required>
          </div>

          <!-- Jenis Kendaraan -->
          <div class="form-group">
            <label for="jenis_kendaraan">Jenis Kendaraan</label>
            <select class="form-control" id="jenis_kendaraan" name="jenis_kendaraan" required>
              <option value="">-- Pilih Jenis --</option>
              <option value="mobil">Mobil</option>
              <option value="motor">Motor</option>
            </select>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Simpan</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>


  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      Mustopa Amin - 202243579089
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2024 All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<script>
$(document).ready(function(){
	$('#modalFormParkir').on('shown.bs.modal', function () {
	  $('#formKendaraan')[0].reset();
	});
	setInterval(updateJam, 1000);
	updateJam();
})	
function updateJam() {
	const now = new Date();
	const jam = now.toLocaleTimeString();
	$("#jam").text('[ '+jam+' ]');
}


function closeModal() {
  $('#modalFormParkir').modal('hide')
}

function refreshTable() {
  $.get('refresh_table.php',function(e){
	$('tbody.data_kendaraan').html(e);
  });
}

function cari_kendaraan() {
	var nopol = $('input#nopol').val();
  $.get('refresh_table.php?nomor_polisi='+nopol,function(e){
	$('tbody.data_kendaraan').html(e);
  });
}

function fnKeluar(x) {
	let kolom2 = $('.tr_'+x+' td:eq(1)').text();
	let kolom3 = $('.tr_'+x+' td:eq(2)').text();
	let text = "Yakin kendaraan "+kolom3+" dengan nopol "+kolom2+" ini keluar ?";
	if (confirm(text) == true) {
	    $.ajax({
	        url: 'proses_hitung.php',
	        type: 'POST',
	        data: { id_transaksi: x },
	        dataType: 'json',
	        success: function(response) {
	            if (response.status) {
	                alert("Biaya berhasil dihitung!\n\n" +
	                      "Waktu Masuk: " + response.data.waktu_masuk + "\n" +
	                      "Waktu Keluar: " + response.data.waktu_keluar + "\n" +
	                      "Durasi: " + response.data.durasi_jam + " jam\n" +
	                      "Jenis: " + response.data.jenis_kendaraan + "\n" +
	                      "Biaya: Rp " + response.data.biaya.toLocaleString('id-ID'));
	            } else {
	                alert("Gagal: " + response.message);
	            }
	            refreshTable();
	        },
	        error: function(xhr, status, error) {
	            alert("Terjadi kesalahan: " + error);
	        }
	    });

	}	
}

// XMLHttpRequest
document.getElementById("formKendaraan").addEventListener("submit", function(e) {
  e.preventDefault();

  const formData = new FormData(this);

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "proses_kendaraan.php", true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      try {
        var response = JSON.parse(xhr.responseText);
        console.log("Response:", response);

        if (response.status === 200) {
          alert("Berhasil disimpan!");
          refreshTable();
          form.reset();
          $('#modalKendaraan').modal('hide'); 
        } else {
          alert("Gagal: " + response.message);
        }

      } catch (e) {
        alert("Gagal parsing JSON: " + e.message);
      }
      closeModal();
    } else {
      alert("Terjadi kesalahan jaringan: " + xhr.status);
    }
  };
  xhr.onerror = function () {
    alert("Terjadi kesalahan saat mengirim data.");
  };
  xhr.send(formData);
});
</script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
