<?php
class offre{
    private int $id;
    private string $categorie;
    private string $titre;
    private string $description;
    private string $location;
    private string $status;
    private string $auteur;


    public function __construct(
        string $titre,
        string $categorie,
        string $description,
        string $location,
        string $status,
        string $auteur

    ) {
        $this->categorie = $categorie;
        $this->titre = $titre;
        $this->description = $description;
        $this->location = $location;
        $this->status = $status;
        $this->auteur = $auteur;
    }

public function getid(){return $this->id;}
public function gettitre(){return $this->titre;}
public function getcategorie(){return $this->categorie;}
public function getdescription(){return $this->description;}
public function getlocation(){return $this->location;}
public function getstatus(){return $this->status;}
public function getauteur(){return $this->auteur;}

public function setcategorie(string $n){$this->categorie=$n;}
public function settitle(string $n){$this->titre=$n;}
public function setdescription(string $d){$this->description=$d;}
public function setlocation(string $n){$this->location=$n;}
public function setstatus(string $n){$this->status=$n;}
public function setauteur(string $n){$this->auteur=$n;}

}

