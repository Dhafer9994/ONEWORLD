<?php
include '../model/offre.php';
include '../controller/offreC.php';
$oc=new offrec();
$o=new offre($_POST["nom"],$_POST["categorie"],$_POST["description"],$_POST["location"],"active",'none');
$oc->addoffre($o);
echo'<div style="background-color: #d4edda; 
            color: #155724; 
            padding: 15px; 
            margin-bottom: 20px; 
            border-radius: 5px;
            border: 1px solid #c3e6cb;">
    Offer added successfully!
</div>';