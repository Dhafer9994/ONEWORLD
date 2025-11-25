<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= isset($page_title) ? $page_title : "OneWorld Dashboard" ?></title>

    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500|Poppins:400,500,600,700|Roboto:400,500" rel="stylesheet" />
    <link href="https://cdn.materialdesignicons.com/4.4.95/css/materialdesignicons.min.css" rel="stylesheet" />

    <!-- PLUGINS CSS -->
    <link href="assets/plugins/simplebar/simplebar.css" rel="stylesheet" />
    <link href="assets/plugins/nprogress/nprogress.css" rel="stylesheet" />

    <!-- SLEEK CSS -->
    <link id="sleek-css" rel="stylesheet" href="assets/css/sleek.css" />

    <!-- CUSTOM ONEWORLD CSS -->
    <link rel="stylesheet" href="assets/css/oneworld.css">

    <!-- FAVICON -->
    <link href="assets/img/favicon.png" rel="shortcut icon" />

    <script src="assets/plugins/nprogress/nprogress.js"></script>
</head>
<body class="header-fixed sidebar-fixed sidebar-dark header-light">
<script>
  NProgress.configure({ showSpinner: false });
  NProgress.start();
</script>

<div class="wrapper">
<header class="main-header" id="header">
  <nav class="navbar navbar-static-top navbar-expand-lg">
    <button id="sidebar-toggler" class="sidebar-toggle">
      <span class="sr-only">Toggle navigation</span>
    </button>

    <div class="search-form d-none d-lg-inline-block">
      <div class="input-group">
        <button id="search-btn" class="btn btn-flat">
          <i class="mdi mdi-magnify"></i>
        </button>
        <input type="text" class="form-control" placeholder="Rechercher..." autocomplete="off" />
      </div>
    </div>

    <div class="navbar-right">
      <ul class="nav navbar-nav">
        <li class="dropdown notifications-menu custom-dropdown">
          <button class="dropdown-toggle notify-toggler custom-dropdown-toggler">
            <i class="mdi mdi-bell-outline"></i>
          </button>
        </li>

        <li class="dropdown user-menu">
          <button class="dropdown-toggle nav-link" data-toggle="dropdown">
            <img src="assets/img/user/user.png" class="user-image" />
            <span class="d-none d-lg-inline-block">Admin OneWorld</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-right">
            <li class="dropdown-header">
              <img src="assets/img/user/user.png" class="img-circle" />
              <div class="d-inline-block">
                Admin OneWorld <small>admin@oneworld.com</small>
              </div>
            </li>
            <li><a href="profile.php"><i class="mdi mdi-account"></i> Mon Profil</a></li>
            <li class="dropdown-footer"><a href="login.php"><i class="mdi mdi-logout"></i> Déconnexion</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>
