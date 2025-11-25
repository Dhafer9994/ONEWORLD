<aside class="left-sidebar bg-sidebar">
  <div id="sidebar" class="sidebar sidebar-with-footer">
    <div class="app-brand">
      <a href="index.php" title="OneWorld Dashboard">
        <img src="assets/img/logo.png" alt="Logo" class="brand-logo">
        <span class="brand-name">OneWorld</span>
      </a>
    </div>

    <div data-simplebar style="height: 100%;">
      <ul class="nav sidebar-inner" id="sidebar-menu">

        <!-- Dashboard -->
        <li class="has-sub <?= $activeMenu=='dashboard' ? 'active expand' : '' ?>">
          <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#dashboard">
            <i class="mdi mdi-view-dashboard-outline"></i>
            <span class="nav-text">Dashboard</span> <b class="caret"></b>
          </a>
          <ul class="collapse <?= $activeMenu=='dashboard' ? 'show' : '' ?>" id="dashboard" data-parent="#sidebar-menu">
            <div class="sub-menu">
              <li class="<?= $activePage=='index' ? 'active' : '' ?>">
                <a class="sidenav-item-link" href="index.php">
                  <span class="nav-text">Tableau de bord</span>
                </a>
              </li>
            </div>
          </ul>
        </li>

        <!-- Offres -->
        <li class="has-sub <?= $activeMenu=='offers' ? 'active expand' : '' ?>">
          <a class="sidenav-item-link" data-toggle="collapse" data-target="#offers">
            <i class="mdi mdi-percent"></i>
            <span class="nav-text">Offres</span> <b class="caret"></b>
          </a>
          <ul class="collapse <?= $activeMenu=='offers' ? 'show' : '' ?>" id="offers">
            <div class="sub-menu">
              <li class="<?= $activePage=='offers' ? 'active' : '' ?>"><a href="offers.php">Gestion des offres</a></li>
              <li class="<?= $activePage=='offers-stats' ? 'active' : '' ?>"><a href="offers-stats.php">Statistiques offres</a></li>
            </div>
          </ul>
        </li>

        <!-- Produits -->
        <li class="has-sub <?= $activeMenu=='products' ? 'active expand' : '' ?>">
          <a class="sidenav-item-link" data-toggle="collapse" data-target="#products">
            <i class="mdi mdi-package-variant"></i>
            <span class="nav-text">Produits</span> <b class="caret"></b>
          </a>
          <ul class="collapse <?= $activeMenu=='products' ? 'show' : '' ?>" id="products">
            <div class="sub-menu">
              <li class="<?= $activePage=='products' ? 'active' : '' ?>"><a href="products.php">Liste des produits</a></li>
              <li class="<?= $activePage=='categories' ? 'active' : '' ?>"><a href="categories.php">Catégories</a></li>
            </div>
          </ul>
        </li>
                <!-- categories-->

<li class="has-sub <?= $activeMenu=='categories' ? 'active expand' : '' ?>">
  <a class="sidenav-item-link" data-toggle="collapse" data-target="#categories">
    <i class="mdi mdi-format-list-bulleted"></i>
    <span class="nav-text">Catégories</span> <b class="caret"></b>
  </a>
  <ul class="collapse <?= $activeMenu=='categories' ? 'show' : '' ?>" id="categories">
    <div class="sub-menu">
      <li class="<?= $activePage=='listecategorie' ? 'active' : '' ?>">
        <a href="listecategorie.php">Liste des catégories</a>
      </li>
    </div>
  </ul>
</li>



        <!-- Clients -->
        <li class="has-sub <?= $activeMenu=='clients' ? 'active expand' : '' ?>">
          <a class="sidenav-item-link" data-toggle="collapse" data-target="#clients">
            <i class="mdi mdi-account-multiple"></i>
            <span class="nav-text">Clients</span> <b class="caret"></b>
          </a>
          <ul class="collapse <?= $activeMenu=='clients' ? 'show' : '' ?>" id="clients">
            <div class="sub-menu">
              <li class="<?= $activePage=='clients' ? 'active' : '' ?>"><a href="clients.php">Liste des clients</a></li>
              <li class="<?= $activePage=='clients-stats' ? 'active' : '' ?>"><a href="clients-stats.php">Statistiques clients</a></li>
            </div>
          </ul>
        </li>

      </ul>
    </div>

    <div class="sidebar-footer">
      <hr />
      <div class="sidebar-footer-content">
        <h6 class="text-uppercase">CPU Usage <span class="float-right">40%</span></h6>
        <div class="progress progress-xs"><div class="progress-bar" style="width:40%;"></div></div>
        <h6 class="text-uppercase mt-2">Memory Usage <span class="float-right">65%</span></h6>
        <div class="progress progress-xs"><div class="progress-bar progress-bar-warning" style="width:65%;"></div></div>
      </div>
    </div>
  </div>
</aside>
