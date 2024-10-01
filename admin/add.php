

<?php
session_start();
require "../config/config.php";
require "../config/common.php";
define("DD", realpath("."));

if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
  header("Location: login.php");
}
if($_SESSION['role'] != 1) {
  header("Location: ../login.php");
}
if($_POST || $_FILES){
  if(empty($_POST['title']) || empty($_POST['content']) || empty($_FILES['image'])){

    if(empty($_POST['title'])){
      $title_error = '* '."Title Required.";
    }
    if(empty($_POST['content'])){
      $content_error = '* '."Content Requierd.";
    }
    if(empty($_FILES['image']['name'])){
      $image_error = '* '."Image  Requierd.";
    }
  } else{
    $title = $_POST['title'];
    $content = $_POST['content'];
    $file_name = $_FILES['image']['name'];
    $temp_file = $_FILES['image']['tmp_name'];
    $file_explode = explode(".", $file_name);
    $file_exten = end($file_explode);
    $name = reset($file_explode);
    $file_dir = DD . "/image/";
    $file_name? $new_file = md5(time().$name) . "." . $file_exten : $new_file = "";
    

    if(is_uploaded_file($temp_file)){
      $dest_dir = $file_dir . $new_file;
      function checkMimeType($file) {
        $allow_file_type = [
          'image/jpeg',
          'image/png',
        ];

        return in_array(mime_content_type($file), $allow_file_type)? true : false;
      }
      $allow_file = checkMimeType($temp_file);
      // echo"$allow_file"."show allow file";
      if($allow_file) {
        if(move_uploaded_file($temp_file, $dest_dir)){
          $stmt = $pdo->prepare("INSERT INTO posts (title, content, img, author_id ) VALUES(:title, :content, :img, :author_id)");
          $result = $stmt -> execute(
            array(
              ":title" => $title,
              ":content" => $content,
              "img" => $new_file,
              ":author_id" => $_SESSION['author_id']
            )
          );
          if($result){
            echo"<script>alert('Success added'); window.location = 'index.php'</script>";
          }
        }
      } else{
        $image_error = '* '."Not allowed file type.";
      }
    }
  }
}
?>



<?php include("header.php");?>

    <!-- Main content -->
    <div class="content">
        <div class="row">
          <div class="card col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Add New Post</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" method="post" action ="add.php" enctype="multipart/form-data">
              <input type="hidden" value="<?php echo empty($_SESSION['token'])? '' :$_SESSION['token']?>" name="token" >
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Title</label>
                    <input type="text" class="form-control" name="title" id="exampleInputEmail1" >
                    <?php echo empty($title_error)? '' : "<span style='color: red'>$title_error</span>";?>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Content</label>
                    <textarea class="form-control" rows="3" placeholder="Enter ..." name="content"></textarea>
                    <?php echo empty($content_error)? '' : "<span style='color: red'>$content_error</span>";?>
                  </div>
                  <div class="mb-3">
                    <label for="formFileSm" class="form-label">Image</label>
                    <input class="form-control" name = "image" id="formFileSm" type="file">
                    <?php echo empty($image_error)? '' : "<span style='color: red'>$image_error</span>";?>
                </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <a href="index.php" type="button" class="btn btn-default">Back</a>
                </div>
              </form>
            </div>

          </div>
        </div>
    </div>
    <!-- /.content -->
<?php include("footer.html");?>