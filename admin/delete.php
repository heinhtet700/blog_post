<?php
require "../config/config.php";

if($_GET){
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id=".$_GET['id']);
    $result = $stmt ->execute();
    if($result){
        echo "<script>alert('delete successfully');window.location.href='index.php'</script>";
    }
}