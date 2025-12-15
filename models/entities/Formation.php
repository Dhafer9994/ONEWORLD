<?php
class Formation
{
    private $id;
    private $titre;
    private $description;
    private $duree;
    private $niveau;
    private $prix;
    private $date_debut;
    private $date_fin;
    private $places_max;
    private $statut;
    private $url_meet; // New property
    private $formateur_id;
    private $created_at;
    private $updated_at;

    // Getters
    public function getId()
    {
        return $this->id;
    }
    public function getTitre()
    {
        return $this->titre;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function getDuree()
    {
        return $this->duree;
    }
    public function getNiveau()
    {
        return $this->niveau;
    }
    public function getPrix()
    {
        return $this->prix;
    }
    public function getDateDebut()
    {
        return $this->date_debut;
    }
    public function getDateFin()
    {
        return $this->date_fin;
    }
    public function getPlacesMax()
    {
        return $this->places_max;
    }
    public function getStatut()
    {
        return $this->statut;
    }
    public function getUrlMeet()
    {
        return $this->url_meet;
    }
    public function getFormateurId()
    {
        return $this->formateur_id;
    }
    public function getCreatedAt()
    {
        return $this->created_at;
    }
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    // Setters
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }
    public function setDescription($description)
    {
        $this->description = $description;
    }
    public function setDuree($duree)
    {
        $this->duree = $duree;
    }
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;
    }
    public function setPrix($prix)
    {
        $this->prix = $prix;
    }
    public function setDateDebut($date_debut)
    {
        $this->date_debut = $date_debut;
    }
    public function setDateFin($date_fin)
    {
        $this->date_fin = $date_fin;
    }
    public function setPlacesMax($places_max)
    {
        $this->places_max = $places_max;
    }
    public function setStatut($statut)
    {
        $this->statut = $statut;
    }
    public function setUrlMeet($url_meet)
    {
        $this->url_meet = $url_meet;
    }
    public function setFormateurId($formateur_id)
    {
        $this->formateur_id = $formateur_id;
    }
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    // Helper to hydrate from array
    public function hydrate(array $data)
    {
        foreach ($data as $key => $value) {
            // Convert snake_case to camelCase setter (e.g. date_debut -> setDateDebut)
            $method = 'set' . str_replace('_', '', ucwords($key, '_'));
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }
}
?>