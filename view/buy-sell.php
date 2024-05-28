<?php
if(isset($_GET['clear']) && $_GET['clear']=='true'){
  DBC::delete('deposit', 'uid', $_SESSION['user']['id']);
  DBC::delete('history', 'uid', $_SESSION['user']['id']);

  Router::redirect('/buy-sell');
}
elseif($_GET['type']=='export'){
  if($_POST['format'] == '.json'){
    switch($_POST['data']){
      case 'all':
        $history = $GLOBALS['connect']->query("SELECT * FROM `history` WHERE `uid` = '".$_SESSION['user']['id']."'");
        $result = array();

        while ($row = $history->fetch_assoc()) {
            // Добавляем каждую строку в массив
            $result[] = $row;
        }
        $hs = $result;

        $history = $GLOBALS['connect']->query("SELECT * FROM `deposit` WHERE `uid` = '".$_SESSION['user']['id']."'");
        $result = array();

        while ($row = $history->fetch_assoc()) {
            // Добавляем каждую строку в массив
            $result[] = $row;
        }
        $dp = $result;
        $file_content = json_encode(array('deposit'=>$dp, 'history'=>$hs), JSON_UNESCAPED_UNICODE);
        break;
      case 'history':
        $history = $GLOBALS['connect']->query("SELECT * FROM `history` WHERE `uid` = '".$_SESSION['user']['id']."'");
        $result = array();

        while ($row = $history->fetch_assoc()) {
            // Добавляем каждую строку в массив
            $result[] = $row;
        }

        $file_content = json_encode($result, JSON_UNESCAPED_UNICODE);
        break;
      case 'deposit':
        $history = $GLOBALS['connect']->query("SELECT * FROM `deposit` WHERE `uid` = '".$_SESSION['user']['id']."'");
        $result = array();

        while ($row = $history->fetch_assoc()) {
            // Добавляем каждую строку в массив
            $result[] = $row;
        }

        $file_content = json_encode($result, JSON_UNESCAPED_UNICODE);
        break;
    }
    // Создание файла на лету
    $file_name = "Tenge_dump-".date('Ymd-Hi').".json";
    if($_POST['name']!='') $file_name = $_POST['name'].'.json';
    // Отправка файла в заголовке
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $file_name . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . strlen($file_content));
    echo $file_content;
    exit;
  }
  elseif($_POST['format'] == '.html'){
    switch($_POST['data']){
      case 'all':
        $result = $GLOBALS['connect']->query("SELECT * FROM `deposit` WHERE `uid` = '".$_SESSION['user']['id']."'");
        $result2 = $GLOBALS['connect']->query("SELECT * FROM `history` WHERE `uid` = '".$_SESSION['user']['id']."'");
        
        $html_content = '<html><head><title>Депозит и Истории</title></head><body>';
        // Добавляем заголовок
        $html_content .= '<h1>Счета / Депозиты:</h1>';
        // Добавляем данные из базы данных в HTML
        $html_content .= '<ul>';
        while ($row = $result->fetch_assoc()) {
            $html_content .= '<li>ID: ' . htmlspecialchars($row['id']) . ' | Название: '. htmlspecialchars($row['name']) .' | Баланс: ' . htmlspecialchars($row['balance']) . '</li>';
        }
        $html_content .= '</ul>';

        $html_content .= '<h1>История транзакции:</h1>';
        // Добавляем данные из базы данных в HTML
        $html_content .= '<ul>';
        while ($row = $result2->fetch_assoc()) {
            $html_content .= '<li>' . htmlspecialchars($row['id']) . ' | ID-депозит: ' . htmlspecialchars($row['did']) . ' | Время: '. htmlspecialchars($row['time']) .' | Сумма: ' . ($row['type']=='plus'?'+':'-') . ' ' . htmlspecialchars($row['summa']) . ' | Комментарий: '. htmlspecialchars($row['comment']) .'</li>';
        }
        $html_content .= '</ul>';
        // Завершаем HTML
        $html_content .= '</body></html>';

        break;
      case 'history':
        $result = $GLOBALS['connect']->query("SELECT * FROM `history` WHERE `uid` = '".$_SESSION['user']['id']."'");
        
        $html_content = '<html><head><title>История</title></head><body>';
        // Добавляем заголовок
        $html_content .= '<h1>История транзакции:</h1>';
        // Добавляем данные из базы данных в HTML
        $html_content .= '<ul>';
        while ($row = $result->fetch_assoc()) {
            $html_content .= '<li>' . htmlspecialchars($row['id']) . ' | Время: '. htmlspecialchars($row['time']) .' | Сумма: ' . ($row['type']=='plus'?'+':'-') . ' ' . htmlspecialchars($row['summa']) . ' | Комментарий: '. htmlspecialchars($row['comment']) .'</li>';
        }
        $html_content .= '</ul>';
        // Завершаем HTML
        $html_content .= '</body></html>';

        break;
      case 'deposit':
        $result = $GLOBALS['connect']->query("SELECT * FROM `deposit` WHERE `uid` = '".$_SESSION['user']['id']."'");
        
        $html_content = '<html><head><title>Счтеа / Депозит</title></head><body>';
        // Добавляем заголовок
        $html_content .= '<h1>Счета / Депозиты:</h1>';
        // Добавляем данные из базы данных в HTML
        $html_content .= '<ul>';
        while ($row = $result->fetch_assoc()) {
            $html_content .= '<li>' . htmlspecialchars($row['id']) . ' | Название: ' . htmlspecialchars($row['name']) . ' | Баланс: ' . htmlspecialchars($row['balance']) . '</li>';
        }
        $html_content .= '</ul>';
        // Завершаем HTML
        $html_content .= '</body></html>';

        break;
    }
    
    // Создание файла на лету
    $file_name = "Tenge_table-".date('Ymd-Hi').".html";
    if($_POST['name']!='') $file_name = $_POST['name'].'.html';

    // Отправляем файл в заголовке для загрузки
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $file_name . '"');

    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . strlen($html_content));
    echo $html_content;
    exit;
  }
}
elseif($_GET['type']=='import'){
  if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
      $file_name = $_FILES['file']['name'];
      $file_tmp = $_FILES['file']['tmp_name'];
      $file_content = file_get_contents($file_tmp);
      $decoded_data = json_decode($file_content, true);

      // Проверяем, удалось ли декодировать JSON
      if ($decoded_data === null) {
          // Если декодирование не удалось, выводим сообщение об ошибке
          echo "Ошибка при декодировании JSON из файла.";
      } else {
          switch($_POST['data']){
            case 'all':
              if($decoded_data['deposit'] && $decoded_data['history']){
                DBC::delete('deposit', 'uid', $_SESSION['user']['id']);
                DBC::delete('history', 'uid', $_SESSION['user']['id']);

                foreach($decoded_data['history'] as $row){
                  DBC::insert('history', array('uid'=>$_SESSION['user']['id'], 'did'=>$row['did'], 'type'=>$row['type'], 'summa'=>$row['summa'], 'comment'=>$row['comment'], 'time'=>$row['time']));
                }
                foreach($decoded_data['deposit'] as $row){
                  DBC::insert('deposit', array('uid'=>$_SESSION['user']['id'], 'name'=>$row['name'], 'balance'=>$row['balance']));
                }
              }
              break;
            case 'history':
              DBC::delete('history', 'uid', $_SESSION['user']['id']);
              if(isset($decoded_data[0]['did']) && isset($decoded_data[0]['summa'])){
                foreach($decoded_data as $row){
                  DBC::insert('history', array('uid'=>$_SESSION['user']['id'], 'did'=>$row['did'], 'type'=>$row['type'], 'summa'=>$row['summa'], 'comment'=>$row['comment'], 'time'=>$row['time']));
                }
              }
              break;
            case 'deposit':
              DBC::delete('deposit', 'uid', $_SESSION['user']['id']);
              if(isset($decoded_data[0]['uid']) && isset($decoded_data[0]['balance'])){
                foreach($decoded_data as $row){
                  DBC::insert('deposit', array('uid'=>$_SESSION['user']['id'], 'name'=>$row['name'], 'balance'=>$row['balance']));
                }
              }
              break;
          }

          Router::redirect('/buy-sell');
      }
      Router::redirect('/buy-sell');
  }
  Router::redirect('/buy-sell');
  exit;
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
require_once "view/block/nav.php  ";
?>
    <!--===================== Content body Start =====================-->
    <div class="content-body">
      <!--===================== Page title Start =====================-->

