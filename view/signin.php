<?php
if(isset($_POST['email']) && isset($_POST['password'])){
    $user = DBC::select('users', 'email', $_POST['email']);
    if($user['password'] == md5($_POST['password'])){
        $ss = new Session;
        $ss->name = 'user';
        $ss->new($user['id']);
        DBC::update('users', array('last'=>date('Y-m-d H:i')), $user['id']);
        Router::redirect('/');
    }
    Router::redirect('/');
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tenge</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="public/images/favicon.png">
    <!-- Owlcarousel -->
    <link rel="stylesheet" href="public/plugins/owlcarousel/css/owl.carousel.min.css">
    <!-- Venobox -->
    <link rel="stylesheet" href="public/plugins/venobox/venobox.min.css">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="public/css/style.css">
</head>

<body>
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>




    <div class="main-wrapper">
        <!--===================== Authentication Start =====================-->
        <div class="authentication">
            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-12 col-lg-8 col-xl-5">
                            <div class="mini-logo text-center mb-5">
                                <a><img src="public/images/logo.png" alt=""></a>
                            </div>
                        <div class="auth-form card">
                            <div class="card-header justify-content-center">
                                <h4 class="card-title">Авторизация</h4>
                            </div>
                            <div class="card-body">
                                <form method="post" class="signin_validate" action="/">
                                    <div class="form-group">
                                        <label>Электронная почта</label>
                                        <input type="email" class="form-control" placeholder="Введите почту"
                                            name="email">
                                    </div>
                                    <div class="form-group">
                                        <label>Пароль</label>
                                        <input type="password" class="form-control" placeholder="Введите пароль"
                                            name="password">
                                    </div>
                                    <div class="form-row d-flex justify-content-between mt-4 mb-2">
                                        <div class="form-group mb-0">
                                            <label class="toggle">
                                                <input class="toggle-checkbox" type="checkbox">
                                                <div class="toggle-switch"></div>
                                                <span class="toggle-label">Запомнить меня</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button class="btn btn-success w-100">Войти</button>
                                    </div>
                                </form>
                                <div class="new-account mt-3">
                                    <p>Нету аккаунта? <a class="text-primary" href="signup">Создать аккаунт</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--===================== Authentication End =====================-->
    </div>
    <script src="public/plugins/jquery/jquery.min.js"></script>
    <script src="public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="public/plugins/validator/jquery.validate.js"></script>
    <script src="public/plugins/validator/validator-init.js"></script>
    <script src="public/js/scripts.js"></script>
    
</body>
</html>