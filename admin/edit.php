

<?php
session_start();
require "../config/config.php";
require "../config/common.php";
define("DD", realpath("."));

if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in']) && $_SESSION['role'] != 1){
  header("Location: login.php");
}
if($_POST){
  if(empty($_POST['title']) || empty($_POST['content'])){

    if(empty($_POST['title'])){
      $title_error = '* '."Title Required.";
    }
    if(empty($_POST['content'])){
      $content_error = '* '."Content Requierd.";
    }
  }  else{
  $title = $_POST['title'];
  $content = $_POST['content'];
  $id = $_POST['id'];
  

  // var_dump($_FILES);
  if($_FILES['image']['name'] != null){
    
    $file_name = $_FILES['image']['name'];
    $temp_file = $_FILES['image']['tmp_name'];
    $file_explode = explode(".", $file_name);
    $name = reset($file_explode);
    $file_exten = end($file_explode);
    if(is_uploaded_file($temp_file)){
      $new_file = md5(time().$name) . "." . $file_exten;
      $dest_dir = DD . '/image/';

      function checkMimeType($file) {
        $allow_file_type = ['image/jpeg', 'image/png'];

        return in_array(mime_content_type($file), $allow_file_type)? true : false;
      }

      $allow_file = checkMimeType($temp_file);
      if($allow_file){
        $stmt = $pdo->prepare("UPDATE posts SET title= :title,content= :content, img=:img WHERE id = '$id'");
        $result = $stmt->execute(
          array(
            ":title" => $title,
            ":content" => $content,
            ":img" => $new_file
          )
        );
        if($result){
            if(move_uploaded_file($temp_file, $dest_dir . $new_file)){
            echo"<script>alert('Success added'); window.location = 'index.php'</script>";
          }
        }
      } else{
        $image_error = '* '."Not allowed file type.";
      }
    }
  } else{
      $stmt = $pdo->prepare("UPDATE posts SET title= :title, content=:content WHERE id = '$id'");
      $result = $stmt->execute(
        array(
          ":title" => $title,
          ":content" => $content
        )
      );
      if($result){
        echo"<script>alert('Success added'); window.location = 'index.php'</script>";
      }
    }
  }
  
}
$stmt = $pdo -> prepare("SELECT * FROM posts WHERE id=".$_GET['id']);
$stmt -> execute();
$result =  $stmt->fetch(PDO::FETCH_ASSOC);
?>



<?php include("header.php");?>

    <!-- Main content -->
    <div class="content">
        <div class="row">
          <div class="card col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Edit Post</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" method="post" action ="" enctype="multipart/form-data">
              <input type="hidden" value="<?php echo empty($_SESSION['token'])? '' :$_SESSION['token']?>" name="token" >
                <input type="hidden" value="<?= $result['id'] ?>" name="id">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Title</label>
                    <input type="text" class="form-control" name="title" id="exampleInputEmail1"  value="<?= escape($result['title']) ?>">
                     <?php echo empty($title_error)? '' : "<span style='color: red'>$title_error</span>";?>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Content</label>
                    <textarea class="form-control" rows="3" placeholder="Enter ..." name="content" ><?= escape($result['content']) ?> </textarea>
                    <?php echo empty($content_error)? '' : "<span style='color: red'>$content_error</span>";?>
                  </div>
                  <img src="image/<?= escape($result['img'])?>" alt="" width="300px" height="200px">
                  <div class="mb-3">
                    <label for="formFileSm" class="form-label">Image</label>
                    <input class="form-control" name = "image" id="formFileSm" type="file">
                    <?php echo empty($image_error)? '' : "<span style='color: red'>$image_error</span>";?>
                </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Update</button>
                  <a href="index.php" type="button" class="btn btn-default">Back</a>
                </div>
              </form>
            </div>

          </div>
        </div>
    </div>
    <!-- /.content -->
<?php include("footer.html");?>