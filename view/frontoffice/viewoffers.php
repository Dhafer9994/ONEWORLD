<?php
include 'header.php';
include '../../controller/offreC.php';
include '../../controller/categorieC.php';
include '../../controller/applicationC.php';
include '../../model/application.php';

$category_id = isset($_GET['category']) ? intval($_GET['category']) : 0;

$offreC = new Offrec();
$categorieC = new categorieC();
$applicationC = new ApplicationC();

$category = $categorieC->getCategorieById($category_id);
$offers = $offreC->getOffresByCategorie($category_id);

// Application Form Logic
$errors = [];
$success_msg = "";

if (isset($_POST['apply_submit'])) {
    $id_offre = isset($_POST['id_offre']) ? (int) $_POST['id_offre'] : 0;
    $status = 'Pending'; // Default status

    if (!$id_offre) {
        $errors[] = "Invalid offer selected.";
    }

    // Handle CV Upload
    $cvFile = ''; // Default to empty string instead of default.pdf
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
        $uploadDir = '../../uploads/';
        if (!is_dir($uploadDir))
            mkdir($uploadDir, 0777, true);

        $fileName = time() . '_' . basename($_FILES['cv']['name']);

        if (move_uploaded_file($_FILES['cv']['tmp_name'], $uploadDir . $fileName)) {
            $cvFile = $fileName; // Store only the filename in DB
        } else {
            $errors[] = "Error uploading CV.";
        }
    }

    // New Fields
    $full_name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';

    if (empty($errors)) {
        // Construct Application with new fields
        $application = new Application(
            $id_offre,
            $status,
            $cvFile,
            $full_name,
            $email,
            $phone
        );

        try {
            $applicationC->addApplication($application);
            $success_msg = "Your application has been submitted successfully!";

            // PHPMailer Integration
            require_once '../../vendor/phpmailer/src/Exception.php';
            require_once '../../vendor/phpmailer/src/PHPMailer.php';
            require_once '../../vendor/phpmailer/src/SMTP.php';

            $mail = new PHPMailer\PHPMailer\PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'dhafersmaoui8@gmail.com'; // Replace with your email
                $mail->Password = 'mtlx qgvd nvgz hxjg';    // Replace with your app password
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Recipients
                $mail->setFrom('noreply@oneworld.com', 'OneWorld Recruiter');
                $mail->addAddress($email, $full_name);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Application Confirmation - OneWorld';
                $mail->Body = "Dear " . htmlspecialchars($full_name) . ",<br><br>" .
                    "Thank you for applying for the position. We have received your application and will review it shortly.<br><br>" .
                    "Best regards,<br>" .
                    "OneWorld HR Team";
                $mail->AltBody = "Dear " . $full_name . ",\n\n" .
                    "Thank you for applying for the position. We have received your application and will review it shortly.\n\n" .
                    "Best regards,\n" .
                    "OneWorld HR Team";

                $mail->send();
            } catch (Exception $e) {
                // Log error but continue
                error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            }

        } catch (Exception $e) {
            $errors[] = "Error submitting application: " . $e->getMessage();
        }
    }
}
?>

