<?php


 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
if (!hash_equals($_SESSION['token'], $_POST['token'])){
    header('Location: login.php');
    echo 'Invalid CSRF token';
    exit();
}else{
  unset($_SESSION['token']);
}
}


if (empty($_SESSION['token'])) {
    if(function_exists('random_bytes')){
    $_SESSION['token'] = bin2hex(random_bytes(32));
    } elseif(function_exists('mcrypt_create_iv')){
        $_SESSION['token'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
    } else{
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
} 
function escape($html){
  return htmlspecialchars($html, ENT_QUOTES);
}
?>