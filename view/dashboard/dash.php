<?php
// index.php – Dashboard principal
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="OneWorld Dashboard - Admin Dashboard Template">
  
    <title>OneWorld - Admin Dashboard</title>
    
    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500|Poppins:400,500,600,700|Roboto:400,500" rel="stylesheet" />
    <link href="https://cdn.materialdesignicons.com/4.4.95/css/materialdesignicons.min.css" rel="stylesheet" />
  
    <!-- PLUGINS CSS STYLE -->
    <link href="../../assets/plugins/simplebar/simplebar.css" rel="stylesheet" />
    <link href="../../assets/plugins/nprogress/nprogress.css" rel="stylesheet" />
  
    <!-- SLEEK CSS -->
    <link id="sleek-css" rel="stylesheet" href="../../assets/css/sleek.css" />
  
    <!-- CUSTOM CSS POUR ONEWORLD -->
    <link rel="stylesheet" href="../../assets/css/users.css">
  
    <!-- FAVICON -->
    <link href="../../assets/img/favicon.png" rel="shortcut icon" />
  
    <script src="../../assets/plugins/nprogress/nprogress.js"></script>
  </head>

  <body class="header-fixed sidebar-fixed sidebar-dark header-light" id="body">
    <script>
      NProgress.configure({ showSpinner: false });
      NProgress.start();
    </script>

    <div id="toaster"></div>

    <div class="wrapper">
      <!-- Sidebar -->
      <?php include 'partials/sidebar.php'; ?>

      <div class="page-wrapper">
        <!-- Header -->
        <?php include 'partials/header.php'; ?>
      

        <div class="content-wrapper">
          <div class="content">
            <!-- Dashboard Cards -->
            <?php include 'partials/dashboard-cards.php'; ?>

            <div class="row">
              <div class="col-12">
                <div class="card card-default">
                  <div class="card-header">
                    <h2>Bienvenue sur OneWorld Dashboard</h2>
                  </div>
                  <div class="card-body">
                    <p>Gérez votre boutique en ligne avec facilité. Accédez aux différentes sections via le menu latéral.</p>
                    <a href="offers.php" class="btn btn-primary">Voir les offres</a>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- Footer -->
        <?php include 'partials/footer.php'; ?>
      </div>
    </div>

    <script src="../../assets/plugins/jquery/jquery.min.js"></script>
    <script src="../../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/plugins/simplebar/simplebar.min.js"></script>
    <script src="../../assets/plugins/charts/Chart.min.js"></script>
    <script src="../../assets/js/chart.js"></script>
    <script src="../../assets/js/sleek.js"></script>
    <script src="../../assets/js/oneworld.js"></script>

  </body>
</html>