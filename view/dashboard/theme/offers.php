<?php
include '../../../controller/offreC.php';

$oc = new Offrec();

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $oc->deleteOffre($id);
    header("Location: offers.php");
    exit;
}

$liste = $oc->listeOffre();
$activeMenu = 'offers'; // pour surligner la section Offres dans le sidebar
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Offres - OneWorld</title>
    <?php include 'header.php'; ?>
</head>
<body class="header-fixed sidebar-fixed sidebar-dark header-light">

<div class="wrapper">

    <!-- SIDEBAR -->
    <?php include 'sidebar.php'; ?>

    <!-- PAGE CONTENT -->
    <div class="page-wrapper">

        <div class="content-wrapper">
            <div class="content">

                <div class="card card-default">
                    <div class="card-header d-flex justify-content-between">
                        <h2>Gestion des Offres</h2>
                        <a href="create.php" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Nouvelle Offre
                        </a>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Catégorie</th>
                                    <th>Titre</th>
                                    <th>Description</th>
                                    <th>Localisation</th>
                                    <th>Statut</th>
                                    <th>Auteur</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($liste as $offre) { ?>
                                <tr>
                                    <td><?= $offre['id'] ?></td>
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
                                        <a href="offers.php?delete=<?= $offre['id'] ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Supprimer cette offre ?')">
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
