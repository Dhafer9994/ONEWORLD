<?php
include '../../../controller/applicationC.php';
include '../../../controller/offreC.php';
include '../../../controller/categorieC.php';
include '../../../model/application.php';

$ac = new ApplicationC();
$oc = new Offrec();
$cc = new categorieC();

$activeMenu = 'applications';
$activePage = 'listeapplication';

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $ac->deleteApplication($id);
    header("Location: listeapplication.php");
    exit;
}

$liste = $ac->listApplications();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Application Management - OneWorld</title>
<?php include "header.php"; ?>
</head>
<body class="header-fixed sidebar-fixed sidebar-dark header-light">

<div class="wrapper">

    <?php include "sidebar.php"; ?>

    <div class="page-wrapper">
        <div class="content-wrapper">
            <div class="content">

                <div class="card card-default">
                    <div class="card-header d-flex justify-content-between">
                        <h2>Application Management</h2>
                        <a href="createapplication.php" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> New Application
                        </a>
                    </div>

                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Offer</th>
                                    <th>Category</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($liste && count($liste) > 0): ?>
                                    <?php foreach ($liste as $app):
                                        // Récupérer l'offre
                                        $offer = $oc->getOffreById($app['id_offre']);
                                        $offerTitle = $offer ? $offer['titre'] : 'Unknown Offer';

                                        // Récupérer la catégorie
                                        $categoryName = 'Unknown Category';
                                        if ($offer && isset($offer['id_categorie'])) {
                                            $category = $cc->getCategorieById($offer['id_categorie']);
                                            $categoryName = $category ? $category['nom'] : 'Unknown Category';
                                        }

                                        // Déterminer le texte du statut
                                        $statusValue = $app['status'];
                                        $statusText = is_numeric($statusValue) ? 
                                            [0=>'Pending',1=>'Accepted',2=>'Rejected'][$statusValue] ?? 'Pending'
                                            : $statusValue;
                                    ?>
                                    <tr>
                                        <td><?= $app['id'] ?></td>
                                        <td>Dhafer (ID: 999)</td>
                                        <td><?= htmlspecialchars($offerTitle) ?></td>
                                        <td><?= htmlspecialchars($categoryName) ?></td>
                                        <td><?= $app['date_postulation'] ?></td>
                                        <td>
                                            <span class="badge <?= $statusText=='Accepted'?'bg-success':($statusText=='Rejected'?'bg-danger':'bg-warning') ?>">
                                                <?= htmlspecialchars($statusText) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="updateapplication.php?id=<?= $app['id'] ?>" class="btn btn-sm btn-warning">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <button class="btn btn-sm btn-danger deleteBtn" data-id="<?= $app['id'] ?>">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="7">No applications found</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Delete -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Application</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this application?
      </div>
      <div class="modal-footer">
        <a href="#" class="btn btn-danger" id="confirmDeleteBtn">Delete</a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>



<script src="assets/plugins/jquery/jquery.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    var deleteModalEl = document.getElementById('deleteModal');
    var deleteModal = new bootstrap.Modal(deleteModalEl);

    $('.deleteBtn').on('click', function() {
        var appId = $(this).data('id');
        $('#confirmDeleteBtn').attr('href', 'listeapplication.php?delete=' + appId);
        deleteModal.show();
    });

    // Optional: reset href when modal is hidden
    deleteModalEl.addEventListener('hidden.bs.modal', function () {
        $('#confirmDeleteBtn').attr('href', '#');
    });
});
</script>


</body>
</html>
