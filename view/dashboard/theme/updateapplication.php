<?php
include '../../../controller/applicationC.php';
include '../../../controller/offreC.php';
include '../../../controller/categorieC.php';
include '../../../model/application.php';

$activeMenu = 'applications';
$activePage = 'listeapplication';

$ac = new ApplicationC();
$oc = new Offrec();
$cc = new categorieC();

$id = $_GET['id'] ?? 0;
$application = $ac->getApplicationById($id);

// Get all categories and offers for dropdowns
$categories = $cc->listecategorie();
$offers = $oc->listeoffre();

if (isset($_POST['update'])) {
    $id_offre = $_POST['id_offre'] ?? $application['id_offre'];
    $status = $_POST['status'] ?? $application['status'];

    // Handle CV upload if a new file is provided
    $cvFile = $application['cv']; // keep existing by default
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
        $uploadDir = '../../../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $cvFile = $uploadDir . basename($_FILES['cv']['name']);
        move_uploaded_file($_FILES['cv']['tmp_name'], $cvFile);
    }

    // Create new Application object with updated data
    $updatedApp = new Application(
        (int)$id_offre,
        $status,
        $cvFile,
        (int)$id
    );

    $ac->updateApplication($updatedApp); // you need to add this method
    header("Location: listeapplication.php");
    exit;
}

include 'header.php';
include 'sidebar.php';
?>

<div class="page-wrapper">
  <div class="content-wrapper">
    <div class="content">

      <div class="card card-default">
        <div class="card-header">
          <h2>Update Application</h2>
        </div>
        <div class="card-body">
          <form method="POST" enctype="multipart/form-data">

            <div class="form-group">
              <label>Offer:</label>
              <select name="id_offre" class="form-control" required>
                <option value="">-- Select Offer --</option>
                <?php foreach($offers as $offer): ?>
                    <option value="<?= $offer['id'] ?>" 
                        <?= $application['id_offre']==$offer['id']?'selected':'' ?>>
                        <?= htmlspecialchars($offer['titre']) ?>
                    </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group">
              <label>Status:</label>
              <select name="status" class="form-control" required>
                <option value="Pending" <?= $application['status']=='Pending'?'selected':'' ?>>Pending</option>
                <option value="Accepted" <?= $application['status']=='Accepted'?'selected':'' ?>>Accepted</option>
                <option value="Rejected" <?= $application['status']=='Rejected'?'selected':'' ?>>Rejected</option>
              </select>
            </div>

            <div class="form-group">
              <label>CV (optional):</label>
              <input type="file" name="cv" class="form-control">
              <?php if($application['cv']): ?>
                <small>Current CV: <?= htmlspecialchars(basename($application['cv'])) ?></small>
              <?php endif; ?>
            </div>

            <button type="submit" name="update" class="btn btn-primary">Update Application</button>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>
