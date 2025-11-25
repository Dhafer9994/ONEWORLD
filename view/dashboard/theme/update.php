<?php
include '../../../controller/offreC.php';
$activeMenu = 'offers';  // Menu ouvert
$activePage = 'offers';  // Page active


$oc = new Offrec();
$id = $_GET['id'];
$offre = $oc->getOffreById($id); 

if (isset($_POST['update'])) {
    $categorie = $_POST['categorie'];
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $status = $_POST['status'];
    $auteur = $_POST['auteur'];

    $oc->updateOffre($id, $categorie, $titre, $description, $location, $status, $auteur);
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
                <option value="Employment" <?= isset($offre['categorie']) && $offre['categorie']=='Employment'?'selected':'' ?>>Employment</option>
                <option value="Accommodation" <?= isset($offre['categorie']) && $offre['categorie']=='Accommodation'?'selected':'' ?>>Accommodation</option>
                <option value="Education" <?= isset($offre['categorie']) && $offre['categorie']=='Education'?'selected':'' ?>>Education</option>
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

<script>
function validateForm() {
    let valid = true;

    document.querySelectorAll(".text-danger").forEach(el => el.innerHTML = "");

    const titre = document.getElementById('titre').value;
    const categorie = document.getElementById('categorie').value;
    const description = document.getElementById('description').value;
    const location = document.getElementById('location').value;
    const status = document.getElementById('status').value;
    const auteur = document.getElementById('auteur').value;

    if (!titre) {
        document.getElementById('err_titre').innerHTML = "Title is required.";
        valid = false;
    }
    if (!categorie) {
        document.getElementById('err_categorie').innerHTML = "Category is required.";
        valid = false;
    }
    if (!description) {
        document.getElementById('err_description').innerHTML = "Description is required.";
        valid = false;
    }
    if (!location) {
        document.getElementById('err_location').innerHTML = "Location is required.";
        valid = false;
    }
    if (!status) {
        document.getElementById('err_status').innerHTML = "Status is required.";
        valid = false;
    }
    if (!auteur) {
        document.getElementById('err_auteur').innerHTML = "Author is required.";
        valid = false;
    }

    return valid;
}
</script>
