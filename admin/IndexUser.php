<?php
session_start();
require "../config/config.php";
require "../config/common.php";
if(empty($_SESSION['longged_in'])  && empty($_SESSION['user_id'])){
  header("Location: login.php");
}
if($_SESSION['role'] != 1){
  header("Location: ../login.php");
}
if(isset($_POST['search'])) {
    setcookie("search", $_POST['search'], time()+3600);
  } else{
    if(empty($_GET['page'])){
      unset($_COOKIE['search']);
      setcookie('search', null, -1, '/');
      setcookie("search", "", time() - 3600);
    }
  }
?>



<?php include("header.php");?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="card col-md-12">
            <div class="card-header">
              <h3 class="card-title">User Lists</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <a href="createUser.php" >
                <button type="button" class="btn btn-primary" style="margin-bottom: 10px;">Create New User</button>
              </a>
              <table class="table table-bordered">
                <thead>                  
                  <tr>
                    <th style="width: 10px">#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th style="width: 40px">Action</th>
                  </tr>
                </thead>
                <?php
                if(isset($_GET['page'])){
                  $page = $_GET['page'];
                } else{
                  $page = 1;
                }
                $per_page = 4;
                $offset = ($page - 1) * $per_page;
                if(isset($_POST['search']) || isset($_COOKIE['search'])){
                  $searchKey = isset($_POST['search'])? $_POST['search'] : $_COOKIE['search'];
                  // echo"$searchKey";
                  $stmt = $pdo->prepare("SELECT COUNT(*) as user_count FROM users WHERE name LIKE '%$searchKey%'" );
                  $stmt->execute();
                  $user_count = $stmt->fetch(PDO::FETCH_ASSOC);
                  print_r($user_count['user_count']);
                  $total_page = ceil($user_count['user_count']/$per_page);
                  
                  $stmt = $pdo->prepare("SELECT id, name, email, role FROM users WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset, $per_page");
                  $stmt->execute();
                  $result = $stmt->fetchAll();
                } else{
                  $stmt = $pdo->prepare("SELECT COUNT(*) as user_count FROM users");
                  $stmt->execute();
                  $user_count = $stmt->fetch(PDO::FETCH_ASSOC);
                  // print_r($user_count['user_count']);
                  $total_page = ceil($user_count['user_count']/$per_page);
                  
                  $stmt = $pdo->prepare("SELECT id, name, email, role FROM users ORDER BY id DESC LIMIT $offset, $per_page");
                  $stmt->execute();
                  $result = $stmt->fetchAll();
                }
                
                ?>
                <tbody>
                  <?php 
                  if($result){
                  $i=1;
                  foreach ($result as $value) {
                  ?>
                  <tr>
                  <td><?= $i?></td>
                  <td><?=escape($value['name'])?></td>
                  <td>
                  <?=escape($value['email'])?>
                  </td>
                  <td>
                  <?=escape($value['role']) == 1? "Admin" : "Normal User"?>
                  </td>
                  <td style="display: flex;" class="btn-group">
                    <div class="container">
                      <a href="editUser.php?id=<?=$value['id'] ?>">
                        <button type="button" class="btn btn-warning">Edit</button>
                      </a>
                    </div>
                    <div class="container">
                      <a href="deleteUser.php?id=<?=$value['id']?>" onclick="return confirm('Are you sure you want to delete this item?')">
                        <button type="button" class="btn btn-danger">Delete</button>
                      </a>
                    </div>
                  </td>
                  </tr>
                   <?php 
                   $i++;
                  }
                  } ?>
                </tbody>
              </table>
              <nav>
                <ul class="pagination" style="justify-content: flex-end; margin-top: 10px">
                  <li class="page-item">
                    <a class="page-link" href="?page=1">First</a>
                  </li>
                  <li class="page-item <?= $page <= 1? 'disabled' : ''?>"><a class="page-link" href="?page=<?=$page - 1?>">Previous</a></li>
                  <li class="page-item active" aria-current="page">
                    <a class="page-link"><?=$page?></a>
                  </li>
                  <li class="page-item <?= $page >= $total_page? 'disabled' : ''?>"><a class="page-link" href="?page=<?= $page + 1?>">Next</a></li>
                  <li class="page-item">
                    <a class="page-link" href="?page=<?= $total_page?>">Last</a>
                  </li>
                </ul>
              </nav>
            </div>
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
<?php include("footer.html");?>