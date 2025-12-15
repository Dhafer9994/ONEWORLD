<?php

$current_page = basename($_SERVER['PHP_SELF']);
$is_users_page = strpos($_SERVER['REQUEST_URI'], 'liste.php') !== false || $current_page == 'liste.php';
$is_history_page = strpos($_SERVER['REQUEST_URI'], 'hist.php') !== false || $current_page == 'hist.php';
$is_dashboard_page = strpos($_SERVER['REQUEST_URI'], 'dash.php') !== false || $current_page == 'dash.php';


$view_path = '';
?>

<aside class="left-sidebar bg-sidebar">
  <div id="sidebar" class="sidebar sidebar-with-footer">
    <div class="app-brand">
      <a href="<?php echo $view_path; ?>dash.php" title="OneWorld Dashboard">
        <img src="../../../images/logo.png" alt="OneWorld Logo" class="brand-logo">
        <span class="brand-name">OneWorld</span>
      </a>
    </div>

    <div data-simplebar style="height: 100%;">
      <ul class="nav sidebar-inner" id="sidebar-menu">

        <li class="has-sub <?php echo $is_dashboard_page ? 'active expand' : ''; ?>">
          <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#dashboard">
            <i class="mdi mdi-view-dashboard-outline"></i>
            <span class="nav-text">Dashboard</span> <b class="caret"></b>
          </a>
          <ul class="collapse <?php echo $is_dashboard_page ? 'show' : ''; ?>" id="dashboard" data-parent="#sidebar-menu">
            <div class="sub-menu">
              <li class="<?php echo $is_dashboard_page ? 'active' : ''; ?>">
                <a class="sidenav-item-link" href="<?php echo $view_path; ?>dash.php">
                  <span class="nav-text">Tableau de bord</span>
                </a>
              </li>
            </div>
          </ul>
        </li>
        
        <li class="has-sub">
          <a class="sidenav-item-link" data-toggle="collapse" data-target="#offers">
            <i class="mdi mdi-percent"></i>
            <span class="nav-text">Offres</span> <b class="caret"></b>
          </a>
          <ul class="collapse" id="offers">
            <div class="sub-menu">
              <li><a class="sidenav-item-link" href="<?php echo $view_path; ?>offers.php">Gestion des offres</a></li>
              <li><a class="sidenav-item-link" href="<?php echo $view_path; ?>offers-stats.php">Statistiques offres</a></li>
            </div>
          </ul>
        </li>

        <li class="has-sub <?php echo $is_users_page ? 'active expand' : ''; ?>">
          <a class="sidenav-item-link" data-toggle="collapse" data-target="#clients">
            <i class="mdi mdi-account-multiple"></i>
            <span class="nav-text">Utilisateurs</span> <b class="caret"></b>
          </a>
          <ul class="collapse <?php echo $is_users_page ? 'show' : ''; ?>" id="clients" data-parent="#sidebar-menu">
            <div class="sub-menu">
              <li class="<?php echo $is_users_page ? 'active' : ''; ?>">
                <a class="sidenav-item-link" href="<?php echo $view_path; ?>liste.php">
                  <span class="nav-text">Gestion des utilisateurs</span>
                </a>
              </li>
            </div>
          </ul>
        </li>

        <li class="has-sub <?php echo $is_history_page ? 'active expand' : ''; ?>">
          <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#history">
            <i class="mdi mdi-history"></i>
            <span class="nav-text">Historique</span> <b class="caret"></b>
          </a>
          <ul class="collapse <?php echo $is_history_page ? 'show' : ''; ?>" id="history" data-parent="#sidebar-menu">
            <div class="sub-menu">
              <li class="<?php echo $is_history_page ? 'active' : ''; ?>">
                <a class="sidenav-item-link" href="<?php echo $view_path; ?>hist.php">
                  <span class="nav-text">Historique des connexions</span>
                </a>
              </li>
            </div>
          </ul>
        </li>

      </ul>
    </div>

    <div class="sidebar-footer">
      <hr class="separator mb-0" />
      <div class="sidebar-footer-content">
        <h6 class="text-uppercase">CPU Usage <span class="float-right">40%</span></h6>
        <div class="progress progress-xs"><div class="progress-bar active" style="width: 40%;"></div></div>
        <h6 class="text-uppercase mt-2">Memory Usage <span class="float-right">65%</span></h6>
        <div class="progress progress-xs"><div class="progress-bar progress-bar-warning" style="width: 65%;"></div></div>
      </div>
    </div>

  </div>
</aside>