<style>
    /* Modern Color Palette & Variables - Green Theme */
    :root {
        --primary-color: #5BB12F;
        --primary-light: #7bc954;
        --secondary-color: #333333;
        --bg-light: #f9f9f9;
        --text-dark: #333333;
        --text-muted: #666666;
        --white: #ffffff;
        --header-height: 80px;
    }

    body {
        background-color: #ffffff;
        color: #444;
        font-family: 'Open Sans', sans-serif;
        padding-top: var(--header-height);
    }

    /* Page Header */
    .page-title-section {
        position: relative;
        padding: 100px 0 80px;
        text-align: center;
        background: #f5f5f5;
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('img/header-bg.jpg');
        background-size: cover;
        background-position: center;
        margin-bottom: 50px;
        color: white;
    }

    .page-title-section.no-img {
        background: linear-gradient(135deg, #333 0%, #5BB12F 100%);
    }

    .page-title {
        font-family: 'Oswald', sans-serif;
        font-size: 48px;
        text-transform: uppercase;
        font-weight: 700;
        color: white;
        margin-bottom: 20px;
        letter-spacing: 2px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .page-subtitle {
        font-size: 18px;
        color: rgba(255, 255, 255, 0.9);
        max-width: 700px;
        font-weight: 300;
        margin: 0 auto 30px;
        line-height: 28px;
    }

    .category-stats-badge {
        display: inline-block;
        background: var(--primary-color);
        color: white;
        padding: 8px 20px;
        border-radius: 30px;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        box-shadow: 0 4px 15px rgba(91, 177, 47, 0.4);
    }

    /* Offer Cards */
    .offer-card {
        background: white;
        border-radius: 3px;
        overflow: hidden;
        position: relative;
        transition: all 0.3s ease;
        border: 1px solid #eaeaea;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .offer-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        border-color: var(--primary-color);
    }

    .offer-card-img {
        height: 180px;
        background-color: #f9f9f9;
        background-size: cover;
        background-position: center;
        position: relative;
    }

    .offer-card-img-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #ddd;
        font-size: 40px;
    }

    .offer-card .card-body {
        padding: 20px;
        text-align: left;
        flex: 1;
    }

    .offer-title {
        font-family: 'Oswald', sans-serif;
        font-size: 18px;
        margin-bottom: 10px;
        color: #333;
        font-weight: 400;
        text-transform: uppercase;
    }

    .offer-meta {
        font-size: 13px;
        color: #888;
        margin-bottom: 15px;
    }

    .offer-meta i {
        color: var(--primary-color);
        margin-right: 5px;
    }

    .offer-category-badge {
        font-size: 11px;
        color: #fff;
        background: var(--primary-light);
        padding: 3px 8px;
        border-radius: 3px;
        text-transform: uppercase;
        display: inline-block;
        margin-bottom: 10px;
    }

    .offer-card .card-footer {
        padding: 15px;
        background: white;
        border-top: 1px solid #f0f0f0;
        text-align: right;
    }

    .btn-apply {
        background: transparent;
        border: 1px solid var(--primary-color);
        color: var(--primary-color);
        padding: 8px 20px;
        border-radius: 3px;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        transition: all 0.3s;
        display: inline-block;
    }

    .btn-apply:hover {
        background: var(--primary-color);
        color: white;
        text-decoration: none;
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .offer-item {
        opacity: 0;
        animation: fadeInUp 0.6s ease forwards;
    }

    /* Modal Styling */
    .modal-content {
        border-radius: 3px;
        border: none;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        background: var(--primary-color);
        color: white;
        border-radius: 3px 3px 0 0;
        padding: 15px 20px;
    }

    .modal-title {
        font-family: 'Oswald', sans-serif;
        font-weight: 400;
        text-transform: uppercase;
        line-height: 1.5;
    }

    .close {
        color: white;
        opacity: 0.8;
        text-shadow: none;
    }

    .close:hover {
        color: white;
        opacity: 1;
    }

    .modal-body {
        padding: 30px;
    }

    .form-control {
        border-radius: 3px;
        height: 40px;
        border-color: #eee;
        box-shadow: none;
    }

    .form-control:focus {
        border-color: var(--primary-color);
    }

    .btn-submit-app {
        background: var(--primary-color);
        border: none;
        color: white;
        padding: 10px 30px;
        text-transform: uppercase;
        font-weight: 600;
        border-radius: 3px;
    }

    .btn-submit-app:hover {
        background: #ea321e;
        color: white;
    }
</style>

<!-- Header Section -->
<div class="page-title-section no-img">
    <div class="container">
        <h1 class="page-title">
            <?php echo $category ? htmlspecialchars($category['nom']) : 'All Offers'; ?>
        </h1>
        <?php if ($category && !empty($category['description'])): ?>
            <p class="page-subtitle">
                <?php echo htmlspecialchars($category['description']); ?>
            </p>
        <?php endif; ?>

        <div class="category-stats-badge">
            <i class="fa fa-briefcase"></i> <?php echo count($offers); ?> Open Opportunities
        </div>
    </div>
</div>

<div class="container" style="padding-bottom: 60px;">

    <!-- Alerts -->
    <?php if (!empty($success_msg)): ?>
        <div class="alert alert-success text-center">
            <i class="fa fa-check-circle"></i> <?php echo $success_msg; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger text-center">
            <?php foreach ($errors as $err):
                echo $err . "<br>";
            endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Back Button & Search -->
    <div class="row mb-4" style="margin-bottom: 30px; align-items: center;">
        <div class="col-md-6 col-xs-12">
            <a href="categorielist.php" style="color: #666; font-size: 13px; text-decoration: none;">
                <i class="fa fa-angle-left"></i> Back to Categories
            </a>
        </div>
        <div class="col-md-6 col-xs-12">
            <input type="text" id="offerSearchInput" class="form-control" placeholder="Search offers..."
                onkeyup="filterOffers()">
        </div>
    </div>

    <script>
        function filterOffers() {
            var input, filter, container, items, title, i, txtValue;
            input = document.getElementById("offerSearchInput");
            filter = input.value.toUpperCase();
            items = document.getElementsByClassName("offer-item");

            for (i = 0; i < items.length; i++) {
                title = items[i].querySelector(".offer-title");
                if (title) {
                    txtValue = title.textContent || title.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        items[i].style.display = "";
                    } else {
                        items[i].style.display = "none";
                    }
                }
            }
        }
    </script>

    <?php if ($offers && count($offers) > 0): ?>
        <div class="row">
            <?php
            $delay = 0;
            foreach ($offers as $offer):
                $delay += 0.1;
                // Check if image exists
                $offerImg = !empty($offer['image']) ? $offer['image'] : null;
                ?>
                <div class="col-md-4 col-sm-6 offer-item" style="margin-bottom: 30px; animation-delay: <?php echo $delay; ?>s;">
                    <div class="offer-card">
                        <!-- Image Section -->
                        <div class="offer-card-img"
                            style="<?php echo $offerImg ? "background-image: url('img/" . $offerImg . "');" : ""; ?>">
                            <?php if (!$offerImg): ?>
                                <div class="offer-card-img-placeholder">
                                    <i class="fa fa-briefcase"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="card-body">
                            <span class="offer-category-badge">
                                <?php echo !empty($offer['categorie_nom']) ? htmlspecialchars($offer['categorie_nom']) : 'Category'; ?>
                            </span>

                            <h3 class="offer-title"><?php echo htmlspecialchars($offer['titre']); ?></h3>

                            <div class="offer-meta">
                                <?php if (!empty($offer['location'])): ?>
                                    <i class="fa fa-map-marker"></i> <?php echo htmlspecialchars($offer['location']); ?>
                                <?php endif; ?>
                            </div>

                            <!-- Snippet of description could go here -->
                        </div>

                        <div class="card-footer">
                            <button type="button" class="btn-apply btn-open-modal" data-id="<?php echo $offer['id']; ?>"
                                data-title="<?php echo htmlspecialchars($offer['titre']); ?>">
                                Apply Now
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fa fa-frown-o"></i>
            <h3>No Offers Found</h3>
            <p>There are currently no job openings in this category.</p>
        </div>
    <?php endif; ?>
</div>

<!-- Apply Modal -->
<div class="modal fade" id="applyModal" tabindex="-1" role="dialog" aria-labelledby="applyModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="applyModalLabel">Apply for <span id="modalOfferTitle">Job</span></h4>
            </div>
            <form method="POST" enctype="multipart/form-data"
                action="viewoffers.php?category=<?php echo $category_id; ?>" novalidate
                onsubmit="return validateApplicationForm()">
                <div class="modal-body">
                    <!-- Hidden inputs -->
                    <input type="hidden" name="id_offre" id="modalOfferId" value="">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="full_name">Full Name</label>
                                <input type="text" name="full_name" id="app_full_name" class="form-control">
                                <small id="err_app_full_name" class="text-danger"></small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="text" name="email" id="app_email" class="form-control">
                        <small id="err_app_email" class="text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" name="phone" id="app_phone" class="form-control">
                        <small id="err_app_phone" class="text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label for="cvFile">Upload CV (PDF/Doc) <span class="text-muted small">(Required)</span></label>
                        <input type="file" name="cv" id="cvFile" class="form-control" style="padding-top: 8px;">
                        <small id="err_cvFile" class="text-danger"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="apply_submit" class="btn-submit-app">Submit Application</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function validateApplicationForm() {
        let valid = true;

        // Clear previous errors
        document.getElementById("err_app_full_name").innerHTML = "";
        document.getElementById("err_app_email").innerHTML = "";
        document.getElementById("err_app_phone").innerHTML = "";
        document.getElementById("err_cvFile").innerHTML = "";

        const fullName = document.getElementById("app_full_name").value.trim();
        const email = document.getElementById("app_email").value.trim();
        const phone = document.getElementById("app_phone").value.trim();
        const cvFile = document.getElementById("cvFile").value;

        const phoneRegex = /^[0-9]+$/;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (fullName === "") {
            document.getElementById("err_app_full_name").innerHTML = "Full Name is required";
            valid = false;
        }

        if (email === "") {
            document.getElementById("err_app_email").innerHTML = "Email is required";
            valid = false;
        } else if (!emailRegex.test(email)) {
            document.getElementById("err_app_email").innerHTML = "Invalid email format";
            valid = false;
        }

        if (phone === "") {
            document.getElementById("err_app_phone").innerHTML = "Phone is required";
            valid = false;
        } else if (!phoneRegex.test(phone)) {
            document.getElementById("err_app_phone").innerHTML = "Phone must contain only numbers";
            valid = false;
        } else if (phone.length < 8) {
            document.getElementById("err_app_phone").innerHTML = "Phone must be at least 8 digits";
            valid = false;
        }

        if (cvFile === "") {
            document.getElementById("err_cvFile").innerHTML = "Please upload your CV";
            valid = false;
        } else {
            const fileInput = document.getElementById("cvFile");
            const fileSize = fileInput.files[0].size;
            const maxSize = 10 * 1024 * 1024;

            if (fileSize > maxSize) {
                document.getElementById("err_cvFile").innerHTML = "File size exceeds 10MB limit";
                valid = false;
            }
        }

        return valid;
    }
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="asset/js/bootstrap.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof jQuery !== 'undefined') {
            $('.btn-open-modal').on('click', function (e) {
                e.preventDefault();
                var offerId = $(this).data('id');
                var offerTitle = $(this).data('title');

                $('#modalOfferId').val(offerId);
                $('#modalOfferTitle').text(offerTitle);

                $('#applyModal').modal('show');
            });
        }
    });
</script>

<footer style="background: #222; color: #fff; padding: 40px 0; margin-top: 60px;">
    <div class="container text-center">
        <p>&copy; <?php echo date('Y'); ?> OneWorld. All Rights Reserved.</p>
    </div>
</footer>

</body>

</html>