<!--===================== Page title End =====================-->
      <div class="container">
        <div class="row">
          <div class="col-xl-5 col-lg-12 col-md-12">
            <div class="card">
              <div class="card-body">
                <div class="buy-sell-widget">
                  <ul class="nav nav-pills nav-tabs justify-content-center flex-lg-nowrap" id="pills-tab" role="tablist">
                    <li class="nav-item text-center" role="presentation">
                      <a class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" href="#buy" aria-selected="true">Экспорт</a>
                    </li>
                    <li class="nav-item text-center" role="presentation">
                      <a class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" href="#sell"aria-selected="false">Импорт</a>
                    </li>
                  </ul>
                  <div class="tab-content tab-content-default">
                    <div class="tab-pane fade show active" id="buy" role="tabpanel">
                      <form method="post" action="/buy-sell?type=export" class="currency_validate">
                        <div class="form-group">
                          <label class="me-sm-2">Данные</label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <label class="input-group-text"><i class="fa fa-th-large"></i></label>
                            </div>
                            <select name='data' class="form-select">
                              <option value="all">Истории / Депозиты</option>
                              <option value="history">История</option>
                              <option value="deposit">Депозиты</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="me-sm-2">Формат</label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <label class="input-group-text"><i class="fa fa-file"></i></label>
                            </div>
                            <select class="form-select" name="format">
                              <option value=".json">.json (JS массив)</option>
                              <option value=".html">.html (Web страница)</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="me-sm-2">Название</label>
                          <div class="input-group">
                            <input type="text" name="name" class="form-control" placeholder="Не объязательно*">
                          </div>
                        </div>
                        <button class="btn btn-success w-100">Скачать</button>
                      </form>
                    </div>
                    <div class="tab-pane fade" id="sell">
                      <form method="post" enctype="multipart/form-data" action="/buy-sell?type=import" class="currency2_validate">
                        <div class="form-group">
                          <label class="me-sm-2">Данные</label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <label class="input-group-text"><i class="fa fa-th-large"></i></label>
                            </div>
                            <select name='data' class="form-select">
                              <option value="all">Истории / Депозиты</option>
                              <option value="history">История</option>
                              <option value="deposit">Депозиты</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="me-sm-2">Импортируемый файл</label>
                          <div class="file-upload-wrapper" data-text=".json (JS массив)">
                            <input name="file" type="file" class="file-upload-field" accept=".json" value="">
                          </div>
                          <style>
                            .file-upload-wrapper:before {
                              content: "Выбрать";
                            }
                          </style>
                        </div>
                        
                        <button type="submit" name="submit" class="btn btn-success w-100">Загрузить</button>
                      </form>
                    </div>
                  </div>
                </div>

              </div>
            </div>
            <p class="p-4">ВНИМАНИЕ: Перед импортом сделайте экспорт текущих данных. После импорта старые данные удаляются без возврата!</p>
          </div>
          <div class="col-xl-7 col-lg-12 col-md-12">
            <div class="card">
              <div class="card-body">
                <div class="buyer-seller">

                  <div class="table-responsive">
                    <table class="table">
                      <tbody>
                        <tr>
                          <td><span class="text-primary">Действие </span></td>
                          <td><span class="text-primary" id="clear" onclick="$('#clear').hide(); $('#complete').show();">Стереть данные</span><span class="text-warning" style="display: none;" id="complete" onclick="window.location.href='/buy-sell?clear=true'">Вы уверены?</span></td>
                        </tr>
                        <tr>
                          <td>Счета / депозиты</td>
                          <td><?=DBC::count('deposit', array('uid'=>$_SESSION['user']['id']))?> Активных</td>
                        </tr>
                        <tr>
                          <td>Удаленные депозиты</td>
                          <td>
                            <?php
                              $history = $GLOBALS['connect']->query("SELECT `did` FROM `history` WHERE `uid` = '".$_SESSION['user']['id']."'");
                              $i = 0; $list=array();
                              foreach($history as $ch){
                                if(1>intval(DBC::count('deposit', array('id'=>$ch['did'])))){
                                  if(!in_array($ch['did'], $list)){
                                    $i++;
                                    $list[] = $ch['did'];
                                  }
                                }
                              }
                              echo $i." удаленных";
                            ?>
                          </td>
                        </tr>
                        <tr>
                          <td>Транзакции</td>
                          <td><?=DBC::count('history', array('uid'=>$_SESSION['user']['id']))?> совершенно</td>
                        </tr>
                        <tr>
                          <td>Приходы</td>
                          <td>
                            <div class="text-success"><?=DBC::count('history', array('uid'=>$_SESSION['user']['id'], 'type'=>'plus'))?> приходов</div>
                          </td>
                        </tr>
                        <tr>
                          <td>Расходы</td>
                          <td>
                            <div class="text-danger"><?=DBC::count('history', array('uid'=>$_SESSION['user']['id'], 'type'=>'minus'))?> расходов</div>
                          </td>
                        </tr>
                        <tr>
                          <td>Дата регистрации</td>
                          <td><?=$user['create']?></td>
                        </tr>
                        <tr>
                          <td>Активность</td>
                          <td><?=$user['last']?></td>
                        </tr>
                      </tbody>
                    </table>
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
<!-- jquery.validate -->
<script src="public/plugins/validator/jquery.validate.js"></script>
<script src="public/plugins/validator/validator-init.js"></script>
<!-- venobox -->
<script src="public/plugins/venobox/venobox.min.js"></script> 
<!-- scripts -->
<script src="public/js/scripts.js"></script>
<script>
  $('.venobox').venobox(); 
</script>
</body>
</html>
