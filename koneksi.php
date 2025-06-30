<?php
$http = 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 's' : '') . '://';
$base_url = $http . $_SERVER['HTTP_HOST'];
$base_url .=  preg_replace('@/+$@','',dirname($_SERVER['SCRIPT_NAME'])).'/';
define('BASE_URL', $base_url);

// Konfigurasi DB
$host = 'localhost';
$dbname = 'parkiran_xyz';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
    exit;
}

function formatTanggal($tanggal) {
    $datetime = DateTime::createFromFormat('Y-m-d', $tanggal);
    
    if ($datetime) {
        return $datetime->format('d-m-Y');
    } else {
        return "Format tanggal tidak valid";
    }
}

function formatTanggalTime($tanggal) {
    $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $tanggal);
    
    if ($datetime) {
        return $datetime->format('d-m-Y H:i:s');
    } else {
        return "Format tanggal tidak valid";
    }
}

function menu_navbar($menu) {
	$html_menu = '  <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
    <div class="container">
      <a href="dashboard.php" class="navbar-brand">
        <img src="parking_5457732.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Parkir XYZ</span>
      </a>

      <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a href="'.(($menu == 'dashboard') ? 'javascript:void(0);':'dashboard.php').'" class="nav-link">Dashboard</a>
          </li>
          <li class="nav-item">
            <a href="'.(($menu == 'kendaraan') ? 'javascript:void(0);':'data_kendaraan.php').'" class="nav-link">Data Kendaraan</a>
          </li>
          <li class="nav-item">
            <a href="logout.php" class="nav-link text-danger">Logout</a>
          </li>
        </ul>
		<ul class="navbar-nav ml-auto">
		  <li class="nav-item">
		    <span id="jam" class="nav-link font-weight-bold text-info"></span>
		  </li>
		</ul>
      </div>

    </div>
  </nav>';
  echo $html_menu;
}
?>
