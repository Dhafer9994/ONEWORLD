<?php 
include '../model/offre.php';
include '../controller/offreC.php';

$oc = new offrec();

if(isset($_POST['add'])) {

    $offre = new offre(
        $_POST['categorie'],
        $_POST['titre'],
        $_POST['description'],
        $_POST['location'],
        $_POST['status'],
        $_POST['auteur']
    );

    $oc->addOffre($offre);

    header("Location: listeoffre.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Create Offer</title>


<script>
function validateCreateForm() {
    const categorie = document.getElementById("categorie").value;
    const titre = document.getElementById("titre").value;
    const description = document.getElementById("description").value;
    const location = document.getElementById("location").value;
    const status = document.getElementById("status").value;
    const auteur = document.getElementById("auteur").value;

    if (categorie === "") {
        alert("Please choose a category.");
        return false;
    }
    if (titre === "") {
        alert("Title cannot be empty.");
        return false;
    }
    if (description === "") {
        alert("Description cannot be empty.");
        return false;
    }
    if (location === "") {
        alert("Location cannot be empty.");
        return false;
    }
    if (status === "") {
        alert("Status cannot be empty.");
        return false;
    }
    if (auteur === "") {
        alert("Author cannot be empty.");
        return false;
    }
    return true;
}
</script>

</head>
<body>

<div class="form-container">
<h2>Create Offer</h2>

<form method="POST" onsubmit="return validateCreateForm()">

    <div class="form-group">
        <label>Category:</label>
        <select name="categorie" id="categorie">
            <option value="">-- Select Category --</option>
            <option value="Employment">Employment</option>
            <option value="Accommodation">Accommodation</option>
            <option value="Education">Education</option>
        </select>
    </div>

    <div class="form-group">
        <label>Title:</label>
        <input type="text" id="titre" name="titre">
    </div>

    <div class="form-group">
        <label>Description:</label>
        <textarea id="description" name="description"></textarea>
    </div>

    <div class="form-group">
        <label>Location:</label>
        <input type="text" id="location" name="location">
    </div>

    <div class="form-group">
        <label>Status:</label>
        <input type="text" id="status" name="status">
    </div>

    <div class="form-group">
        <label>Author:</label>
        <input type="text" id="auteur" name="auteur">
    </div>

    <button type="submit" name="add">Create Offer</button>

</form>
</div>
<style>
    body {
        font-family: Arial;
        background: #f4f4f9;
        padding: 30px;
    }

    .form-container {
        width: 70%;
        margin: auto;
        background: white;
        padding: 25px 40px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    h2 {
        text-align: center;
        margin-bottom: 25px;
    }

    .form-group {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .form-group label {
        width: 150px;
        font-weight: bold;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        flex: 1;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    textarea { height: 90px; }

    button {
        background: #4CAF50;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        width: 100%;
        font-size: 16px;
    }

    button:hover { background: #45a049; }
</style>


</body>
</html>
