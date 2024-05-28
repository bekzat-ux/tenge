<?php
if(isset($_POST['type']) && $_POST['type'] == 'add'){
  $data = array(
    'uid'=>$_SESSION['user']['id'],
    'name'=>$_POST['name'],
    'balance'=>$_POST['summa']
  );

  DBC::insert('deposit', $data);
  Router::redirect('accounts');
}
elseif(isset($_GET['dell']) && intval($_GET['dell'])>0){
  DBC::delete('deposit', 'id', $_GET['dell']);
  Router::redirect('accounts');
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
          <div class="col-xl-6 col-lg-6 col-md-12">
            <div class="card profile_card">
              <div class="card-body">
                <div class="user-profile d-flex mb-4 pb-4">
                  <div>
                    <span>Привет</span>
                    <h4 class="mb-2"><?=$user['name']?></h4>
                    
                    <?php if($user['phone']){ ?>
                    <a href="tel:<?=$user['phone']?>" class="mb-1 d-inline-block"> <span><i class="fas fa-phone-alt me-2 text-primary"></i></span> <?=$user['phone']?></a>
                    <?php } ?>

                  <br>
                    <a href="mailto:<?=$user['email']?>"> 
                      <span><i class="fa fa-envelope me-2 text-primary"></i></span> <?=$user['email']?>
                    </a>
                  </div>
                </div>

                <ul class="card-profile__info">
                  <?php if($user['addres']){ ?>
                  <li>
                    <h5 class="me-4">Адрес</h5>
                    <span class="text-muted"><?=$user['addres']?></span>
                  </li>
                  <?php } ?>
                  <li class="mb-1">
                    <h5 class="me-4">Аккаунт создан:</h5>
                    <span><?=$user['create']?></span>
                  </li>
                  <li>
                    <h5 class="text-danger me-4">Последний вход: </h5>
                    <span class="text-danger"><?=$user['last']?></span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-xl-6 col-lg-6 col-md-12">
            <div class="card acc_balance">
              <div class="card-header">
                <h4 class="card-title">Баланс</h4>
              </div>
              <div class="card-body">
                <span>Общий баланс</span>
                <?php 
                      $deposits = $GLOBALS['connect']->query("SELECT * FROM `deposit` WHERE `uid` = '".$_SESSION['user']['id']."'");
                ?>
                <h3><?php
                $summa=0;
                foreach($deposits as $deposit){$summa = $summa+$deposit['balance'];}
                echo $summa;
                ?> ₸</h3>

                <div class="d-flex justify-content-between my-4">
                  <div>
                    <p class="mb-1">Доходы за все время</p>
                    <?php
                      $history  = $GLOBALS['connect']->query("SELECT * FROM `history` WHERE `uid` = '".$_SESSION['user']['id']."'");
                      $plus     = 0; $minus   = 0;
                      foreach($history as $op){
                        if($op['type']=='minus'){
                          $minus  = $minus+intval($op['summa']);
                        } 
                        else if($op['type']=='plus'){
                          $plus   = $plus+intval($op['summa']);
                        }
                      }
                    ?>
                    <h4>+ <?=$plus?> ₸</h4>
                  </div>
                  <div>
                    <p class="mb-1">Расходы за все время</p>
                    <h4>- <?=$minus?> ₸</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-12 col-lg-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Добавить счет / депозит</h4>
              </div>
              <div class="card-body">
                <form method="post" action="accounts">
                  <input type="text" name="type" value="add" hidden readonly>
                  <div class="form-group">
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <label class="input-group-text"><i class="far fa-money-bill-alt"></i></label>
                      </div>
                      <input type="text" class="form-control" name="summa" placeholder="Первоначальный баланс">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <label class="input-group-text"><i class="fas fa-university"></i></label>
                      </div>
                      <input type="text" class="form-control" name="name" placeholder="Название счета / депозита">
                    </div>
                  </div>

                  <button class="btn btn-primary w-100">Подтвердить добавление счета</button>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xl-12">

            
            <div class="card table-card border-0 rounded-default px-4 py-3 box-shadow">
              <div class="card-header bg-transparent border-0 p-0">
                <h5 class="card-title border-0 p-0 pb-2">Список счетов / депозитов</h5>
              </div>
              <div class="card-body p-0">
                <table>
                  <tbody>
                    <?php
                      $i = 0;
                      foreach($deposits as $deposit){
                        $i++;
                        ?>
                          <tr>
                            <td scope="row" data-label="Arrow Icon"><i class="la la-bank fa-md rounded-circle text-white bg-success"></i></td>
                            <td data-label="Card"><span class="text-purple-200 fw-bold"><?=$deposit['name']?></span></td>
                            <td data-label="Amout BTC"><span class="text-success fw-bold"><?=$deposit['balance']?> ₸</span></td>
                            <td data-label="Amout USD" onclick="window.location.href='?dell=<?=$deposit['id']?>'"><span class="text-purple-200 fw-bold" style="cursor: pointer;">Удалить</span></td>
                          </tr>
                        <?php
                      }

                      if($i<1){
                        ?>
                          <tr>
                            <td data-label="Amout USD"><span class="text-purple-200 fw-bold">Пусто</span></td>
                          </tr>
                        <?php
                      }
                    ?>
                    <!-- <tr>
                      <td data-label="Arrow Icon"><i class="la la-arrow-down fa-md rounded-circle text-white bg-danger"></i></td>
                      <td data-label="Badge"><span class="badge inline-flex align-items-center bg-danger text-white">solid</span></td>
                      <td data-label="Icon">
                        <div class="d-flex align-items-center">
                          <span class="icon d-flex align-items-center justify-content-center me-1">
                            <i class="cc BTC fs-6"></i>
                          </span>
                          <span class="text-uppercase text-purple-200 fw-bold">btc</span>
                        </div>
                      </td>
                      <td data-label="Card"><span class="text-purple-200 fw-bold">Using - Bank *******5264</span></td>
                      <td data-label="Amout BTC"><span class="text-danger fw-bold">-0.000242 BTC</span></td>
                      <td data-label="Amout USD"><span class="text-purple-200 fw-bold">-0.125 USD</span></td>
                    </tr>
                    <tr>
                      <td scope="row" data-label="Arrow Icon"><i class="la la-arrow-up fa-md rounded-circle text-white bg-success"></i></td>
                      <td data-label="Badge"><span class="badge inline-flex align-items-center bg-success text-white">solid</span></td>
                      <td data-label="Icon">
                        <div class="d-flex align-items-center">
                          <span class="icon d-flex align-items-center justify-content-center me-1">
                            <i class="cc LTC fs-6"></i>
                          </span>
                          <span class="text-uppercase text-purple-200 fw-bold">LTC</span>
                        </div>
                      </td>
                      <td data-label="Card"><span class="text-purple-200 fw-bold">Using - Bank *******5264</span></td>
                      <td data-label="Amout BTC"><span class="text-success fw-bold">-0.000242 BTC</span></td>
                      <td data-label="Amout USD"><span class="text-purple-200 fw-bold">-0.125 USD</span></td>
                    </tr> -->
                  </tbody>
                </table>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
    <!--===================== Content body End =====================-->
  </section>
</div>
<!-- Jquery -->
<script src="public/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Scripts -->
<script src="public/js/scripts.js"></script>
</body>
</html>


