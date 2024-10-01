<?php
session_start();
require "../config/config.php";
require "../config/common.php";
// var_dump($_SESSION);
if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in']) && $_SESSION['role'] != 1){
  header("Location: login.php");
}
// var_dump($_POST['search']);
if(isset($_POST['search'])){
  setcookie("search", $_POST['search'], time()+3600);
} else{
  if(empty($_GET['pageno'])){
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
              <h3 class="card-title">Post Lists</h3>
            </div>
            <?php
            if(!empty($_GET['pageno'])){
              $pageno = $_GET['pageno'];
            } else{
              $pageno = 1;
            }
            $per_page = 4;
            $offset = ($pageno-1) * $per_page;
            if(empty($_POST['search']) && empty($_COOKIE['search'])){
              // echo"in search";
              $stmt = $pdo->prepare("SELECT COUNT(*) AS total_count FROM posts");
              $stmt -> execute();
              $rawResult = $stmt -> fetchAll();
              $countResult = $rawResult[0]['total_count'];
              $total_pages = ceil($countResult/$per_page);
              $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $per_page OFFSET $offset");
              $stmt -> execute();
              $result = $stmt -> fetchAll();
            } else{
              $searchKey = isset($_POST['search']) ? $_POST['search'] : $_COOKIE['search'];
              $stmt = $pdo->prepare("SELECT COUNT(*) AS total_count FROM posts WHERE title LIKE '%$searchKey%' ");
              $stmt -> execute();
              $rawResult = $stmt -> fetchAll();
              $countResult = $rawResult[0]['total_count'];
              // $per_page = $countResult;
              $total_pages = ceil($countResult/$per_page);
              $stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC LIMIT $per_page OFFSET $offset");
              $stmt -> execute();
              $result = $stmt -> fetchAll();
              // print_r($result);
            }
            ?>
            <!-- /.card-header -->
            <div class="card-body">
              <a href="add.php" >
                <button type="button" class="btn btn-primary" style="margin-bottom: 10px;">Create New Post</button>
              </a>
              <table class="table table-bordered">
                <thead>                  
                  <tr>
                    <th style="width: 10px">#</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th style="width: 40px">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if($result){
                    $i = 1;
                    foreach ($result as $value) { ?>
                  <tr>
                  <td><?= $i?></td>
                  <td><?= escape($value['title'])?></td>
                  <td>
                    <?=  strlen($value['content']) > 50? substr(escape($value['content']), 0, 50) . '...': escape($value['content']);?>
                  </td>
                  <td style="display: flex;" class="btn-group">
                    <div class="container">
                      <a href="edit.php?id=<?=escape($value['id'])?>" >
                        <button type="button" class="btn btn-warning">Edit</button>
                      </a>
                    </div>
                    <div class="container">
                      <a href="delete.php?id=<?=escape($value['id'])?>" onclick="return confirm('Are you sure you want to delete this item?')">
                        <button type="button" class="btn btn-danger">Delete</button>
                      </a>
                    </div>
                  </td>
                  </tr>
                  <?php
                  $i++;
                    }
                  }
                  ?>
                </tbody>
              </table>
              <nav>
                <ul class="pagination" style="justify-content: flex-end; margin-top: 10px">
                  <li class="page-item">
                    <a class="page-link" href="?pageno=1">First</a>
                  </li>
                  <li class="page-item <?php if($pageno<=1) echo"disabled"?>"><a class="page-link" href="?pageno=<?=$pageno - 1?>">Previous</a></li>
                  <li class="page-item active" aria-current="page">
                    <a class="page-link"><?=$pageno?></a>
                  </li>
                  <li class="page-item <?php if($pageno>=$total_pages) echo"disabled"?>"><a class="page-link" href="?pageno=<?=$pageno + 1?>">Next</a></li>
                  <li class="page-item">
                    <a class="page-link" href="?pageno=<?=$total_pages?>">Last</a>
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