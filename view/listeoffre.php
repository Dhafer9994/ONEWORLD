<?php
include '../controller/offreC.php';
$oc = new Offrec();
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $oc->deleteOffre($id);
    header("Location: listeoffre.php");
    exit;
}

$liste = $oc->listeOffre();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Liste des Offres</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        padding: 20px;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        background-color: #fff;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
    }

    th {
        background-color: #4CAF50;
        color: white;
        text-transform: uppercase;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #e0f7fa;
    }

    .icon {
        vertical-align: middle;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .icon:hover {
        transform: scale(1.2);
    }

    td a {
        text-decoration: none;
        margin: 0 5px;
    }
</style>
</head>
<body>

<h2>Liste des Offres</h2>

<table>
    <tr>
        <th>Id</th>
        <th>Category</th>
        <th>Title</th>
        <th>Description</th>
        <th>Location</th>
        <th>Status</th>
        <th>Author</th>
        <th>Operations</th>
    </tr>

    <?php foreach($liste as $offre){ ?>
    <tr>
        <td><?= $offre['id'] ?></td>
        <td><?= $offre['categorie'] ?></td>
        <td><?= $offre['titre'] ?></td>
        <td><?= $offre['description'] ?></td>
        <td><?= $offre['location'] ?></td>
        <td><?= $offre['status'] ?></td>
        <td><?= $offre['auteur'] ?></td>
        <td>
            <a href="listeoffre.php?delete=<?= $offre['id'] ?>">
                <img class="icon" src="https://cdn-icons-png.flaticon.com/512/6861/6861362.png" alt="Delete" width="25" height="25">
            </a>
            |
            <a href="update.php?id=<?= $offre['id'] ?>">
                <img class="icon" src="https://cdn-icons-png.flaticon.com/512/1159/1159633.png" width="20">
            </a>
        </td>
    </tr>
    <?php } ?>
</table>

</body>
</html>
