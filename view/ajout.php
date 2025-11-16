<?php
include '../model/offre.php';
include '../controller/offreC.php';
$oc=new offrec();
$o=new offre($_POST["nom"],$_POST["categorie"],$_POST["description"],$_POST["location"],"active",'none');
$oc->addoffre($o);