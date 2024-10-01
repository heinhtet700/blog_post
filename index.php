
<?php
session_start();
require "config/config.php";
require "config/common.php";
if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
  header("Location: login.php");
}
$stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id");
$stmt -> execute();
$result = $stmt -> fetchAll();
// var_dump($result);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 3 | Widgets</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <?php
  if($_SESSION['role']==1){
    echo"<div class='float-right d-none d-sm-inline'>
        <a href='admin/index.php' target='_blank'>
          <button type='button' class='btn btn-success'>Go To Admin</button>
        </a>
      </div>";
  }
  ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="">
  <section class="content-header">
      <div class="container-fluid" style="text-align: center;">
        <h1>BLOG POSTS</h1>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
      <div class="row">
        <?php
        foreach ($result as $value) {
        ?>
        <div class="col-md-4">
            <!-- Box Comment -->
            <div class="card card-widget">
              <div class="card-header">
                <div class="card-title" style="textalig">
                  <h6><strong><?= escape($value['title'])?></strong></h6>
                </div>
                <!-- /.user-block -->
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-toggle="tooltip" title="Mark as read">
                    <i class="far fa-circle"></i></button>
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
                  </button>
                </div>
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              <a href="blogdetail.php?id=<?=$value['id']?>">
                  <img class="img-fluid pad" src="admin/image/<?=$value['img']?>" alt="Photo" style="height: 200px;">
              </a>
                <p style="margin-top: 10px"><?=strlen($value['content']) > 30? substr(escape($value['content']), 0, 50) . '...' : escape($value['content'])?></p>
              </div>
            </div>
            <!-- /.card -->
          </div>
        <?php
        }
        ?>
          
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer" style="margin-left: 0px">
  <div class="float-right d-none d-sm-inline">
    <a href="logout.php" >
      <button type="button" class="btn btn-default">LogOut</button>
    </a>
  </div>
    <strong>Copyright &copy; 2014-2019 <a href="http://adminlte.io">AdminLTE.io</a>.</strong> All rights
    reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
