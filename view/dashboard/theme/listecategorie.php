<?php
include __DIR__ . '/../../../controller/categorieC.php';

$categoriec = new categorieC();

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $categoriec->deletecategorie($id);
    header("Location: listecategorie.php");
    exit;
}

$liste = $categoriec->listecategorie();
$activeMenu = 'categories';    // Pour surligner le menu "Catégories"
$activePage = 'listecategorie';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Gestion des Catégories - OneWorld</title>
<?php include "header.php"; ?>
</head>
<body class="header-fixed sidebar-fixed sidebar-dark header-light">

<div class="wrapper">

    <!-- SIDEBAR -->
    <?php include "sidebar.php"; ?>

    <!-- PAGE CONTENT -->
    <div class="page-wrapper">

        <div class="content-wrapper">
            <div class="content">

                <div class="card card-default">
                    <div class="card-header d-flex justify-content-between">
                        <h2>Gestion des Catégories</h2>
                        <a href="createCategorie.php" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Nouvelle Catégorie
                        </a>
                    </div>

                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($liste as $cat) { ?>
                                <tr>
                                    <td><?= $cat['id'] ?></td>
                                    <td><?= $cat['nom'] ?></td>
                                    <td><?= $cat['description'] ?></td>
                                    <td>
                                        <a href="updateCategorie.php?id=<?= $cat['id'] ?>" class="btn btn-sm btn-warning">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        <a href="listecategorie.php?delete=<?= $cat['id'] ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Supprimer cette catégorie ?')">
                                           <i class="mdi mdi-delete"></i>
                                        </a>
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

<script src="assets/plugins/jquery/jquery.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/plugins/simplebar/simplebar.min.js"></script>
<script src="assets/js/sleek.js"></script>
<script src="assets/js/oneworld.js"></script>

</body>
</html>
