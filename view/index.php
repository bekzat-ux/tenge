<?php
if(isset($_POST['type']) && $_POST['type']=='operation'){
  $data = array(
    'uid'=>$_SESSION['user']['id'],
    'did'=>$_POST['deposit'],
    'type'=>$_POST['operation'],
    'summa'=>$_POST['summa'],
    'comment'=>($_POST['desc']?$_POST['desc']:'Без комментарий'),
    'time'=>date('Y-m-d H:i'),
  );
  DBC::insert('history', $data);
  $deposit = DBC::select('deposit', 'id', $_POST['deposit']);
  if($_POST['operation']=='plus'){
    $newb = intval($deposit['balance'])+intval($_POST['summa']);
  }
  else{
    $newb = intval($deposit['balance'])-intval($_POST['summa']);
  }
  DBC::update('deposit', array('balance'=>$newb), $_POST['deposit']);
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
    <!-- toastr -->
    <link rel="stylesheet" href="public/plugins/toastr/toastr.min.css">
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
  $deposits   = $GLOBALS['connect']->query("SELECT * FROM `deposit` WHERE `uid` = '".$_SESSION['user']['id']."'");
  $history    = $GLOBALS['connect']->query("SELECT * FROM `history` WHERE `uid` = '".$_SESSION['user']['id']."' ORDER BY `id` DESC");
  $history24  = $GLOBALS['connect']->query("SELECT * FROM `history` WHERE `uid` = '".$_SESSION['user']['id']."' AND `time` LIKE '".date('Y-m-d')."%' ORDER BY `id` DESC");


  $plus     = 0; $minus   = 0;  $total   = 0;

  foreach($deposits as $deposit){
      $total  = $total+intval($deposit['balance']);
      $one    = 100/$total;
      $val    = $total/100;
  }

  foreach($history as $op){
    if($op['type']=='minus'){
      $minus  = $minus+intval($op['summa']);
    } 
    else if($op['type']=='plus'){
      $plus   = $plus+intval($op['summa']);
    }
  }  
  
  if($plus>$minus){
    $mid    = "+".strval($plus-$minus);
    $cf     = "+".strval(number_format($one*($plus-$minus), 2));
    $cfn    = number_format($one*($plus-$minus), 2);
    $status = 'Ростущий актив';
  }
  else{
    $mid    = "-".strval($minus-$plus);
    $cf     = "-".strval(number_format($one*($minus-$plus), 2));
    $cfn    = number_format($one*($minus-$plus), 2);
    $status = 'Убывающий актив';
  }

  $minus24  = 0;  $plus24 = 0;
  foreach($history24 as $op24){
    if($op24['type']=='minus'){
      $minus24  = $minus24+intval($op24['summa']);
    } 
    else if($op24['type']=='plus'){
      $plus24   = $plus24+intval($op24['summa']);
    }
  }  

  if($plus24>$minus24){
    $mid24    = "+".strval($plus24-$minus24);
    $cf24     = "+".strval(number_format($one*($plus24-$minus24), 2));
    $cfn24    = number_format($one*($plus24-$minus24), 2);
  }
  else{
    $mid24    = "-".strval($minus24-$plus24);
    $cf24     = "-".strval(number_format($one*($minus24-$plus24), 2));
    $cfn24    = number_format($one*($minus24-$plus24), 2);
  }
  ?>
    

    <!--===================== Content body Start =====================-->
    <div class="content-body">
      <!--===================== Page title Start =====================-->
<div class="page_title">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="page_title-content">
          <p>Добро пожаловать,<span> <?=$user['name']?>!</span></p>
        </div>
      </div>
    </div>
  </div>
</div>
<!--===================== Page title End =====================-->
      <div class="container">
        <div class="row">
          <div class="col-xl-12">
            <div class="row">
              <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="widget-card">
                  <div class="widget-title">
                    <h5>Доход</h5>
                    <p class="text-success"><?=number_format($plus*$one, 2)?>% <span><i class="las la-arrow-up"></i></span></p>
                  </div>
                  <div class="widget-info">
                    <h3>₸ +<?=$plus?></h3>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="widget-card">
                  <div class="widget-title">
                    <h5>Расход</h5>
                    <p class="text-danger"><?=number_format($minus*$one, 2)?>% <span><i class="las la-arrow-down"></i></span></p>
                  </div>
                  <div class="widget-info">
                    <h3>₸ -<?=$minus?></h3>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="widget-card">
                  <div class="widget-title">
                    <h5>Разница</h5>
                    <?php
                    if($plus>$minus){
                      ?>
                      <p class="text-success"><?=number_format(($plus-$minus)*$one, 2)?>% <span><i class="las la-arrow-up"></i></span></p>
                      <?php
                    }
                    else{
                      ?>
                      <p class="text-danger"><?=number_format(($minus-$plus)*$one, 2)?>% <span><i class="las la-arrow-down"></i></span></p>
                      <?php
                    }
                    ?>
                  </div>
                  <div class="widget-info">
                    <h3>₸ <?=$mid?></h3>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="widget-card">
                  <div class="widget-title">
                    <h5>Коэффициент </h5>
                    <?php
                    if($plus>$minus){
                      ?>
                      <p class="text-success">Рост<span></span></p>
                      <?php
                    }
                    else{
                      ?>
                      <p class="text-danger">Убыток<span></i></span></p>
                      <?php
                    }
                    ?>
                  </div>
                  <div class="widget-info">
                    <h3><?=$cf?> %</h3>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12 col-xl-8">
                <div class="card profile_chart transparent px-0">
                  <div class="card-header px-0">
                    <div class="chart_current_data">
                      <h3>Активы: <?=number_format($total-($val*$cfn), 2)?> <span>₸</span></h3>
                      <p class="text-<?=($status=='Убывающий актив'?'danger':'success')?>"><?=$status?></p>
                    </div>
                    <div class="duration-option">
                      <a id="one_month" class="active mb-2 mb-sm-0">Общая статистика</a>
                    </div>
                  </div>
                  <div class="card-body px-0">
                    <div id="timeline-chart"></div>
                    <div class="chart-content text-center">
                      <div class="row">
                        <div class="col-12 col-md-6 col-xl-4">
                          <div class="chart-stat">
                            <p class="mb-1">Расход за сутки</p>
                            <strong><?=$minus24?> ₸</strong>
                          </div>
                        </div>
                        <div class="col-12 col-md-6 col-xl-4">
                          <div class="chart-stat">
                            <p class="mb-1">Доход за сутки</p>
                            <strong><?=$plus24?> ₸</strong>
                          </div>
                        </div>
                        <div class="col-12 col-md-6 col-xl-4">
                          <div class="chart-stat">
                            <p class="mb-1">Разница за сутки</p>
                            <strong><?=$mid24?> ₸</strong>
                          </div>
                        </div>
                        <div class="col-12 col-md-6 col-xl-4">
                          <div class="chart-stat">
                            <p class="mb-1">Коэффициент за сутки</p>
                            <strong><?=$cf24?>%</strong>
                          </div>
                        </div>
                        <div class="col-12 col-md-6 col-xl-4">
                          <div class="chart-stat">
                            <p class="mb-1">Транзакции за сутки</p>
                            <strong><?=$history24->num_rows?></strong>
                          </div>
                        </div>
                        <div class="col-12 col-md-6 col-xl-4">
                          <div class="chart-stat">
                            <p class="mb-1">Статус счетов</p>
                            <strong><?=($plus24>$minus24?'Рост':'Убыток')?></strong>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-4 col-lg-12 col-xxl-4">
                <div class="card balance-widget transparent">
                  <div class="card-body">
                    <div id="wallet-chart"></div>
                    <div class="balance-widget">
                      <h4>Общий баланс : <strong> ₸ <?=$total?></strong></h4>
                      <ul class="list-unstyled">
                        <?php
                        foreach($deposits as $deposit){
                          ?>
                            <li class="d-flex align-items-center justify-content-between border-bottom  rounded-default">
                              <div class="d-flex align-items-center">
                                  <h5 class="mb-0"><?=$deposit['name']?></h5>
                              </div>
                              <div class="text-end">
                                  <h5 class=" mb-2"><?=$deposit['balance']?> ₸</h5>
                                  <span><?=($deposit['last'] ? $deposit['last'].' ₸':'Новый счет')?></span>
                              </div>
                          </li>
                          <?php
                        }
                        ?>
                    </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-12">
            <div class="row">
              <div class="col-xl-6 col-lg-12 col-xxl-4">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">Добавить транзакцию</h4>
                  </div>
                  <div class="card-body">
                    <div class="buy-sell-widget">
                      <form method="post" action="/" class="currency_validate">
                        <input type="text" value="operation" name="type" readonly hidden>
                        <div class="form-group">
                          <label class="me-sm-2">Тип операции</label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <label class="input-group-text"><i class="fas fa-tasks"></i></label>
                            </div>
                            <select name='operation' class="form-select">
                              <option value="plus">Приход</option>
                              <option value="minus">Расход</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="me-sm-2">Счет / Депозит</label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <label class="input-group-text"><i class="fas fa-university"></i></label>
                            </div>
                            <select class="form-select" name="deposit">
                              <?php
                              foreach($deposits as $dep){
                                ?>
                                  <option value="<?=$dep['id']?>"><?=$dep['name']?></option>
                                <?php
                              }
                              ?>
                            </select>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="me-sm-2">Сумма операции</label>
                          <div class="input-group">
                            <input type="number" name="summa" class="form-control" placeholder="0.00 ₸">
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="me-sm-2">Комментарий</label>
                          <div class="input-group">
                            <input type="text" name="desc" class="form-control" placeholder="Комментировать (*необязательно)">
                          </div>
                          <div class="d-flex justify-content-between mt-3">
                            <p class="mb-0">Подтвержденную операцию нельзя отменять</p>
                          </div>
                        </div>
                        <button class="btn btn-success w-100">Подтвердить транзакцию</button>

                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-xl-6 col-lg-12 col-xxl-8">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">Последние транзакции </h4>
                  </div>
                  <div class="card-body">
                    <div class="transaction-widget">
                      <ul class="p-0 m-0 list-unstyled">
                        <?php
                        $i = 0;
                        $history  = $GLOBALS['connect']->query("SELECT * FROM `history` WHERE `uid` = '".$_SESSION['user']['id']."' ORDER BY `id` DESC LIMIT 6");
                        foreach($history as $row){
                          $i++;
                          if($row['type']=='plus'){
                            ?>
                              <li class="d-sm-flex align-items-center justify-content-between">
                                  <div class="d-flex align-items-center">
                                      <span class="icon me-3 rounded-circle d-flex align-items-center justify-content-center bg-success">
                                        <i class="las la-arrow-up"></i>
                                      </span>
                                      <div>
                                          <p class="mb-2"><?=$row['time']?></p>
                                          <h5><?=$row['comment']?></h5>
                                      </div>
                                  </div>
                                  <div class="ms-5 mt-3 text-sm-end text-sm-end">
                                      <h4><?=$row['summa']?> ₸</h4>
                                  </div>
                              </li>
                            <?php
                          }
                          else{
                            ?>
                              <li class="d-sm-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <span class="icon me-3 rounded-circle d-flex align-items-center justify-content-center bg-danger">
                                      <i class="las la-arrow-down"></i>
                                    </span>
                                    <div>
                                        <p class="mb-2"><?=$row['time']?></p>
                                        <h5><?=$row['comment']?></h5>
                                    </div>
                                </div>
                                <div class="ms-5 mt-3 text-sm-end">
                                    <h4><?=$row['summa']?> ₸</h4>
                                </div>
                            </li>
                            <?php
                          }
                        }

                        if($i<1){
                          ?>
                            <li class="d-sm-flex align-items-center justify-content-between">
                              <div class="d-flex align-items-center">
                                  <div>
                                      <h5>Транзакции не найдены</h5>
                                  </div>
                              </div>
                          </li>
                          <?php
                        }
                        ?>
                    </ul>


                    </div>
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

<!-- circle-progress -->
<script src="public/plugins/circle-progress/circle-progress.min.js"></script>
<script src="public/plugins/circle-progress/circle-progress-init.js"></script>
<!--  flot-chart js -->
<script src="public/plugins/apexchart/apexcharts.min.js"></script>
<script>
  (function ($) {
    var options = {
        chart: {
            type: "area",
            height: 300,
            foreColor: "#8C87C2",
            fontFamily: 'Rubik, sans-serif',
            stacked: true,
            dropShadow: {
                enabled: true,
                enabledSeries: [0],
                top: -2,
                left: 2,
                blur: 5,
                opacity: 0.06
            },
            toolbar: {
                show: false,
            }
        },
        colors: ['#7B6FFF', '#7395FF'],
        stroke: {
            curve: "smooth",
            width: 3
        },
        dataLabels: {
            enabled: false
        },
        series: [{
            name: 'Доход',
            data: generateDayWiseTimeSeries(0, 30),
        }, {
            name: 'Расход',
            data: generateDayWiseTimeSeries(1, 30)
        }],
        markers: {
            size: 0,
            strokeColor: "#fff",
            strokeWidth: 3,
            strokeOpacity: 1,
            fillOpacity: 1,
            hover: {
                size: 6
            }
        },
        xaxis: {
            type: "datetime",
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            }
        },
        yaxis: {
            labels: {
                offsetX: -10,
                offsetY: 0
            },
            tooltip: {
                enabled: true,
            }
        },
        grid: {
            show: false,
            padding: {
                left: -5,
                right: 5
            }
        },
        tooltip: {
            x: {
                format: "dd MMM yyyy"
            },
        },
        legend: {
            position: 'top',
            horizontalAlign: 'left'
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.5,
                opacityTo: 0,
                stops: [0, 100, 100]
            }
        },
    };

    var chart = new ApexCharts(
        document.querySelector("#timeline-chart"),
        options
    );

    chart.render();

    var resetCssClasses = function (activeEl) {
        var els = document.querySelectorAll("button");
        Array.prototype.forEach.call(els, function (el) {
            el.classList.remove('active');
        });

        activeEl.target.classList.add('active')
    }


    function generateDayWiseTimeSeries(s, count) {
        var values = [[
          <?php
          foreach($history as $stat){
            if($stat['type']=='plus'){
              echo $stat['summa'].",";
              $ones   = intval($stat['summa'])/100;
              $first  = intval($stat['summa']);
              for($i=0; $i<10; $i++){
                echo strval($first-($ones*10)).",";
                // $first=$first-($ones*10);
              }
            }
            else{
              echo "0,";
            }
          }  
          ?>
        ],
        [
          <?php
          foreach($history as $stat){
            if($stat['type']=='minus'){
              echo $stat['summa'].",";
              $ones   = intval($stat['summa'])/100;
              $first  = intval($stat['summa']);
              for($i=0; $i<10; $i++){
                echo strval($first-($ones*10)).",";
                // $first=$first-($ones*10);
              }
            }
            else{
              echo "0,";
            }
          }  
          ?>
        ]];
        var i = 0;
        var series = [];
        var x = new Date().getTime();
        while (i < count) {
            series.push([x, values[s][i]]);
            x +=  1;
            i++;
        }
        return series;
    }


})(jQuery);
</script>





