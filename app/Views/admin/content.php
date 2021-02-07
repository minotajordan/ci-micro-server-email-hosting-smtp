<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>Admin | Daily 1</title>

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

<!-- OPTIONAL SCRIPTS -->
<script src="<?php echo base_url(); ?>/plugins/chart.js/Chart.min.js"></script>
<script src="<?php echo base_url(); ?>/dist/js/demo.js"></script>
<script src="<?php echo base_url(); ?>/dist/js/pages/dashboard3.js"></script>
</body>

</html>


<style>

.tooltip_admin {
  position: relative;
  display: inline-block;
  margin: 0 12px 12px 12px;
}

.tooltip_admin .tooltiptext {
  visibility: hidden;
  width: 120px;
  background-color: black;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px 0;

  /* Position the tooltip */
  position: absolute;
  z-index: 1;
}

.tooltip_admin:hover .tooltiptext {
  visibility: visible;
}

  .gallery-update {
    width: 100% !important;
    padding: 10px;
    border-radius: 20px;
  }

  .model-center-sm {
    top: 40%;
    position: fixed;
    width: 200px;
    left: calc(50% - 100px);
  }

  .model-center-sm .modal-title {
    font-size: 16px;
  }
</style>

<!-- Select2 -->
<link rel="stylesheet" href="<?= base_url() ?>/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?= base_url() ?>/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<script src="<?= base_url() ?>/plugins/select2/js/select2.full.min.js"></script>

<!-- summernote -->
<link rel="stylesheet" href="<?= base_url() ?>/plugins/summernote/summernote-bs4.css">
<!-- Summernote -->
<script src="<?= base_url() ?>/plugins/summernote/summernote-bs4.min.js"></script>

<!-- DataTables -->
<script src='".base_url()."/plugins/jquery/jquery.min.js'></script>
<script src='<?= base_url() ?>/plugins/datatables/jquery.dataTables.min.js'></script>
<script src='<?= base_url() ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js'></script>
<script src='<?= base_url() ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js'></script>
<script src='<?= base_url() ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js'></script>


