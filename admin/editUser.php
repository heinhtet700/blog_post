<?php
session_start();
require("../config/config.php");
require "../config/common.php";

if($_POST){
  if(empty($_POST['name']) || empty($_POST['email'])){
    if(empty($_POST['name'])){
      $name_error = "* "."Name Required.";
    }
    if(empty($_POST['email'])){
      $email_error = "* "."Email Required.";
    }
  } else{
    if($_POST['password'] != $_POST['confirmPassword']){
      $password_error = "* "."Password and ReType Password must be the same.";
    } else{
      $name = $_POST['name'];
      $email = $_POST['email'];
      $id = $_POST['id'];
      $role = empty(($_POST)['role'])? 0 : 1;

      if(empty(($_POST)['password'])){
        $stmt = $pdo->prepare("UPDATE users SET name=:name, email=:email, role=:role WHERE id=$id");
        $result = $stmt->execute(
          array(
            ":name" => $name,
            ":email" => $email,
            ":role" => $role
          )
        );
        if($result){
          echo"<script>alert('Updated Success'); window.location = 'indexUser.php'</script>";
        }
      } else {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET name=:name, email=:email, password=:password, role=:role WHERE id=$id");
        $result = $stmt->execute(
          array(
            ":name" => $name,
            ":email" => $email,
            ":password" => $password,
            ":role" => $role
          )
        );
        if($result){
          echo"<script>alert('Updated Success'); window.location = 'indexUser.php'</script>";
        }
      }
    }
  }
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id=".$_GET['id']);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Edit User Page</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition register-page">
<div class="register-box">

  <div class="card">
    <div class="card-body register-card-body">
      <h4 class="login-box-msg">Update User</h4>

      <form action="" method="post">
      
        <?php
        if($result){
          ?>
          <input type="hidden" name="id" value="<?=$result['id']?>">
          <input type="hidden" value="<?php echo empty($_SESSION['token'])? '' :$_SESSION['token']?>" name="token" >
        <div class="input-group mb-3">
        <input type="text" class="form-control" style="flex:auto;border-right: 1px solid #ced4da; border-radius:.25rem" placeholder="Full name" name="name" value="<?=escape($result['name'])?>">
        <?php echo empty($name_error)?  '' :"<span style='color: red'>$name_error</span>"?>
        </div>
        <div class="input-group mb-3">
          <input name= "email" type="email" class="form-control" style="flex:auto;border-right: 1px solid #ced4da; border-radius:.25rem" placeholder="Email" value="<?=escape($result['email'])?>">
          <?php echo empty($email_error)?  '' :"<span style='color: red'>$email_error</span>"?>
        </div>
        <div class="input-group mb-3">
          <span style="font-size: 12px">Password havs already exits.</span>
          <input name="password" type="password" class="form-control" style="flex:auto;border-right: 1px solid #ced4da; border-radius:.25rem" placeholder="Password">
        </div>
        <div class="input-group mb-3">
          <input name="confirmPassword" type="password" class="form-control" style="flex:auto;border-right: 1px solid #ced4da; border-radius:.25rem" placeholder="Retype password">
          <?php echo empty($password_error)?  '' :"<span style='color: red'>$password_error</span>"?>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input name="role" type="checkbox" id="agreeTerms" name="terms" value="agree" <?=escape($result['role']) == 1? 'checked' : ''?>>
              <label for="agreeTerms">
                Role
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Update</button>
          </div>
          <!-- /.col -->
        </div>
         <?php 
        }
        ?>
      </form>
      <div class="col-4" style="padding-left: 0px">
            <a href="indexUser.php" class="text-center"><button type="submit" class="btn btn-primary btn-block">Back</button></a>
        </div>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
</body>
</html>
