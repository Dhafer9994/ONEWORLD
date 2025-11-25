<?php
$activeMenu = 'dashboard'; // pour surligner le menu "Dashboard"
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - OneWorld</title>
    <?php include 'header.php'; ?>
</head>
<body class="header-fixed sidebar-fixed sidebar-dark header-light">

<div class="wrapper">

    <!-- SIDEBAR -->
    <?php include 'sidebar.php'; ?>

    <!-- PAGE CONTENT -->
    <div class="page-wrapper">

        <div class="content-wrapper">
            <div class="content">

                <div class="row">

                    <!-- Carte Visiteurs -->
                    <div class="col-xl-3 col-sm-6 mb-4">
                        <div class="card shadow-sm border-0 rounded-lg">
                            <div class="card-body text-center">
                                <h5 class="text-muted">Visiteurs aujourd'hui</h5>
                                <h2 class="font-weight-bold mb-3">5,248</h2>
                                <div class="chartjs-wrapper" style="height: 80px;">
                                    <canvas id="barChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Carte Commandes -->
                    <div class="col-xl-3 col-sm-6 mb-4">
                        <div class="card shadow-sm border-0 rounded-lg">
                            <div class="card-body text-center">
                                <h5 class="text-muted">Commandes aujourd'hui</h5>
                                <h2 class="font-weight-bold mb-3">1,287</h2>
                                <div class="chartjs-wrapper" style="height: 80px;">
                                    <canvas id="dual-line"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Carte Revenu -->
                    <div class="col-xl-3 col-sm-6 mb-4">
                        <div class="card shadow-sm border-0 rounded-lg">
                            <div class="card-body text-center">
                                <h5 class="text-muted">Revenu du jour</h5>
                                <h2 class="font-weight-bold mb-3">€45,890</h2>
                                <div class="chartjs-wrapper" style="height: 80px;">
                                    <canvas id="area-chart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Carte Offres actives -->
                    <div class="col-xl-3 col-sm-6 mb-4">
                        <div class="card shadow-sm border-0 rounded-lg">
                            <div class="card-body text-center">
                                <h5 class="text-muted">Offres actives</h5>
                                <h2 class="font-weight-bold mb-3">15</h2>
                                <div class="chartjs-wrapper" style="height: 80px;">
                                    <canvas id="line"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

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

    </div>
</div>

<script src="assets/plugins/jquery/jquery.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/plugins/simplebar/simplebar.min.js"></script>
<script src="assets/plugins/charts/Chart.min.js"></script>
<script src="assets/js/chart.js"></script>
<script src="assets/js/sleek.js"></script>
<script src="assets/js/oneworld.js"></script>

</body>
</html>
