<?php
class User
{
    private $id;
    private $nom;
    private $prenom;
    private $email;
    private $password;
    private $role;
    private $pays;
    private $telephone;
    private $id_refugie;
    private $created_at;

    // Getters
    public function getId()
    {
        return $this->id;
    }
    public function getNom()
    {
        return $this->nom;
    }
    public function getPrenom()
    {
        return $this->prenom;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function getRole()
    {
        return $this->role;
    }
    public function getPays()
    {
        return $this->pays;
    }
    public function getTelephone()
    {
        return $this->telephone;
    }
    public function getIdRefugie()
    {
        return $this->id_refugie;
    }
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    // Setters
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setNom($nom)
    {
        $this->nom = $nom;
    }
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }
    public function setRole($role)
    {
        $this->role = $role;
    }
    public function setPays($pays)
    {
        $this->pays = $pays;
    }
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }
    public function setIdRefugie($id_refugie)
    {
        $this->id_refugie = $id_refugie;
    }
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
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