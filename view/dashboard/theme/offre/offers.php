<?php
include '../../../controller/offreC.php';

$oc = new Offrec();

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $oc->deleteOffre($id);
    header("Location: offers.php");
    exit;
}

// Récupération des offres avec id et nom de catégorie
$liste = $oc->listeOffre();
$activeMenu = 'offers'; // Highlight "Offers" menu
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Offers Management - OneWorld</title>
    <?php include 'header.php'; ?>
</head>
<body class="header-fixed sidebar-fixed sidebar-dark header-light">

<div class="wrapper">

    <?php include 'sidebar.php'; ?>

    <div class="page-wrapper">

        <div class="content-wrapper">
            <div class="content">

                <div class="card card-default">
                    <div class="card-header d-flex justify-content-between">
                        <h2>Offers Management</h2>
                        <a href="create.php" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> New Offer
                        </a>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Category ID</th>
                                    <th>Category </th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Author</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($liste as $offre) { ?>
                                <tr>
                                    <td><?= $offre['id'] ?></td>
                                    <td><?= $offre['id_categorie'] ?></td> 
                                    <td><?= $offre['categorie'] ?></td>  
                                    <td><?= $offre['titre'] ?></td>
                                    <td><?= $offre['description'] ?></td>
                                    <td><?= $offre['location'] ?></td>
                                    <td>
                                        <span style="padding:5px 10px; border-radius:5px; color:white; 
                                              background-color: <?= $offre['status']=='Active'?'green':'red' ?>;">
                                              <?= $offre['status'] ?>
                                        </span>
                                    </td>
                                    <td><?= $offre['auteur'] ?></td>
                                    <td>
                                        <a href="update.php?id=<?= $offre['id'] ?>" class="btn btn-sm btn-warning">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger deleteBtn" data-id="<?= $offre['id'] ?>">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Offer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this offer?
      </div>
      <div class="modal-footer">
        <a href="#" class="btn btn-danger" id="confirmDeleteBtn">Delete</a>
        <button type="button" class="btn btn-secondary" id="cancelDeleteBtn">Cancel</button>
      </div>
    </div>
  </div>
</div>

<script src="assets/plugins/jquery/jquery.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/plugins/simplebar/simplebar.min.js"></script>
<script src="assets/js/sleek.js"></script>
<script src="assets/js/oneworld.js"></script>

<script>
$(document).ready(function() {
    var deleteModalEl = document.getElementById('deleteModal');
    var deleteModal = new bootstrap.Modal(deleteModalEl);

    $('.deleteBtn').on('click', function() {
        var offreId = $(this).data('id');
        $('#confirmDeleteBtn').attr('href', 'offers.php?delete=' + offreId);
        deleteModal.show();
    });

    $('#cancelDeleteBtn').on('click', function() {
        deleteModal.hide();
    });
});
</script>

</body>
</html>
