<?php
include '../controller/offreC.php';
$oc = new Offrec();


$id = $_GET['id'];
$offre = $oc->getOffreById($id); 

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

    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        padding: 20px;
    }

    h2 {
        color: #4CAF50;
        margin-bottom: 20px;
    }

    form {
        background-color: #fff;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        max-width: 600px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: #333;
    }

    input[type="text"], select, textarea {
        width: 100%;
        padding: 10px 12px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
        font-size: 14px;
    }

    textarea {
        resize: vertical;
        min-height: 80px;
    }

    button[type="submit"] {
        background-color: #4CAF50;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s;
    }

    button[type="submit"]:hover {
        background-color: #45a049;
    }

    /* Optional: focus style */
    input[type="text"]:focus,
    select:focus,
    textarea:focus {
        border-color: #4CAF50;
        outline: none;
    }
</style>

    
</head>
<body>
        <script>
        function validateForm() {
            const title = document.getElementById("titre").value;
            const category = document.getElementById("categorie").value;
            const description = document.getElementById("description").value;
            const location = document.getElementById("location").value;
            const status = document.getElementById("status").value;
            const author = document.getElementById("auteur").value;

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
    <h2>Update Offer</h2>
    <form method="POST" onsubmit="return validateForm();">
        <label>Title:</label>
        <input type="text" id="titre" name="titre" value="<?php echo $offre['titre']; ?>
?>"><br><br>

        <label>Category:</label>
        <select id="categorie" name="categorie">
            <option value="">--Select Category--</option>
            <option value="Employment" <?php if ($offre['categorie'] == 'Employment') echo 'selected'; ?>>Employment</option>
            <option value="Accommodation" <?php if ($offre['categorie'] == 'Accommodation') echo 'selected'; ?>>Accommodation</option>
            <option value="Education" <?php if ($offre['categorie'] == 'Education') echo 'selected'; ?>>Education</option>
        </select><br><br>

        <label>Description:</label>
        <textarea id="description" name="description"><?= htmlspecialchars($offre['description']) ?></textarea><br><br>

        <label>Location:</label>
        <input type="text" id="location" name="location" value="<?php echo($offre['location']) ?>"><br><br>

        <label>Status:</label>
        <input type="text" id="status" name="status" value="<?php echo($offre['status']) ?>"><br><br>

        <label>Author:</label>
        <input type="text" id="auteur" name="auteur" value="<?php echo($offre['auteur']) ?>"><br><br>

        <button type="submit" name="update">Update</button>
    </form>
</body>
</html>