<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to to the body tag
to get the desired effect
|---------------------------------------------------------|
|LAYOUT OPTIONS | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->
      <!-- <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="<?= base_url(); ?>" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="#" class="nav-link">Contact</a>
        </li>
      </ul>

      <form class="form-inline ml-3">
        <div class="input-group input-group-sm">
          <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-navbar" type="submit">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
      </form>

      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-comments"></i>
            <span class="badge badge-danger navbar-badge">3</span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <a href="#" class="dropdown-item">
              <div class="media">
                <img src="dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                <div class="media-body">
                  <h3 class="dropdown-item-title">
                    Brad Diesel
                    <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                  </h3>
                  <p class="text-sm">Call me whenever you can...</p>
                  <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                </div>
              </div>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <div class="media">
                <img src="dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                <div class="media-body">
                  <h3 class="dropdown-item-title">
                    John Pierce
                    <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                  </h3>
                  <p class="text-sm">I got your message bro</p>
                  <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                </div>
              </div>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <div class="media">
                <img src="dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                <div class="media-body">
                  <h3 class="dropdown-item-title">
                    Nora Silvester
                    <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                  </h3>
                  <p class="text-sm">The subject goes here</p>
                  <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                </div>
              </div>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>
            <span class="badge badge-warning navbar-badge">15</span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-item dropdown-header">15 Notifications</span>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-envelope mr-2"></i> 4 new messages
              <span class="float-right text-muted text-sm">3 mins</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-users mr-2"></i> 8 friend requests
              <span class="float-right text-muted text-sm">12 hours</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-file mr-2"></i> 3 new reports
              <span class="float-right text-muted text-sm">2 days</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button"><i class="fas fa-th-large"></i></a>
        </li>
      </ul>
    </nav>
    /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="<?= base_url(); ?>/admin" class="brand-link">
        <img src="https://cdn3.iconfinder.com/data/icons/redmoon-google/512/google_admin-512.png" alt="AdminLTE Logo" class="brand-image" style="opacity: .8">
        <span class="brand-text font-weight-light">Daily</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="https://cdn4.iconfinder.com/data/icons/small-n-flat/24/user-256.png" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="#" class="d-block">Admin</a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <li class="nav-item has-treeview menu-open">
              <a href="#" class="nav-link active">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Dashboard
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?= base_url(); ?>/admin/user" class="nav-link">
                    <i class="fas fa-user-friends nav-icon"></i>
                    <p>User</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url(); ?>/admin/product" class="nav-link">
                    <i class="fas fa-list-ul nav-icon"></i>
                    <p>Product</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url(); ?>/admin/category" class="nav-link">
                    <i class="fas fa-ellipsis-v nav-icon"></i>
                    <p>Category</p>
                  </a>
                </li>

                <li class="nav-item">
                  <a href="<?= base_url(); ?>/admin/service" class="nav-link">
                    <i class="fas fa-border-all nav-icon"></i>
                    <p>Service</p>
                  </a>
                </li>


                <li class="nav-item">
                  <a href="<?= base_url(); ?>/admin/slider" class="nav-link">
                    <i class="fas fa-images nav-icon"></i>
                    <p>Slider</p>
                  </a>
                </li>

                <li class="nav-item">
                  <a href="<?= base_url(); ?>/admin/cardHome" class="nav-link">
                    <i class="fas fa-credit-card nav-icon"></i>
                    <p>cardHome</p>
                  </a>
                </li>

              <li class="nav-item">
                  <a href="<?= base_url(); ?>/admin/configuration" class="nav-link">
                      <i class="fas fa-cog nav-icon"></i>
                      <p>Configuration</p>
                  </a>
              </li>

              <li class="nav-item">
                  <a href="<?= base_url(); ?>/admin/post" class="nav-link">
                      <i class="fas fa-pen-alt nav-icon"></i>
                      <p>Post</p>
                  </a>
              </li>

              <li class="nav-item">
                  <a href="<?= base_url(); ?>/admin/contacto" class="nav-link">
                      <i class="fab fa-facebook-messenger nav-icon"></i>
                      <p>Contacto</p>
                  </a>
              </li>

              <li class="nav-item">
                  <a href="<?= base_url(); ?>/admin/delivery" class="nav-link">
                      <i class="fa fa-toolbox nav-icon"></i>
                      <p>Envios</p>
                  </a>
              </li>

              <li class="nav-item">
                  <a href="<?= base_url(); ?>/admin/email" class="nav-link">
                      <i class="fa fa-envelope-open nav-icon"></i>
                      <p>Correos Electronicos</p>
                  </a>
              </li>


              </ul>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark"><?= $title; ?></h1>







            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="<?= base_url(); ?>/admin/login/close">Close</a></li>
              </ol>

            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-6">
              <div class="card">
                <!-- <?= isset($form_libmin) && $form_libmin; ?> -->
              </div>
              <!-- /.card -->
              <!-- /.card -->
            </div>
            <!-- /.col-md-6 -->
            <div class="col-lg-12">

                <div class="modal fade" id="modal-overlay">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="overlay d-flex justify-content-center align-items-center">
                                <i class="fas fa-2x fa-sync fa-spin"></i>
                            </div>
                            <div class="modal-header">
                                <h4 class="modal-title">Estamos gestionando tus datos</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Tardaremos unos segundos o minutos dependiendo de tu internet&hellip;</p>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->

              <div class="card">
                <?= $list_libmin; ?>
              </div>
              <!-- /.card -->

            </div>
            <!-- /.col-md-6 -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <div class="modal fade" id="modal-sm">
      <div class="modal-dialog modal-sm">
        <div class="modal-content model-center-sm">
          <div class="modal-header">
            <h4 class="modal-title">Confirm Remove</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-danger" onclick="redirectRemove()">Confirm</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer">
      <strong>Copyright &copy; 2014-2019 <a href="http://adminlte.io">AdminLTE.io</a>.</strong>
      All rights reserved.
      <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 3.0.5
      </div>
    </footer>
  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->


  <script>
    let id_remove_temp = 0;
    let table_temp = 0;

    function checkDelete(id, table) {
      id_remove_temp = id;
      table_temp = table;
    }

    function redirectRemove() {
      // $this->request->uri->getPath()
      window.location.replace(`${window.location.href}?lib_min=remove&id=${id_remove_temp}&table=${table_temp}`);
    }

    $(function() {
      $('#example1').DataTable({
        'responsive': true,
        'autoWidth': false,
      });
      $('#example2').DataTable({
        'paging': true,
        'lengthChange': false,
        'searching': false,
        'ordering': true,
        'info': true,
        'autoWidth': false,
        'responsive': true,
      });
    });

    $(function() {
      //Initialize Select2 Elements
      $('.select2').select2()

      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      })
    })
  </script>