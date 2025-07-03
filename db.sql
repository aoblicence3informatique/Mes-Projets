-- Création de la base de données
CREATE DATABASE IF NOT EXISTS ajvdk_finance;
USE ajvdk_finance;

-- Table des utilisateurs
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'tresorier', 'membre') NOT NULL,
    statut ENUM('actif', 'inactif') DEFAULT 'actif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des membres
CREATE TABLE members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    contact VARCHAR(100),
    sexe ENUM('homme', 'femme') NOT NULL,
    statut ENUM('actif', 'inactif') DEFAULT 'actif',
    user_id INT UNIQUE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Table des cotisations
CREATE TABLE contributions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    membre_id INT NOT NULL,
    montant DECIMAL(10,2) NOT NULL,
    type ENUM('hebdomadaire', 'mensuel') NOT NULL,
    date_cotisation DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (membre_id) REFERENCES members(id) ON DELETE CASCADE
);

-- Table des dons
CREATE TABLE donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donateur VARCHAR(100) DEFAULT 'Anonyme',
    montant DECIMAL(10,2) NOT NULL,
    date_don DATE NOT NULL,
    motif TEXT,
    mode_paiement ENUM('especes', 'orange_money') NOT NULL DEFAULT 'especes',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =============================
-- Exemples de requêtes SQL utilisées dans l'application AJVDNK
-- =============================

-- Sélection d'un trésorier (pour affichage/modification du profil)
SELECT username, email FROM users WHERE id = ? AND role = "tresorier";

-- Mise à jour du profil trésorier (sans changement de mot de passe)
UPDATE users SET username = ?, email = ? WHERE id = ? AND role = "tresorier";

-- Mise à jour du profil trésorier (avec changement de mot de passe)
UPDATE users SET username = ?, email = ?, password = ? WHERE id = ? AND role = "tresorier";

-- Insertion d'un trésorier (exemple d'initialisation, à adapter avec un mot de passe hashé)
INSERT INTO users (username, email, password, role, statut) VALUES ('tresorier', 'tresorier@email.com', '<mot_de_passe_hashé>', 'tresorier', 'actif');

-- =============================
