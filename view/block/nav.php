   <!--===================== Sidebar Start =====================-->
   <?php
   $user = DBC::select('users', 'id', $_SESSION['user']['id']);
   $urlComponents = parse_url($_SERVER['REQUEST_URI']);
   ?>
 <section class="sidebar">
    <nav class="navbar navbar-expand-lg navbar-light flex-nowrap flex-column justify-content-between align-items-start p-0">
        <div class="mb-lg-5 w-100">
            <div class="d-none d-lg-block">
                <a class="navbar-brand d-flex align-items-start mb-5">
                    <img src="public/images/logo.png" alt="" class="mb-2">
                    <span class="fw-bold ms-2">Tenge</span>
                </a>
            </div>
            <div class="collapse navbar-collapse collapse show" id="navbarNavAltMarkup">
              <ul class="navbar-nav flex-row flex-lg-column justify-content-between align-items-center align-items-lg-start">
              <li>
                  <a class="nav-link d-flex align-items-center <?=($urlComponents['path']=='/'?'active':'')?>" href="/" data-tooltip="home" data-placement="top">
                    <i class="mdi mdi-home"></i><div class="nav-text d-none d-md-block">Главная</div>
                  </a>
              </li>
              <li>
                  <a class="nav-link d-flex align-items-center <?=($urlComponents['path']=='/history'?'active':'')?>" href="/history" data-tooltip="history" data-placement="top">
                    <i class="mdi mdi-view-dashboard"></i><div class="nav-text d-none d-md-block">История</div>
                  </a>
              </li>
              <li>
                  <a class="nav-link d-flex align-items-center @@buy-sell <?=($urlComponents['path']=='/buy-sell'?'active':'')?>" href="buy-sell" data-tooltip="exchange" data-placement="top">
                    <i class="mdi mdi-repeat"></i><div class="nav-text d-none d-md-block">База</div>
                  </a>
              </li>
              <li>
                  <a class="nav-link d-flex align-items-center @@accounts <?=($urlComponents['path']=='/accounts'?'active':'')?>" href="accounts" data-tooltip="account" data-placement="top">
                    <i class="mdi mdi-account"></i><div class="nav-text d-none d-md-block">Аккаунт</div>
                  </a>
              </li>
              <li>
                  <a class="nav-link d-flex align-items-center @@settings <?=($urlComponents['path']=='/settings'?'active':'')?>" href="settings" data-tooltip="settings" data-placement="top">
                    <i class="mdi mdi-cog"></i></i><div class="nav-text d-none d-md-block">Настройки</div>
                  </a>
              </li>
              </ul>
            </div>
        </div>
        <div class="d-none d-lg-block w-100 pb-5">
            <div class="copyright">
                <p>&copy; <script>document.write(new Date().getFullYear());</script> <a>Bekzat</a></p>
            </div>
        </div>
    </nav>
</section>
  <!--===================== Sidebar End =====================-->
  <section class="ms-lg-240 px-lg-4 px-xl-0 mb-100 mb-lg-0">
    <!--===================== Navigation Start =====================-->
<div class="navigation-2 d-flex sticky-top px-lg-4 px-xl-0">
  <div class="container">
      <div class="row">
          <div class="col-12 d-flex justify-content-between align-items-center">
            <div class="d-block d-lg-none">
              <a class="navbar-brand d-flex align-items-start">
                  <img src="public/images/logo.png" alt="" class="mb-2">
                  <span class=" fw-bold  ms-2">Tenge</span>
                </a>
            </div>
            <div class="search-form">
              <form action="history" method="get">
                    <div class="input-group">
                      <input type="text" placeholder="Поиск транзакции" value="<?=(isset($_GET['s'])?$_GET['s']:"")?>" name="s" class="form-control">
                      <div class="input-group-append  d-flex">
                          <button class="">
                              <span class="input-text"><i class="fa fa-search"></i></span>
                          </button>
                      </div>
                  </div>
              </form>
            </div>
            <!-- search form end -->
            <div class="d-flex align-items-center">
              <button type="button" class="search-btn rounded-circle d-md-none border-0">
                <span class="input-text d-flex align-items-center justify-content-center"><i class="fa fa-search"></i></span>
              </button>
                <div class="dropdown text-center text-sm-start">
                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown">
                        <span class="user-icon rounded-circle text-white"><i class="mdi mdi-account"></i></span>
                        <span class="user-name fw-medium ms-2 d-none d-sm-inline-block"><?=$user['email']?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-light w-100 box-shadow border-0 p-0 mt-2 rounded" aria-labelledby="dropdownMenuButton2">
                        <li><a class="dropdown-item" href="accounts"><i class="mdi mdi-account"></i>Аккаунт</a></li>
                        <li><a class="dropdown-item" href="history"><i class="la la-book"></i>История</a></li>
                        <li><a class="dropdown-item" href="settings"><i class="mdi mdi-cog"></i>Параметры</a></li>
                        <li><a class="dropdown-item logout" href="?closeSession=true"><i class="mdi mdi-logout"></i>Выйти</a></li>
                    </ul>
                </div>
                <!-- dropdown end -->
            </div>

          </div>
      </div>
  </div>
</div>
<!--===================== Navigation End =====================-->