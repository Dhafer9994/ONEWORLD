<?php
include '../../../controller/categorieC.php';

$cc = new categorieC();

// Handle delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $cc->deleteCategorie($id);
    header("Location: listecategorie.php");
    exit;
}

// Get PDOStatement
$liste = $cc->listecategorie();
$activeMenu = 'categories';

include 'header.php';
include 'sidebar.php';
?>

<div class="page-wrapper">
    <div class="content-wrapper">
        <div class="content">

            <div class="card card-default">
                <div class="card-header d-flex justify-content-between">
                    <h2>Categories Management</h2>
                    <a href="createcategorie.php" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> New Category
                    </a>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <input type="text" id="categorySearchInput" class="form-control"
                            placeholder="Search categories...">
                    </div>
                    <table class="table table-hover" id="categoryTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($liste): ?>
                                <?php foreach ($liste as $cat): ?>
                                    <tr>
                                        <td><?= $cat['id'] ?></td>
                                        <td>
                                            <?php if (!empty($cat['image'])): ?>
                                                <img src="../../../view/frontoffice/img/<?= htmlspecialchars($cat['image']) ?>"
                                                    alt="Img"
                                                    style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                            <?php else: ?>
                                                <span class="badge bg-secondary">No Img</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $cat['nom'] ?></td>
                                        <td><?= $cat['description'] ?></td>
                                        <td>
                                            <a href="updatecategorie.php?id=<?= $cat['id'] ?>" class="btn btn-sm btn-warning">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <button class="btn btn-sm btn-danger deleteBtn" data-id="<?= $cat['id'] ?>">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">No categories found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
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
                <h5 class="modal-title">Delete Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this category?
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-danger" id="confirmDeleteBtn">Delete</a>
                <button type="button" class="btn btn-secondary" id="cancelDeleteBtn"
                    data-bs-dismiss="modal">Cancel</button>
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

<script src="assets/plugins/jquery/jquery.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        // We can rely on Bootstrap's data-bs-dismiss.
        // Manually handling show/hide if needed.
        var deleteModalEl = document.getElementById('deleteModal');
        var deleteModal = new bootstrap.Modal(deleteModalEl);

        $('.deleteBtn').on('click', function () {
            var catId = $(this).data('id');
            $('#confirmDeleteBtn').attr('href', 'listecategorie.php?delete=' + catId);
            deleteModal.show();
        });

        // Search functionality
        $("#categorySearchInput").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#categoryTable tbody tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>