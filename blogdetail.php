<?php
session_start();
require "config/config.php";
require "config/common.php";
if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
  header("Location: login.php");
}



$stmt = $pdo -> prepare("SELECT id, title, content, img, created_at FROM posts WHERE id=".$_GET['id']);
$stmt -> execute();
$result =  $stmt->fetch(PDO::FETCH_ASSOC);

$post_id = $_GET['id'];
$stmtcm = $pdo -> prepare("SELECT content, author_id, created_at FROM comments WHERE post_id=$post_id");
$stmtcm -> execute();
$cmresult =  $stmtcm->fetchAll();

if($cmresult){
  $auresult = [];
  foreach ($cmresult as $key => $value) {
    # code...
    // print_r($value);
    $author_id = $value['author_id'];
    $stmtau = $pdo -> prepare("SELECT name FROM users WHERE id=$author_id");
    $stmtau -> execute();
    $auresult[] =  $stmtau->fetch(PDO::FETCH_ASSOC);
  }
}
// var_dump($auresult);
if(($_POST) && $_POST['comment'] != ""){
  $comment = $_POST['comment'];
  $autor_id = $_SESSION['author_id'];
  $stmt = $pdo->prepare("INSERT INTO comments (content, author_id, post_id) VALUES(:content, :author_id, :post_id)");
  $com_result = $stmt -> execute(
    array(
      ":content" => $comment,
      ":author_id" => $autor_id,
      ":post_id" => $post_id
    )
  );
  if($com_result){
    header('Location: blogdetail.php?id='.$post_id);
  }
}

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

  <!-- Content Wrapper. Contains page content -->
  <div class="">

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
      <div class="row">
          <div class="col-md-12">
            <!-- Box Comment -->
            <div class="card card-widget">
              <div class="card-header">
                <div class="card-title" style="text-al;text-align: center;float: none;">
                   <h1><?=escape($result['title'])?></h1>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <img class="img-fluid pad" src="admin/image/<?=$result['img']?>" alt="Photo">

                <p><?=escape($result['content'])?></p>
              </div>
              <!-- /.card-body -->
              <div style="display: flex;justify-content: space-between;margin: 10px 0px">
              <h4>Comments</h4>
              <div class="float-right d-none d-sm-inline">
                <a href="index.php" >
                  <button type="button" class="btn btn-default">Back</button>
                </a>
              </div>
              </div>
              <div class="card-footer card-comments">
                <!-- /.card-comment -->
                <div class="card-comment" >

                  <?php
                  foreach ($cmresult as  $key => $value) {?>
                  <div class="comment-text" style="margin: 0px">
                    <span class="username">=
                      <span class="text-muted float-right"><?php echo escape($value['created_at'])?></span>
                    </span><!-- /.username -->
                    <?php echo escape($value['content'])?>
                  </div>
                  <?php
                  }
                  ?>
                  <!-- /.comment-text -->
                </div>
                <!-- /.card-comment -->
              </div>
              <!-- /.card-footer -->
              <div class="card-footer">
                <form action="" method="post">
                  <!-- .img-push is used to add margin to elements next to floating images -->
                  <div class="img-push">
                    <input type="text" name = "comment" class="form-control form-control-sm" placeholder="Press enter to post comment">
                  </div>
                </form>
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
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
