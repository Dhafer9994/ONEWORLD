<?php
if (!class_exists('User')) {
    class User {
        private string $id;
        private string $nom;
        private string $prenom;
        private string $email;
        private string $mdp;
        private string $role;
        private string $telephone; 
        private string $adresse;
        private string $photo;

        public function __construct(
            string $nom, 
            string $prenom, 
            string $email, 
            string $mdp, 
            string $role, 
            string $telephone, 
            string $adresse,
            string $photo = ""
        ){
            $this->nom = $nom;
            $this->prenom = $prenom;
            $this->email = $email;
            $this->mdp = $mdp;
            $this->role = $role;
            $this->telephone = $telephone;
            $this->adresse = $adresse;
            $this->photo = $photo;
        }

        
        public function getId(): int { return $this->id; }  
        public function setId($id) {
            $this->id = $id;
        }
        

        public function getNom(): string { return $this->nom; }
        public function setNom(string $nom){ $this->nom = $nom; }

        public function getPrenom(): string { return $this->prenom; }
        public function setPrenom(string $prenom){ $this->prenom = $prenom; }

        public function getEmail(): string { return $this->email; }
        public function setEmail(string $email){ $this->email = $email; }

        public function getMdp(): string { return $this->mdp; }
        public function setMdp(string $mdp){ $this->mdp = $mdp; }

        public function getRole(): string { return $this->role; }
        public function setRole(string $role){ $this->role = $role; }

        public function getTelephone(): string { return $this->telephone; }
        public function setTelephone(string $telephone){ $this->telephone = $telephone; }

        public function getAdresse(): string { return $this->adresse; }
        public function setAdresse(string $adresse){ $this->adresse = $adresse; }

        public function getPhoto(): string { return $this->photo; }
        public function setPhoto(string $photo){ $this->photo = $photo; }
    }
}
