<?php
// dashboard-cards.php - Cartes du dashboard
?>
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