<script>
  (function ($) {

    // BTC Line Chart

    var options = {
        chart: {
            height: 100,
            type: 'line',
            zoom: {
                enabled: false
            },

            toolbar: {
                show: false,
            }
        },
        series: [{
            name: "Desktops",
            data: [10, 41, 35, 51, 49, 62, 69, 91, 80, 10, 41, 35, 51, 49, 62, 69, 91, 80]
        }],
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2,
            colors: ["#7391FF"],
        },
        grid: {
            show: false,
        },
        tooltip: {
            enabled: false,
            x: {
                format: "dd MMM yyyy"
            },
        },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
            axisBorder: {
                show: false
            },

            labels: {
                show: false
            }
        },
    }

    var chart = new ApexCharts(
        document.querySelector("#btcChart"),
        options
    );
    chart.render();

    // BTC Line Chart

    var options = {
        chart: {
            height: 100,
            type: 'line',
            zoom: {
                enabled: false
            },

            toolbar: {
                show: false,
            }
        },
        series: [{
            name: "Desktops",
            data: [10, 41, 35, 51, 49, 62, 69, 91, 80, 10, 41, 35, 51, 49, 62, 69, 91, 80]
        }],
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2,
            colors: ["#7391FF"],
        },
        grid: {
            show: false,
        },
        tooltip: {
            enabled: false,
            x: {
                format: "dd MMM yyyy"
            },
        },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
            axisBorder: {
                show: false
            },

            labels: {
                show: false
            }
        },
    }

    var chart = new ApexCharts(
        document.querySelector("#ltcChart"),
        options
    );
    chart.render();

    // BTC Line Chart

    var options = {
        chart: {
            height: 100,
            type: 'line',
            zoom: {
                enabled: false
            },

            toolbar: {
                show: false,
            }
        },
        series: [{
            name: "Desktops",
            data: [10, 41, 35, 51, 49, 62, 69, 91, 80, 10, 41, 35, 51, 49, 62, 69, 91, 80]
        }],
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2,
            colors: ["#7391FF"],
        },
        grid: {
            show: false,
        },
        tooltip: {
            enabled: false,
            x: {
                format: "dd MMM yyyy"
            },
        },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
            axisBorder: {
                show: false
            },

            labels: {
                show: false
            }
        },
    }

    var chart = new ApexCharts(
        document.querySelector("#xrpChart"),
        options
    );
    chart.render();

    // BTC Line Chart

    var options = {
        chart: {
            height: 100,
            type: 'line',
            zoom: {
                enabled: false
            },

            toolbar: {
                show: false,
            }
        },
        series: [{
            name: "Desktops",
            data: [10, 41, 35, 51, 49, 62, 69, 91, 80, 10, 41, 35, 51, 49, 62, 69, 91, 80]
        }],
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2,
            colors: ["#7391FF"],
        },
        grid: {
            show: false,
        },
        tooltip: {
            enabled: false,
            x: {
                format: "dd MMM yyyy"
            },
        },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
            axisBorder: {
                show: false
            },

            labels: {
                show: false
            }
        },
    }

    var chart = new ApexCharts(
        document.querySelector("#dashChart"),
        options
    );
    chart.render();


    <?php
    $procent  = ''; $title  = '';
    foreach($deposits as $dep){
      $procent  .=  strval(number_format($one*$dep['balance'],2)).",";
      $title    .=  "'".$dep['name']."',";
    }
    ?>
    var options = {
        series: [<?=$procent?>],
        chart: {
            height: 300,
            type: 'radialBar',
        },
        tooltip: {
            enabled: true,
        },
        plotOptions: {
            radialBar: {
                offsetY: 0,
                startAngle: 0,
                endAngle: 360,
                hollow: {
                    margin: 5,
                    size: '20%',
                    background: 'transparent',
                    image: undefined,
                },
                dataLabels: {
                    name: {
                        show: false,
                    },
                    value: {
                        show: false,
                    }
                }
            }
        },
        colors: [
            'rgba(94, 55, 255,1)',
            'rgba(94, 55, 255,0.7)',
            'rgba(94, 55, 255,0.3)',
            'rgba(94, 55, 255,0.1)'
        ],
        labels: [<?=$title?>],
        legend: {
            show: false,
            floating: true,
            fontSize: '16px',
            position: 'left',
            offsetX: 160,
            offsetY: 15,
            labels: {
                useSeriesColors: true,
            },
            markers: {
                size: 0
            },
            formatter: function (seriesName, opts) {
                return seriesName + ":  " + opts.w.globals.series[opts.seriesIndex]
            },
            itemMargin: {
                vertical: 3
            }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                legend: {
                    show: false
                }
            }
        }]
    };

    var chart = new ApexCharts(document.querySelector("#wallet-chart"), options);
    chart.render();


})(jQuery);
</script>
<!-- scripts -->
<script src="public/js/scripts.js"></script>

</body>
</html>