<<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Offer</title>

<!-- ======== FORM STYLE (comme tu voulais) ======== -->
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f5f5f5;
        display: flex;
        justify-content: center;
        padding: 40px 0;
    }

    h1 {
        text-align: center;
        margin-bottom: 20px;
        font-size: 26px;
        color: #333;
    }

    .form-container {
        width: 450px;
        background: white;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 0 12px rgba(0,0,0,0.1);
    }

    label {
        font-weight: bold;
        color: #444;
    }

    input[type="text"],
    textarea,
    select {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        background: #fafafa;
    }

    textarea {
        height: 100px;
        resize: none;
    }

    button {
        width: 100%;
        padding: 12px;
        background: #27ae60;
        color: white;
        font-size: 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: 0.3s;
    }

    button:hover {
        background: #1e874b;
    }
</style>


<script>
function validateForm() {
    const nom = document.getElementById("name").value;
    const description = document.getElementById("description").value;
    const categorie = document.getElementById("categorie").value;
    const location = document.getElementById("location").value;
    
   

    if (!nom) {
        alert("Offer title is required");
        return false;
    }

    if (nom.length < 3) {
        alert("The offer title must contain at least 3 characters");
        return false;
    }

    if (!description) {
        alert("Description is required");
        return false;
    }

    if (!categorie) {
        alert("Please select a category");
        return false;
    }

    if (!location) {
        alert("Location is required");
        return false;
    }

   

    return true;
}
</script>

</head>

<body>

<div class="form-container">
<h1>Add New Offer</h1>

<form action="ajout.php" method="POST" onsubmit="return validateForm()">

    <label>Title</label>
    <input type="text" id="name" name="nom" placeholder="Enter offer title">

    <label>Description</label>
    <textarea id="description" name="description" placeholder="Enter offer description"></textarea>

    <label>Category</label>
    <select id="categorie" name="categorie">
        <option value=""> Select Category </option>
        <option value="Accommodation">Accommodation</option>
        <option value="Education">Education</option>
        <option value="Employment">Employment</option>
    </select>

    <label>Location</label>
    <input type="text" id="location" name="location" placeholder="Enter location">

    <button type="submit">Add Offer</button>
</form>
</div>

</body>
</html>
