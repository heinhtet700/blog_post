<?php
require "config/config.php";
if($_POST){
  if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['terms'])){
    if(empty($_POST['name'])){
      $name_error = '* '."Name Requiered";
    }
    if(empty($_POST['email'])){
      $email_error = '* '."Email Requiered";
    }
    if(empty($_POST['password'])){
      $password_error = '* '."Password Requiered";
    }
    if(empty($_POST['terms'])){
      $term_error = '* '."You Need to access Term and Conditon.";
    }

  } else{
    if($_POST['password'] === $_POST['confirm-password']){
      $name = $_POST['name'];
      $email = $_POST['email'];
      $password = password_hash($_POST['password'], PASSWORD_DEFAULT);


      $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");
      $stmt->bindValue(":email", $email);
      $stmt->execute();
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      if($user){
          $password_error = '* '."User already exits.";
      } else{
          $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES(:name, :email, :password)");
          $result = $stmt -> execute(
          array(
              ":name" => $name,
              ":email" => $email,
              "password" => $password,
          )
          );
          if($result){
          echo"<script>alert('Success added'); window.location = 'login.php'</script>";
          }
      }

  } else{
    $password_error = '* '."Password and Retype Password must be same.";
  }
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Blog Post</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="register-logo">
    <h2>Register</h2>
  </div>

  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg">Register a new membership</p>

      <form action="register.php" method="post">
        <div class="input-group mb-3">
          <input name="name" type="text" class="form-control" placeholder="Full name" style="flex:auto;border-right: 1px solid #ced4da; border-radius:.25rem">
          <?php echo empty($name_error)?  '' :"<span style='color: red'>$name_error</span>"?>
        </div>
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email" name="email" style="flex:auto;border-right: 1px solid #ced4da; border-radius:.25rem"  autocomplete="username">
          <?php echo empty($email_error)?  '' :"<span style='color: red'>$email_error</span>"?>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" name="password" style="flex:auto;border-right: 1px solid #ced4da; border-radius:.25rem" autocomplete="new-password">
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Retype password" name="confirm-password" style="flex:auto;border-right: 1px solid #ced4da; border-radius:.25rem" autocomplete="new-password">
          <?php echo empty($password_error)?  '' :"<span style='color: red'>$password_error</span>"?>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="agreeTerms" name="terms" value="agree">
              <label for="agreeTerms">
                I agree to the <a href="license.php">terms</a>
              </label>
              <?php echo empty($term_error)?  '' :"<p style='color: red'>$term_error</p>"?>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <a href="login.php" class="text-center">I already have a membership</a>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
