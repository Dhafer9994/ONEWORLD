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

    $cc->updateCategorie($id, $nom, $description);
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
          <form method="POST" onsubmit="return validateForm();">

            <div class="form-group">
              <label>Name:</label>
              <input type="text" id="nom" name="nom" class="form-control" 
                     value="<?= htmlspecialchars($categorie['nom']) ?>">
            </div>

            <div class="form-group">
              <label>Description:</label>
              <textarea id="description" name="description" class="form-control"><?= htmlspecialchars($categorie['description']) ?></textarea>
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
    const nom = document.getElementById('nom').value;
    const description = document.getElementById('description').value;

    if (!nom) { alert('Le nom est requis !'); return false; }
    if (!description) { alert('La description est requise !'); return false; }

    return true;
}
</script>
