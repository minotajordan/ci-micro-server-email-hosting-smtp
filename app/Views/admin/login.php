<!DOCTYPE html>
<html>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>Admin</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>/plugins/fontawesome-free/css/all.min.css">
  <!-- IonIcons -->
  <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>/dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>


<!-- jQuery -->
<script src="<?php echo base_url(); ?>/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="<?php echo base_url(); ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- AdminLTE -->
<script src="<?php echo base_url(); ?>/dist/js/adminlte.js"></script>

</body>

</html>

<body class="hold-transition lockscreen">
<!-- Automatic element centering -->
<div class="lockscreen-wrapper">
  <div class="lockscreen-logo">
    <a href="<?= base_url(); ?>/admin"><b>Modulo </b>Admin</a>
  </div>
  <!-- User name -->
  <div class="lockscreen-name">Tecniagro del Sur</div>

  <!-- START LOCK SCREEN ITEM -->
  <div class="">
    <!-- /.lockscreen-image -->
    <hr>
    <!-- lockscreen credentials (contains the form) -->
    <form class="" method="post">
      <div class="input-group">
        <input type="text" name="user" class="form-control" placeholder="Usuario">
      </div>
      <div class="input-group">
        <input type="password" name="password" class="form-control" placeholder="Contraseña">
      </div>
        <hr>
        <input type="submit" class="btn btn-primary" value="Ingresar">
    </form>
    <!-- /.lockscreen credentials -->
      <hr>

  </div>
  <!-- /.lockscreen-item -->
  <div class="help-block text-center">
    No se permite el cambio de contraseña administrativa
  </div>
  <div class="lockscreen-footer text-center">
    Copyright &copy; 2020 <b><a href="http://tyfy.live" class="text-black">Dayli Dev</a></b><br>
    All rights reserved
  </div>
</div>
<!-- /.center -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
