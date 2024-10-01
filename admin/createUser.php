<?php
session_start();
require "../config/config.php";
require "../config/common.php";
if($_POST){
  if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirmPassword'])){
    if(empty($_POST['name'])){
      $name_error = '* '."Name Required.";
    }
    if(empty($_POST['email'])){
      $email_error = '* '."Email Requierd.";
    }
    if(empty($_POST['password']) || empty($_POST['confirmPassword'])){
      $pass_error = '* '."Password Requiered";
    }
  } else{
    if($_POST['password'] != $_POST['confirmPassword']){
      $pass_error = '* '."Password and confirm Password must be the same.";
    } else{
      $name = $_POST['name'];
      $email = $_POST['email'];
      // $password = $_POST['password'];
      $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
      $role = empty($_POST['role'])? 0 : $_POST['role'];

      $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");
      $stmt->execute(array(":email" => $email));
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      if($result){
        $user_exits = "User Already Exists.";
      } else{
        $stmt = $pdo->prepare("INSERT INTO users(name, email, password, role) VALUES(:name, :email, :password, :role)");
        $response = $stmt->execute(
          array(
            ":name" => $name,
            ":email" => $email,
            ":password" => $password,
            "role" => $role
          )
        );
        if($response){
          echo"<script>alert('Success!');window.location.href='indexUser.php'</script>";
        }
      }
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Create User</title>
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
  <div class="register-logo">
    <h2>Create User</h2>
  </div>

  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg">Create New User</p>

      <form action="createUser.php" method="post">
      <input type="hidden" value="<?php echo empty($_SESSION['token'])? '' :$_SESSION['token']?>" name="token" >
        <div class="input-group mb-3">
          <input name="name"  type="text" style="flex:auto;border-right: 1px solid #ced4da;border-radius:.25rem" class="form-control" placeholder="Full name">
          <?php echo empty($name_error)? '' : "<span style='color: red'>$name_error</span>";?>
        </div>
        <div class="input-group mb-3">
          <input name= "email"  type="email" class="form-control" style="flex:auto;border-right: 1px solid #ced4da; border-radius:.25rem" placeholder="Email">
          <?php echo empty($email_error)? '' :" <span style='color: red'>$email_error</span> "?>
        </div>
        <div class="input-group mb-3">
          <input name="password"  type="password" style="flex:auto;border-right: 1px solid #ced4da; border-radius:.25rem" class="form-control" placeholder="Password">
        </div>
        <div class="input-group mb-3">
          <input name="confirmPassword"  type="password" style="flex:auto;border-right: 1px solid #ced4da; border-radius:.25rem" class="form-control" placeholder="Retype password">
          <?php echo empty($pass_error)?  '' :"<span style='color: red'>$pass_error</span>"?>
          <?php echo empty($user_exits)?  '' :"<span style='color: red'>$user_exits</span>"?>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="agreeTerms" name="role" value="1">
              <label for="agreeTerms">
                Role
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Create</button>
          </div>
          <!-- /.col -->
        </div>
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
