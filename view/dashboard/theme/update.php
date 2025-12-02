<?php
include '../../../controller/offreC.php';
include '../../../controller/categorieC.php';

$activeMenu = 'offers';  
$activePage = 'offers';

$oc = new Offrec();
$cc = new categorieC();
$id = $_GET['id'];
$offre = $oc->getOffreById($id); 

// Get all categories dynamically
$categories = $cc->listecategorie();

if (isset($_POST['update'])) {
    $id_categorie = $_POST['categorie'];  // send category ID
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $status = $_POST['status'];
    $auteur = $_POST['auteur'];

    $oc->updateOffre($id, $id_categorie, $titre, $description, $location, $status, $auteur);
    header("Location: offers.php");
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
          <h2>Update Offer</h2>
        </div>
        <div class="card-body">
          <form method="POST" onsubmit="return validateForm();">

            <div class="form-group">
              <label>Title:</label>
              <input type="text" class="form-control" id="titre" name="titre" 
                     value="<?= isset($offre['titre']) ? $offre['titre'] : '' ?>">
              <small id="err_titre" class="text-danger"></small>
            </div>

            <div class="form-group">
              <label>Category:</label>
              <select id="categorie" name="categorie" class="form-control">
                <option value="">--Select Category--</option>
                <?php foreach($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" 
                        <?= isset($offre['id_categorie']) && $offre['id_categorie'] == $cat['id'] ? 'selected' : '' ?>>
                        <?= $cat['nom'] ?>
                    </option>
                <?php endforeach; ?>
              </select>
              <small id="err_categorie" class="text-danger"></small>
            </div>

            <div class="form-group">
              <label>Description:</label>
              <textarea id="description" name="description" class="form-control"><?= isset($offre['description']) ? $offre['description'] : '' ?></textarea>
              <small id="err_description" class="text-danger"></small>
            </div>

            <div class="form-group">
              <label>Location:</label>
              <input type="text" id="location" name="location" class="form-control" 
                     value="<?= isset($offre['location']) ? $offre['location'] : '' ?>">
              <small id="err_location" class="text-danger"></small>
            </div>

            <div class="form-group">
              <label>Status:</label>
              <select id="status" name="status" class="form-control">
                <option value="Active" <?= isset($offre['status']) && $offre['status']=='Active'?'selected':'' ?>>Active</option>
                <option value="Inactive" <?= isset($offre['status']) && $offre['status']=='Inactive'?'selected':'' ?>>Inactive</option>
              </select>
              <small id="err_status" class="text-danger"></small>
            </div>

            <div class="form-group">
              <label>Author:</label>
              <input type="text" id="auteur" name="auteur" class="form-control" 
                     value="<?= isset($offre['auteur']) ? $offre['auteur'] : '' ?>">
              <small id="err_auteur" class="text-danger"></small>
            </div>

            <button type="submit" name="update" class="btn btn-primary">Update</button>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>
