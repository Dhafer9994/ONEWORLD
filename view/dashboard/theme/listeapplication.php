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

include "header.php";
include "sidebar.php";
?>

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
                    <div class="mb-3">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search applications...">
                    </div>
                    <table class="table table-hover" id="applicationTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Applicant</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Offer</th>
                                <th>Category</th>
                                <th>CV</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($liste && count($liste) > 0): ?>
                                <?php foreach ($liste as $app):
                                    // Data is now fetched via JOIN in ApplicationC
                                    $offerTitle = $app['offre_titre'] ?? 'Unknown Offer';
                                    $categoryName = $app['categorie_nom'] ?? 'N/A';
                                    $statusValue = $app['status'];

                                    // Determine Status Text
                                    $statusText = 'Pending';
                                    if (is_numeric($statusValue)) {
                                        $statusMap = [0 => 'Pending', 1 => 'Accepted', 2 => 'Rejected'];
                                        $statusText = $statusMap[$statusValue] ?? 'Pending';
                                    } else {
                                        $statusText = $statusValue;
                                    }

                                    // Applicant Name
                                    $applicantName = htmlspecialchars($app['full_name'] ?? 'N/A');
                                    $email = $app['email'] ?? 'N/A';
                                    $phone = $app['phone'] ?? 'N/A';

                                    // CV Link
                                    $cvPath = (!empty($app['cv']) && $app['cv'] !== 'default.pdf') ? $app['cv'] : null;
                                    $cvLink = '#';
                                    $cvLabel = 'No CV';
                                    if ($cvPath) {
                                        $cvLink = '../../../uploads/' . $cvPath;
                                        $cvLabel = '<i class="mdi mdi-download"></i> Download';
                                    }
                                    ?>
                                    <tr>
                                        <td><?= $app['id'] ?></td>
                                        <td><?= htmlspecialchars($applicantName) ?></td>
                                        <td><?= htmlspecialchars($email) ?></td>
                                        <td><?= htmlspecialchars($phone) ?></td>
                                        <td><?= htmlspecialchars($offerTitle) ?></td>
                                        <td><?= htmlspecialchars($categoryName) ?></td>
                                        <td>
                                            <?php if ($cvPath): ?>
                                                <a href="<?= htmlspecialchars($cvLink) ?>" target="_blank"
                                                    class="btn btn-sm btn-info">
                                                    <?= $cvLabel ?>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">No CV</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $app['date_postulation'] ?></td>
                                        <td>
                                            <span
                                                class="badge <?= $statusText == 'Accepted' ? 'bg-success' : ($statusText == 'Rejected' ? 'bg-danger' : 'bg-warning') ?>">
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
                                <tr>
                                    <td colspan="10">No applications found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
    // Fix slow loading bar by completing NProgress
    window.onload = function () {
        if (typeof NProgress !== 'undefined') {
            NProgress.done();
        }
    };
</script>

<!-- Modal Delete -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this application?
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-danger" id="confirmDeleteBtn">Delete</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script src="assets/plugins/jquery/jquery.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        $('.deleteBtn').on('click', function (e) {
            e.preventDefault();
            var appId = $(this).data('id');
            $('#confirmDeleteBtn').attr('href', 'listeapplication.php?delete=' + appId);
            $('#deleteModal').modal('show');
        });

        // Search functionality
        $("#searchInput").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#applicationTable tbody tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>