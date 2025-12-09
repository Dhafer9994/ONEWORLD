-- ========================================================
-- VERSION MINIMALE POUR TESTER LE CRUD
-- ========================================================

CREATE DATABASE IF NOT EXISTS oneworld;
USE oneworld;

-- Table utilisateurs minimale
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    email VARCHAR(255),
    mot_de_passe VARCHAR(255),
    type VARCHAR(50) DEFAULT 'refugie',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table formations minimale
CREATE TABLE formations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(255),
    description TEXT,
    formateur_id INT,
    duree VARCHAR(100),
    niveau VARCHAR(50),
    prix DECIMAL(10,2) DEFAULT 0,
    date_debut DATE,
    date_fin DATE,
    places_max INT DEFAULT 20,
    statut VARCHAR(50) DEFAULT 'actif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table inscriptions minimale
CREATE TABLE inscriptions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    refugie_id INT,
    formation_id INT,
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    statut VARCHAR(50) DEFAULT 'en_attente',
    progression INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Données de test minimales
INSERT INTO users (nom, prenom, email, mot_de_passe, type) VALUES
('Admin', 'Test', 'admin@test.com', 'password', 'admin'),
('Formateur', 'Test', 'formateur@test.com', 'password', 'formateur'),
('Réfugié', 'Test', 'refugie@test.com', 'password', 'refugie');

INSERT INTO formations (titre, description, formateur_id, duree, niveau, date_debut, places_max) VALUES
('Formation Test 1', 'Description test 1', 2, '40h', 'débutant', DATE_ADD(CURDATE(), INTERVAL 7 DAY), 10),
('Formation Test 2', 'Description test 2', 2, '60h', 'intermédiaire', DATE_ADD(CURDATE(), INTERVAL 14 DAY), 15);

INSERT INTO inscriptions (refugie_id, formation_id, statut) VALUES
(3, 1, 'confirme'),
(3, 2, 'en_attente');

SELECT 'Base de données minimale créée!' as message;