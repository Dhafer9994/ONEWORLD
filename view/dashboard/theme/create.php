<?php
include '../../../controller/offreC.php';
include '../../../model/offre.php';
include '../../../controller/categorieC.php';

$activeMenu = 'offers';
$activePage = 'offers';

$oc = new Offrec();
$categoriec = new categorieC();
$listeCategories = $categoriec->listecategorie(); // récupère les catégories depuis la DB

if (isset($_POST['add'])) {
  // Handle Image Upload
  $image = null;
  if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $uploadDir = '../../../view/frontoffice/img/';
    $image = time() . '_' . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $image);
  }

  // créer un objet offre avec id_categorie
  $offre = new offre(
    $_POST['titre'],
    $_POST['categorie'], // ici c'est id_categorie
    $_POST['description'],
    $_POST['location'],
    $_POST['status'],
    $_POST['auteur'],
    $image
  );

  $oc->addOffre($offre);
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
          <h2>Create Offer</h2>
        </div>
        <div class="card-body">
          <form method="POST" enctype="multipart/form-data" onsubmit="return validateCreateForm();">

            <!-- Sélection dynamique des catégories -->
            <div class="form-group">
              <label>Category:</label>
              <select name="categorie" id="categorie" class="form-control">
                <option value="">-- Select Category --</option>
                <?php
                if (!empty($listeCategories)) {
                  foreach ($listeCategories as $cat) {
                    echo '<option value="' . $cat['id'] . '">' . $cat['nom'] . '</option>';
                  }
                } else {
                  echo '<option value="">No categories found</option>';
                }
                ?>
              </select>
              <small id="err_categorie" class="text-danger"></small>
            </div>

            <div class="form-group">
              <label>Title:</label>
              <input type="text" id="titre" name="titre" class="form-control">
              <small id="err_titre" class="text-danger"></small>
            </div>

            <div class="form-group">
              <label>Description:</label>
              <textarea id="description" name="description" class="form-control"></textarea>
              <small id="err_description" class="text-danger"></small>
            </div>

            <div class="form-group">
              <label>Image (Optional):</label>
              <input type="file" name="image" class="form-control">
            </div>

            <div class="form-group">
              <label>Location:</label>
              <input type="text" id="location" name="location" class="form-control">
              <small id="err_location" class="text-danger"></small>
            </div>

            <div class="form-group">
              <label>Status:</label>
              <select id="status" name="status" class="form-control">
                <option value="Active">Active</option>
              </select>
              <small id="err_status" class="text-danger"></small>
            </div>

            <div class="form-group">
              <label>Author:</label>
              <input type="text" id="auteur" name="auteur" class="form-control">
              <small id="err_auteur" class="text-danger"></small>
            </div>

            <button type="submit" name="add" class="btn btn-primary">Create Offer</button>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function validateCreateForm() {
    document.querySelectorAll(".text-danger").forEach(el => el.innerHTML = "");
    let valid = true;

    const categorie = document.getElementById("categorie").value;
    const titre = document.getElementById("titre").value;
    const description = document.getElementById("description").value;
    const location = document.getElementById("location").value;
    const status = document.getElementById("status").value;
    const auteur = document.getElementById("auteur").value;

    if (!categorie) {
      document.getElementById("err_categorie").innerHTML = "Please choose a category.";
      valid = false;
    }
    if (titre === "") {
      document.getElementById("err_titre").innerHTML = "Title cannot be empty.";
      valid = false;
    }
    if (description === "") {
      document.getElementById("err_description").innerHTML = "Description cannot be empty.";
      valid = false;
    }
    if (location === "") {
      document.getElementById("err_location").innerHTML = "Location cannot be empty.";
      valid = false;
    }
    if (!status) {
      document.getElementById("err_status").innerHTML = "Please choose a status.";
      valid = false;
    }
    if (auteur === "") {
      document.getElementById("err_auteur").innerHTML = "Author cannot be empty.";
      valid = false;
    }
    return valid;
  }
</script>