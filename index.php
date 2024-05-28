<?php
require_once "ectr/main.php";

if(isset($_GET['closeSession']) && $_GET['closeSession']=='true'){
    Session::close('user');
    Router::redirect('/');
}


if(!$_SESSION['user']){
    Router::get('/', 'signin.php');
    Router::get('/signup', 'signup.php');
}
else{
    Router::get('/', 'index.php');
    Router::get('/accounts', 'accounts.php');
    Router::get('/history', 'history.php');
    Router::get('/buy-sell', 'buy-sell.php');
    Router::get('/settings', 'settings.php');
}

// Activate routing 
Router::on();
?>