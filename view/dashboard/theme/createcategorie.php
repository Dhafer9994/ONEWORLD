<?php
include '../../../controller/categorieC.php';
include '../../../model/categorie.php';

$categoriec = new categorieC();
$activeMenu = 'categories';   // pour ouvrir le menu Catégories
$activePage = 'listecategorie';   // pour marquer la page active

if (isset($_POST['add'])) {
  // Handle Image Upload
  $image = null;
  if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $uploadDir = '../../frontoffice/img/';
    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0777, true);
    }
    $image = time() . '_' . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $image);
  }

  $categorie = new categorie(
    $_POST['name'],
    $_POST['description'],
    $image
  );

  $categoriec->addcategorie($categorie);

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
          <h2>Create a Category</h2>
        </div>

        <div class="card-body">
          <form method="POST" enctype="multipart/form-data" onsubmit="return validateCategoryForm();">

            <div class="form-group">
              <label>Name:</label>
              <input type="text" id="name" name="name" class="form-control">
              <small id="err_name" class="text-danger"></small>
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

            <button type="submit" name="add" class="btn btn-primary">Create Category</button>
          </form>
        </div>

      </div>

    </div>
  </div>
</div>
<script>
  function validateCategoryForm() {

    // Clear previous errors
    document.getElementById("err_name").innerHTML = "";
    document.getElementById("err_description").innerHTML = "";

    let valid = true;

    const name = document.getElementById("name").value;
    const description = document.getElementById("description").value;

    if (!name) {
      document.getElementById("err_name").innerHTML = "Name cannot be empty.";
      valid = false;
    }

    if (!description) {
      document.getElementById("err_description").innerHTML = "Description cannot be empty.";
      valid = false;
    }

    return valid; // false blocks form submission
  }
</script>