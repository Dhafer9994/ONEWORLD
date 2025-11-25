<?php
class Categorie {
    private int $id;
    private string $nom;
    private string $description;

    // Constructeur
   public function __construct(string $nom, string $description) {
        $this->nom = $nom;
        $this->description = $description;
    }
    // Getters
    public function getId(): int {
        return $this->id;
    }

    public function getnom(): string {
        return $this->nom;
    }

    public function getdescription(): string {
        return $this->description;
    }

    // Setters
    public function setnom(string $nom): void {
        $this->nom = $nom;
    }

    public function setdescription(string $description): void {
        $this->description = $description;
    }
}
?>
