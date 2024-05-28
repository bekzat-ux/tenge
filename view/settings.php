<?php
if(isset($_GET['type']) && $_GET['type']=='data'){
  $data = array(
    'name' => $_POST['name'],
    'email' => $_POST['email'],
    'addres' => $_POST['addres'],
    'city' => $_POST['city'],
    'phone' => $_POST['phone'],
    'country' => $_POST['country']
  );

  DBC::update('users', $data, $_SESSION['user']['id']);
  Router::redirect('/settings');
}
elseif((isset($_POST['password']) && isset($_POST['password2']) && isset($_GET['type'])) && $_GET['type'] == 'pass'){
  if($_POST['password'] == $_POST['password2']){
    DBC::update('users', array('password'=>md5($_POST['password'])), $_SESSION['user']['id']);
  }
  Router::redirect('/settings');
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
<?php
require_once "view/block/nav.php";
?>
    <!--===================== Content body Start =====================-->
    <div class="content-body">
      <!--===================== Page title Start =====================-->

<!--===================== Page title End =====================-->
      <div class="container">
        <div class="row">
        
          <div class="col-12 col-xl-12">
            <div class="row">
              <div class="col-xl-12">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">Персональные данные</h4>
                  </div>
                  <div class="card-body">
                    <form method="post" action="/settings?type=data" class="personal_validate">
                      <div class="form-row d-flex flex-wrap">
                        <div class="form-group col-12 col-xl-6">
                          <label class="mr-sm-2">ФИО</label>
                          <input type="text" class="form-control" placeholder="Введите фио" value="<?=$user['name']?>" name="name">
                        </div>
                        <div class="form-group col-12 col-xl-6">
                          <label class="mr-sm-2">Email</label>
                          <input type="email" class="form-control" placeholder="Введите почту" value="<?=$user['email']?>" name="email">
                        </div>
                        <div class="form-group col-12 col-xl-6">
                          <label class="mr-sm-2">Фактический адрес</label>
                          <input type="text" class="form-control" placeholder="Введите адрес" value="<?=$user['addres']?>" name="addres">
                        </div>
                        <div class="form-group col-12 col-xl-6">
                          <label class="mr-sm-2">Город</label>
                          <input type="text" class="form-control" placeholder="Введите ваш город" value="<?=$user['city']?>" name="city">
                        </div>
                        <div class="form-group col-12 col-xl-6">
                          <label class="mr-sm-2">Телефон</label>
                          <input type="text" class="form-control" placeholder="Введите номер" name="phone" value="<?=$user['phone']?>">
                        </div>
                        <div class="form-group col-12 col-xl-6">
                          <label class="mr-sm-2">Страна</label>
                          <select class="form-select" name="country">
                            <option value="kz" <?=($user['country']=='kz'?'selected':'')?>>Казахстан</option>
                            <option value="ch" <?=($user['country']=='ch'?'selected':'')?>>Китай</option>
                            <option value="uk" <?=($user['country']=='uk'?'selected':'')?>>Украина</option>
                            <option value="ru" <?=($user['country']=='ru'?'selected':'')?>>Россия</option>
                            <option value="uz" <?=($user['country']=='uz'?'selected':'')?>>Узбекситан</option>
                            <option value="kg" <?=($user['country']=='kg'?'selected':'')?>>Киргизстан</option>
                              </ul> 
                          </select>
                        </div>

                        <div class="form-group col-12">
                          <button class="btn btn-success pl-5 pr-5">Обновить</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              

              <div class="col-xl-12">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">Доступ к аккаунту</h4>
                  </div>
                  <div class="card-body">
                    <form method="post" action="/settings?type=pass" class="personal_validate">
                      <div class="form-row d-flex flex-wrap">
                       <div class="form-group col-12 col-xl-6">
                          <label class="mr-sm-2">Новый пароль</label>
                          <input type="password" name="password" class="form-control" placeholder="**********">
                        </div>
                        <div class="form-group col-12 col-xl-6">
                          <label class="mr-sm-2">Подтвердить пароль</label>
                          <input type="password" name="password2" class="form-control" placeholder="**********">
                        </div>
                        <div class="form-group col-12">
                          <button class="btn btn-success pl-5 pr-5">Обновить пароль</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--===================== Content body End =====================-->
  </section>
</div>
<!-- jquery -->
<script src="public/plugins/jquery/jquery.min.js"></script>
<!-- bootstrap -->
<script src="public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- jquery-ui -->
<script src="public/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="public/js/plugins/jquery-ui-init.js"></script>
<!-- jquery.validate -->
<script src="public/plugins/validator/jquery.validate.js"></script>
<script src="public/plugins/validator/validator-init.js"></script>
<!-- scripts -->
<script src="public/js/scripts.js"></script>
</body>
</html>

