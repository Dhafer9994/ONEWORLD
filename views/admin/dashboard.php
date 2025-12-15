<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin - One World Formations</title>

    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500|Poppins:400,500,600,700|Roboto:400,500"
        rel="stylesheet" />
    <link href="https://cdn.materialdesignicons.com/4.4.95/css/materialdesignicons.min.css" rel="stylesheet" />
    <link href="<?= ASSETS_ADMIN_URL ?>/plugins/simplebar/simplebar.css" rel="stylesheet" />
    <link href="<?= ASSETS_ADMIN_URL ?>/plugins/nprogress/nprogress.css" rel="stylesheet" />
    <link id="sleek-css" rel="stylesheet" href="<?= ASSETS_ADMIN_URL ?>/css/sleek.css" />
    <link href="<?= ASSETS_ADMIN_URL ?>/img/favicon.png" rel="shortcut icon" />
    <script src="<?= ASSETS_ADMIN_URL ?>/plugins/nprogress/nprogress.js"></script>
    <style>
        .form-section-active {
            display: block !important;
        }
    </style>
</head>

<body class="header-fixed sidebar-fixed sidebar-dark header-light" id="body">
    <script>NProgress.configure({ showSpinner: false }); NProgress.start();</script>

    <div class="wrapper">
        <aside class="left-sidebar bg-sidebar">
            <div id="sidebar" class="sidebar sidebar-with-footer">
                <div class="app-brand">
                    <a href="index.php?route=admin" title="One World Admin">
                        <span class="brand-name text-truncate">One World Admin</span>
                    </a>
                </div>
                <div class="" data-simplebar style="height: 100%;">
                    <ul class="nav sidebar-inner" id="sidebar-menu">
                        <li class="<?= (!isset($_GET['section']) || $_GET['section'] == 'inscriptions') ? 'active' : '' ?>"
                            id="menu-inscriptions">
                            <a class="sidenav-item-link" href="index.php?route=admin&section=inscriptions">
                                <i class="mdi mdi-account-group"></i><span class="nav-text">Inscriptions</span>
                            </a>
                        </li>
                        <li class="<?= (isset($_GET['section']) && $_GET['section'] == 'formations') ? 'active' : '' ?>"
                            id="menu-formations">
                            <a class="sidenav-item-link" href="index.php?route=admin&section=formations">
                                <i class="mdi mdi-school"></i><span class="nav-text">Formations</span>
                            </a>
                        </li>
                        <li><a class="sidenav-item-link" href="index.php"><i class="mdi mdi-home"></i><span
                                    class="nav-text">Retour au Site</span></a></li>
                    </ul>
                </div>
            </div>
        </aside>

        <div class="page-wrapper">
            <header class="main-header " id="header">
                <nav class="navbar navbar-static-top navbar-expand-lg">
                    <button id="sidebar-toggler" class="sidebar-toggle"><span class="sr-only">Toggle
                            navigation</span></button>
                    <div class="navbar-right ">
                        <ul class="nav navbar-nav">
                            <li class="user-menu"><button class="dropdown-toggle nav-link" data-toggle="dropdown"><span
                                        class="d-none d-lg-inline-block">Administrateur</span></button></li>
                        </ul>
                    </div>
                </nav>
            </header>

            <div class="content-wrapper">
                <div class="content">

                    <?php if (isset($success)): ?>
                        <div class="alert alert-success"><?= $success ?></div>
                    <?php endif; ?>
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <!-- SECTION: INSCRIPTIONS -->
                    <div id="section-inscriptions" class="row"
                        style="display: <?= (!isset($_GET['section']) || $_GET['section'] == 'inscriptions') ? 'flex' : 'none' ?>;">
                        <div class="col-12">
                            <div class="card card-default">
                                <div
                                    class="card-header card-header-border-bottom d-flex justify-content-between align-items-center">
                                    <h2>Inscriptions Réfugiés</h2>
                                </div>
                                <div class="card-body">
                                    <div class="basic-data-table">
                                        <table class="table table-hover table-bordered" style="width:100%">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>Refugie</th>
                                                    <th>Pays</th>
                                                    <th>Contact</th>
                                                    <th>Formation</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($inscriptions as $i): ?>
                                                    <tr>
                                                        <td><strong><?= htmlspecialchars($i['refugie_nom']) ?></strong></td>
                                                        <td><?= htmlspecialchars($i['refugie_pays']) ?></td>
                                                        <td><?= htmlspecialchars($i['refugie_contact']) ?></td>
                                                        <td><?= htmlspecialchars($i['formation_titre']) ?></td>
                                                        <td><?= htmlspecialchars($i['date_inscription']) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                <?php if (empty($inscriptions)): ?>
                                                    <tr>
                                                        <td colspan="5" class="text-center">Aucune inscription (Base de
                                                            données)</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION: FORMATIONS -->
                    <div id="section-formations" class="row"
                        style="display: <?= (isset($_GET['section']) && $_GET['section'] == 'formations') ? 'flex' : 'none' ?>;">
                        <div class="col-12">
                            <div class="card card-default">
                                <div
                                    class="card-header card-header-border-bottom d-flex justify-content-between align-items-center">
                                    <h2>Gestion des Formations</h2>
                                    <div>
                                        <form action="index.php" method="GET" style="display:inline-flex;">
                                            <input type="hidden" name="route" value="admin">
                                            <input type="hidden" name="section" value="formations">
                                            <input type="text" name="q" class="form-control" placeholder="Rechercher..."
                                                value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                                            <button type="submit" class="btn btn-sm btn-light border"><i
                                                    class="mdi mdi-magnify"></i></button>
                                        </form>
                                        <button onclick="$('#formationModal').modal('show')"
                                            class="btn btn-primary btn-sm ml-2">Ajouter Formation</button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="basic-data-table">
                                        <table class="table table-hover table-bordered" style="width:100%">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Titre</th>
                                                    <th>Durée</th>
                                                    <th>Niveau</th>
                                                    <th>Date Début</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($formations as $f): ?>
                                                    <tr>
                                                        <td><strong><?= htmlspecialchars($f->getTitre()) ?></strong></td>
                                                        <td><?= htmlspecialchars($f->getDuree()) ?></td>
                                                        <td><span
                                                                class="badge badge-info"><?= htmlspecialchars($f->getNiveau()) ?></span>
                                                        </td>
                                                        <td><?= htmlspecialchars($f->getDateDebut()) ?></td>
                                                        <td>
                                                            <a href="index.php?route=admin&section=formations&action=edit&id=<?= $f->getId() ?>"
                                                                class="btn btn-sm btn-outline-primary">Modifier</a>
                                                            <a href="index.php?route=admin&section=formations&action=delete&id=<?= $f->getId() ?>"
                                                                class="btn btn-sm btn-outline-danger"
                                                                onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- MODAL FORMATION (PHP FORM) -->
    <div class="modal fade <?= ($formationToEdit || (isset($_GET['action']) && $_GET['action'] == 'edit')) ? 'show' : '' ?>"
        id="formationModal" tabindex="-1" role="dialog"
        style="<?= ($formationToEdit || (isset($_GET['action']) && $_GET['action'] == 'edit')) ? 'display:block; padding-right: 17px;' : '' ?>"
        aria-hidden="<?= ($formationToEdit) ? 'false' : 'true' ?>">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?= $formationToEdit ? 'Modifier' : 'Ajouter' ?> une Formation</h5>
                    <button type="button" class="close" data-dismiss="modal"
                        onclick="window.location.href='index.php?route=admin&section=formations'">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="index.php?route=admin&section=formations&action=save" method="POST">
                        <input type="hidden" name="id" value="<?= $formationToEdit ? $formationToEdit->getId() : '' ?>">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Titre</label>
                                <input type="text" class="form-control" name="titre"
                                    value="<?= $formationToEdit ? htmlspecialchars($formationToEdit->getTitre()) : '' ?>"
                                    required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Durée</label>
                                <input type="text" class="form-control" name="duree"
                                    value="<?= $formationToEdit ? htmlspecialchars($formationToEdit->getDuree()) : '' ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" name="description"
                                rows="3"><?= $formationToEdit ? htmlspecialchars($formationToEdit->getDescription()) : '' ?></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Niveau</label>
                                <select class="form-control" name="niveau">
                                    <option value="débutant" <?= ($formationToEdit && $formationToEdit->getNiveau() == 'débutant') ? 'selected' : '' ?>>Débutant
                                    </option>
                                    <option value="intermédiaire" <?= ($formationToEdit && $formationToEdit->getNiveau() == 'intermédiaire') ? 'selected' : '' ?>>
                                        Intermédiaire</option>
                                    <option value="avancé" <?= ($formationToEdit && $formationToEdit->getNiveau() == 'avancé') ? 'selected' : '' ?>>Avancé</option>
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Prix</label>
                                <input type="number" class="form-control" name="prix"
                                    value="<?= $formationToEdit ? htmlspecialchars($formationToEdit->getPrix()) : '0' ?>">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Places Max</label>
                                <input type="number" class="form-control" name="places_max"
                                    value="<?= $formationToEdit ? htmlspecialchars($formationToEdit->getPlacesMax()) : '20' ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Date Début</label>
                                <input type="date" class="form-control" name="date_debut"
                                    value="<?= $formationToEdit ? htmlspecialchars($formationToEdit->getDateDebut()) : '' ?>"
                                    required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Date Fin</label>
                                <input type="date" class="form-control" name="date_fin"
                                    value="<?= $formationToEdit ? htmlspecialchars($formationToEdit->getDateFin()) : '' ?>">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="index.php?route=admin&section=formations" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php if ($formationToEdit): ?>
        <div class="modal-backdrop fade show"></div>
    <?php endif; ?>

    <script src="<?= ASSETS_ADMIN_URL ?>/plugins/jquery/jquery.min.js"></script>
    <script src="<?= ASSETS_ADMIN_URL ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= ASSETS_ADMIN_URL ?>/plugins/simplebar/simplebar.min.js"></script>
    <script src="<?= ASSETS_ADMIN_URL ?>/js/sleek.js"></script>
</body>

</html>