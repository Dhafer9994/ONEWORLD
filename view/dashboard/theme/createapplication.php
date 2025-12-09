<?php
include '../../../controller/applicationC.php';
include '../../../controller/offreC.php';
include '../../../controller/categorieC.php';
include '../../../model/application.php';

$activeMenu = 'applications';
$activePage = 'listeapplication';

$ac = new ApplicationC();
$oc = new Offrec();
$cc = new categorieC();

// Get all categories and offers for dropdowns
$offers = $oc->listeoffre();

if (isset($_POST['add'])) {
    $id_offre = $_POST['id_offre'];
    $status = $_POST['status'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Handle CV upload
    $cvFile = ''; // Default empty
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
        $uploadDir = '../../../uploads/';
        if (!is_dir($uploadDir))
            mkdir($uploadDir, 0777, true);

        $fileName = time() . '_' . basename($_FILES['cv']['name']);
        if (move_uploaded_file($_FILES['cv']['tmp_name'], $uploadDir . $fileName)) {
            $cvFile = $fileName; // Store only filename
        }
    }

    // Create new Application object
    $app = new Application(
        (int) $id_offre,
        $status,
        $cvFile,
        $full_name,
        $email,
        $phone
    );

    $ac->addApplication($app);
    header("Location: listeapplication.php");
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
                    <h2>Create Application</h2>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" id="createAppForm" novalidate
                        onsubmit="return validateForm()">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Full Name:</label>
                                    <input type="text" name="full_name" id="full_name" class="form-control">
                                    <small id="err_full_name" class="text-danger"></small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Email:</label>
                            <input type="text" name="email" id="email" class="form-control">
                            <small id="err_email" class="text-danger"></small>
                        </div>

                        <div class="form-group">
                            <label>Phone:</label>
                            <input type="text" name="phone" id="phone" class="form-control">
                            <small id="err_phone" class="text-danger"></small>
                        </div>

                        <div class="form-group">
                            <label>Offer:</label>
                            <select name="id_offre" id="id_offre" class="form-control">
                                <option value="">-- Select Offer --</option>
                                <?php foreach ($offers as $offer): ?>
                                    <option value="<?= $offer['id'] ?>">
                                        <?= htmlspecialchars($offer['titre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small id="err_id_offre" class="text-danger"></small>
                        </div>

                        <div class="form-group">
                            <label>Status:</label>
                            <select name="status" class="form-control">
                                <option value="Pending">Pending</option>
                                <option value="Accepted">Accepted</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>CV (optional):</label>
                            <input type="file" name="cv" id="cv" class="form-control">
                        </div>

                        <button type="submit" name="add" class="btn btn-primary">Create Application</button>
                    </form>

                    <script>
                        function validateForm() {
                            let valid = true;

                            // Clear errors
                            document.getElementById('err_full_name').innerHTML = '';
                            document.getElementById('err_email').innerHTML = '';
                            document.getElementById('err_phone').innerHTML = '';
                            document.getElementById('err_id_offre').innerHTML = '';

                            // Get values
                            const fullName = document.getElementById('full_name').value.trim();
                            const email = document.getElementById('email').value.trim();
                            const phone = document.getElementById('phone').value.trim();
                            const offer = document.getElementById('id_offre').value;

                            // Regex
                            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                            const phoneRegex = /^[0-9]{8,}$/; // At least 8 digits

                            if (fullName === '') {
                                document.getElementById('err_full_name').innerHTML = 'Full Name is required';
                                valid = false;
                            }

                            if (email === '') {
                                document.getElementById('err_email').innerHTML = 'Email is required';
                                valid = false;
                            } else if (!emailRegex.test(email)) {
                                document.getElementById('err_email').innerHTML = 'Invalid email format';
                                valid = false;
                            }

                            if (phone === '') {
                                document.getElementById('err_phone').innerHTML = 'Phone is required';
                                valid = false;
                            } else if (!phoneRegex.test(phone)) {
                                document.getElementById('err_phone').innerHTML = 'Phone must be at least 8 digits';
                                valid = false;
                            }

                            if (offer === '') {
                                document.getElementById('err_id_offre').innerHTML = 'Please select an offer';
                                valid = false;
                            }

                            return valid;
                        }
                    </script>
                </div>
            </div>

        </div>
    </div>
</div>