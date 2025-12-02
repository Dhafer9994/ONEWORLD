<?php
include '../../controller/applicationC.php';
include '../../controller/offreC.php';
include '../../controller/categorieC.php';
include '../../model/application.php';

$applicationC = new ApplicationC();
$offreC = new Offrec();
$categorieC = new categorieC();

$categories = $categorieC->listecategorie();
$listeOffres = [];

$selectedCategory = $_POST['id_categorie'] ?? null;

if ($selectedCategory) {
    $allOffres = $offreC->listeoffre();
    foreach ($allOffres as $offre) {
        if ($offre['id_categorie'] == $selectedCategory) {
            $listeOffres[] = $offre;
        }
    }
}

// Création de l'application
if (isset($_POST['add'])) {
    $id_offre = (int)($_POST['id_offre'] ?? 0);
    $status = $_POST['status'] ?? 'Pending';

=    $cvFile = 'default.pdf'; 
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
        $uploadDir = '../../../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $cvFile = $uploadDir . basename($_FILES['cv']['name']);
        move_uploaded_file($_FILES['cv']['tmp_name'], $cvFile);
    }

    $application = new Application($id_offre, $status, $cvFile);
    $applicationC->addApplication($application);
    header("Location: categorielist.php?success=1");
    exit;
}

include 'header.php';
include 'sidebar.php';
?>

<div class="page-wrapper">
  <div class="content-wrapper">
    <div class="content">

      <div class="card card-default">
        <div class="card-header"><h2>Apply to Offer</h2></div>
        <div class="card-body">

          <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success">Application submitted successfully!</div>
          <?php endif; ?>

          <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
              <label>Category:</label>
              <select name="id_categorie" class="form-control" onchange="this.form.submit()">
                <option value="">-- Select Category --</option>
                <?php foreach ($categories as $cat): ?>
                  <option value="<?= $cat['id'] ?>" <?= ($selectedCategory==$cat['id']?'selected':'') ?>>
                    <?= htmlspecialchars($cat['nom']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group">
              <label>Offer:</label>
              <select name="id_offre" class="form-control" required>
                <option value="">-- Select Offer --</option>
                <?php foreach ($listeOffres as $offre): ?>
                  <option value="<?= $offre['id'] ?>"><?= htmlspecialchars($offre['titre']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group">
              <label>Status:</label>
              <select name="status" class="form-control" required>
                <option value="Pending" selected>Pending</option>
                <option value="Accepted">Accepted</option>
                <option value="Rejected">Rejected</option>
              </select>
            </div>

            <div class="form-group">
              <label>CV (optional):</label>
              <input type="file" name="cv" class="form-control">
            </div>

            <button type="submit" name="add" class="btn btn-primary">Apply</button>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>
