<?php
class Inscription
{
    private $id;
    private $refugie_id;
    private $formation_id;
    private $date_inscription;
    private $statut;
    private $progression;

    // Getters
    public function getId()
    {
        return $this->id;
    }
    public function getRefugieId()
    {
        return $this->refugie_id;
    }
    public function getFormationId()
    {
        return $this->formation_id;
    }
    public function getDateInscription()
    {
        return $this->date_inscription;
    }
    public function getStatut()
    {
        return $this->statut;
    }
    public function getProgression()
    {
        return $this->progression;
    }

    // Setters
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setRefugieId($refugie_id)
    {
        $this->refugie_id = $refugie_id;
    }
    public function setFormationId($formation_id)
    {
        $this->formation_id = $formation_id;
    }
    public function setDateInscription($date_inscription)
    {
        $this->date_inscription = $date_inscription;
    }
    public function setStatut($statut)
    {
        $this->statut = $statut;
    }
    public function setProgression($progression)
    {
        $this->progression = $progression;
    }

    public function hydrate(array $data)
    {
        foreach ($data as $key => $value) {
            $method = 'set' . str_replace('_', '', ucwords($key, '_'));
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }
}
?>