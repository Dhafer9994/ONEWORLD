<?php

?>
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
            <img src="../../images/eya.png" class="user-image" />
            <span class="d-none d-lg-inline-block">eya OneWorld</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-right">
            <li class="dropdown-header">
              <img src="../../images/eya.png" class="img-circle" />
              <div class="d-inline-block">
                eya OneWorld <small>admin@oneworld.com</small>
              </div>
            </li>
            <li><a href="../front-office/profile.php"><i class="mdi mdi-account"></i> Mon Profil</a></li>
            <li class="dropdown-footer"><a href="../../controller/AuthController.php?action=logout"><i class="mdi mdi-logout"></i> Déconnexion</a></li>
          </ul>
        </li>
      </ul>
    </div>

  </nav>
</header>