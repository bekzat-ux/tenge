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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.15.6/xlsx.full.min.js"></script>
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
        <div class="col-xl-12">
          <div class="card table-card border-0 rounded-default px-4 py-3 box-shadow">
            <div class="card-header bg-transparent border-0 p-0">
              <h5 class="card-title border-0 p-0 pb-2">История транзакции</h5>
              <button id="downloadBtn">Скачать Excel</button>
            </div>
            <div class="card-body p-0">
              <table id="myTable">
                <tbody>
                  <?php
                      $deposit  = $GLOBALS['connect']->query("SELECT * FROM `deposit` WHERE `uid` = '".$_SESSION['user']['id']."'");
                      foreach($deposit as $dep){
                        $depo[$dep['id']] = $dep['name'];  
                      }
                      $history  = $GLOBALS['connect']->query("SELECT * FROM `history` WHERE `uid` = '".$_SESSION['user']['id']."' ".(isset($_GET['s'])?"AND `comment` LIKE '%".$_GET['s']."%'":"")."");
                      foreach($history as $row){
                        if($row['type']=='minus'){
                          ?>
                            <tr>
                              <td data-label="Arrow Icon"><i class="la la-arrow-down fa-md rounded-circle text-white bg-danger"></i></td>
                              <td data-label="Badge"><span class="badge inline-flex align-items-center bg-danger text-white"><?=$row['time']?></span></td>
                              <td data-label="Card"><span class="text-purple-200 fw-bold"><?=$row['comment']?></span></td>
                              <td data-label="Amout BTC"><span class="text-danger fw-bold">-<?=$row['summa']?> ₸ </span></td>
                              <td data-label="Amout USD"><span class="text-purple-200 fw-bold"><?=($depo[$row['did']]!=''?$depo[$row['did']]:'<span style="color: orange;">Депозит удален</span>')?></span></td>
                            </tr>
                          <?php
                        }
                        else{
                          ?>
                            <tr>
                              <td scope="row" data-label="Arrow Icon"><i class="la la-arrow-up fa-md rounded-circle text-white bg-success"></i></td>
                              <td data-label="Badge"><span class="badge inline-flex align-items-center bg-success text-white"><?=$row['time']?></span></td>
                              <td data-label="Card"><span class="text-purple-200 fw-bold"><?=$row['comment']?></span></td>
                              <td data-label="Amout BTC"><span class="text-success fw-bold">+<?=$row['summa']?> ₸ </span></td>
                              <td data-label="Amout USD"><span class="text-purple-200 fw-bold"><?=($depo[$row['did']]!=''?$depo[$row['did']]:'<span style="color: orange;">Депозит удален</span>')?></span></td>
                            </tr>
                          <?php
                        }
                      }
                  ?>
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

<script>
  function generateFolderName() {
      // Получаем текущую дату и время
      var currentDate = new Date();
      
      // Получаем год, месяц и день
      var year = currentDate.getFullYear();
      var month = (currentDate.getMonth() + 1).toString().padStart(2, '0'); // добавляем ноль в начале, если месяц < 10
      var day = currentDate.getDate().toString().padStart(2, '0'); // добавляем ноль в начале, если день < 10
      
      // Получаем часы, минуты и секунды
      var hours = currentDate.getHours().toString().padStart(2, '0'); // добавляем ноль в начале, если часы < 10
      var minutes = currentDate.getMinutes().toString().padStart(2, '0'); // добавляем ноль в начале, если минуты < 10
      var seconds = currentDate.getSeconds().toString().padStart(2, '0'); // добавляем ноль в начале, если секунды < 10
      
      // Составляем название папки в формате "ГГГГ-ММ-ДД_ЧЧ-ММ-СС"
      var folderName = year + month + day + '_' + hours + minutes + seconds;
      
      return folderName;
  }

  document.getElementById("downloadBtn").addEventListener("click", function() {
  // Получаем данные из таблицы
  var table = document.getElementById("myTable");
  var rows = table.getElementsByTagName("tr");
  
  // Создаем новый экземпляр Workbook
  var wb = XLSX.utils.book_new();
  var ws = XLSX.utils.table_to_sheet(table);
  XLSX.utils.book_append_sheet(wb, ws, "Sheet1");

  // Сохраняем Workbook в файл
  var wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });
  function s2ab(s) {
    var buf = new ArrayBuffer(s.length);
    var view = new Uint8Array(buf);
    for (var i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
    return buf;
  }
  // Пример использования функции
  var folderName = "Tenge_История-транзакции_"+generateFolderName()+".xlsx";
  saveAs(new Blob([s2ab(wbout)], { type: "application/octet-stream" }), folderName);
});
</script>


<!-- Scripts -->
<script src="public/js/scripts.js"></script>
</body>
</html>


