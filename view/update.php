<?php
include '../controller/offreC.php';
$oc = new Offrec();


$id = $_GET['id'];
$offre = $oc->getOffreById($id); // Tu dois avoir une méthode getOffreById dans ton controller

// Traitement du formulaire
if (isset($_POST['update'])) {
    $categorie = $_POST['categorie'];
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $status = $_POST['status'];
    $auteur = $_POST['auteur'];

    $oc->updateOffre($id, $categorie, $titre, $description, $location, $status, $auteur);
    header("Location: listeoffre.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Offer</title>
    <script>
        function validateForm() {
            const title = document.getElementById("titre").value.trim();
            const category = document.getElementById("categorie").value;
            const description = document.getElementById("description").value.trim();
            const location = document.getElementById("location").value.trim();
            const status = document.getElementById("status").value.trim();
            const author = document.getElementById("auteur").value.trim();

            if (title === "") {
                alert("Title is required!");
                return false;
            }
            if (category === "") {
                alert("Category is required!");
                return false;
            }
            if (description === "") {
                alert("Description is required!");
                return false;
            }
            if (location === "") {
                alert("Location is required!");
                return false;
            }
            if (status === "") {
                alert("Status is required!");
                return false;
            }
            if (author === "") {
                alert("Author is required!");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <h2>Update Offer</h2>
    <form method="POST" onsubmit="return validateForm();">
        <!-- Title -->
        <label>Title:</label>
        <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($offre['titre']) ?>"><br><br>

        <!-- Category select -->
        <label>Category:</label>
        <select id="categorie" name="categorie">
            <option value="">--Select Category--</option>
            <option value="Employment" <?= $offre['categorie'] == 'Employment' ? 'selected' : '' ?>>Employment</option>
            <option value="Accommodation" <?= $offre['categorie'] == 'Accommodation' ? 'selected' : '' ?>>Accommodation</option>
            <option value="Education" <?= $offre['categorie'] == 'Education' ? 'selected' : '' ?>>Education</option>
        </select><br><br>

        <!-- Description -->
        <label>Description:</label>
        <textarea id="description" name="description"><?= htmlspecialchars($offre['description']) ?></textarea><br><br>

        <!-- Location -->
        <label>Location:</label>
        <input type="text" id="location" name="location" value="<?= htmlspecialchars($offre['location']) ?>"><br><br>

        <!-- Status -->
        <label>Status:</label>
        <input type="text" id="status" name="status" value="<?= htmlspecialchars($offre['status']) ?>"><br><br>

        <!-- Author -->
        <label>Author:</label>
        <input type="text" id="auteur" name="auteur" value="<?= htmlspecialchars($offre['auteur']) ?>"><br><br>

        <!-- Submit button -->
        <button type="submit" name="update">Update</button>
    </form>
</body>
</html>
