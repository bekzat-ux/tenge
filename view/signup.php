<?php
if(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['name'])){
    $user = DBC::select('users', 'email', $_POST['email']);
    if(!$user['id']){
        $data = array(
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password' => md5($_POST['password'])
        );
        DBC::insert('users', $data);

        $check = DBC::select('users', 'email', $_POST['email']);
        $ss = new Session;
        $ss->name = 'user';
        $ss->new($check['id']);
        Router::redirect('/accounts');
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
                                <h4 class="card-title">Регистрация</h4>
                            </div>
                            <div class="card-body">
                                <form method="post" action="/signup">
                                    <div class="form-group">
                                        <label>ФИО</label>
                                        <input type="text" class="form-control" placeholder="Введите полное ФИО" name="name">
                                    </div>
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
                                    <div class="text-center mt-4">
                                        <button class="btn btn-success w-100">Создать</button>
                                    </div>
                                </form>
                                <div class="new-account mt-3">
                                    <p>У вас уже есть аккаунт? <a class="text-primary" href="/">Авторизация</a>
                                    </p>
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