<?php
include '../../../controller/categorieC.php';

$activeMenu = 'categories';  // pour ouvrir le menu Catégories
$activePage = 'listecategorie';  // pour marquer la page active

$cc = new categorieC();
$id = $_GET['id'];
$categorie = $cc->getCategorieById($id);  // récupérer la catégorie existante

if (isset($_POST['update'])) {
  $nom = $_POST['nom'];
  $description = $_POST['description'];

  // Handle Image Upload
  $image = isset($categorie['image']) ? $categorie['image'] : null;
  if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    // Use absolute path to avoid ambiguity
    $targetDir = __DIR__ . '/../../frontoffice/img/';



    if (!is_dir($targetDir)) {
      if (!mkdir($targetDir, 0777, true)) {
        // failed to create directory
      }
    }

    $newFileName = time() . '_' . basename($_FILES['image']['name']);
    $targetFile = $targetDir . $newFileName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
      $image = $newFileName;
    } else {
      // upload failed
    }
  }

  $cc->updateCategorie($id, $nom, $description, $image);
  header("Location: listecategorie.php");
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
          <h2>Update Category</h2>
        </div>

        <div class="card-body">
          <form method="POST" enctype="multipart/form-data" onsubmit="return validateForm();">

            <div class="form-group">
              <label>Name:</label>
              <input type="text" id="nom" name="nom" class="form-control"
                value="<?= isset($categorie['nom']) ? $categorie['nom'] : '' ?>">
              <small id="err_nom" class="text-danger"></small>
            </div>

            <div class="form-group">
              <label>Description:</label>
              <textarea id="description" name="description"
                class="form-control"><?= isset($categorie['description']) ? $categorie['description'] : '' ?></textarea>
              <small id="err_description" class="text-danger"></small>
            </div>

            <div class="form-group">
              <label>Image (Optional):</label>
              <input type="file" name="image" class="form-control">
              <?php if (!empty($categorie['image'])): ?>
                <small>Current Image: <?= htmlspecialchars($categorie['image']) ?></small>
              <?php endif; ?>
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

    // Clear previous errors
    document.querySelectorAll(".text-danger").forEach(el => el.innerHTML = "");

    const nom = document.getElementById('nom').value;
    const description = document.getElementById('description').value;

    if (!nom) {
      document.getElementById('err_nom').innerHTML = "Name is required.";
      valid = false;
    }
    if (!description) {
      document.getElementById('err_description').innerHTML = "Description is required.";
      valid = false;
    }

    return valid;
  }
</script>