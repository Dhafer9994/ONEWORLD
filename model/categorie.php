<?php
class Categorie
{
    private int $id;
    private string $nom;
    private string $description;
    private ?string $image;

    // Constructeur
    public function __construct(string $nom, string $description, ?string $image = null)
    {
        $this->nom = $nom;
        $this->description = $description;
        $this->image = $image;
    }
    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getnom(): string
    {
        return $this->nom;
    }

    public function getdescription(): string
    {
        return $this->description;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    // Setters
    public function setnom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function setdescription(string $description): void
    {
        $this->description = $description;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }
}
?>