<?php
require_once 'header.php';
?>
<body class="hold-transition login-page">
  <link rel="stylesheet" href="plugins/sweetalert2/sweetalert2.min.css">

<div class="login-box">
  <div class="login-logo">
    <a href="index2.html"><b>PARKIRAN XYZ</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="index3.html" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="username" id="username" placeholder="Username" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button id="submit" type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script type="text/javascript">
$(document ).ready(function() {
    console.log( "ready!" );
    $('form').on('submit',function(e){
		e.preventDefault();
		$('button#submit').prop('disabled', true);
		$('button#submit').removeAttr('disabled');
		var rec = $(this).serialize();
		$.post( "cek_user.php", rec ,function( data ) {
		  if(data.status == 200) {
			  window.location.href = 'dashboard.php';
		  } else {
			  //swal.fire(data.message);
			  alert(data.message);
			  $('button#submit').removeAttr('disabled');
		  }
		},'json');
		return false;
	});
});

</script>
</body>
</html>
