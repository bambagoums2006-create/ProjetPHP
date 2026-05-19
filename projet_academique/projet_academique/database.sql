-- ============================================
-- BASE DE DONNÉES : gestion_academique
-- Projet PHP L2 GL 2026
-- ============================================

CREATE DATABASE IF NOT EXISTS gestion_academique CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gestion_academique;

-- ============================================
-- TABLE : niveaux
-- ============================================
CREATE TABLE IF NOT EXISTS niveaux (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- TABLE : classes
-- ============================================
CREATE TABLE IF NOT EXISTS classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    niveau_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (niveau_id) REFERENCES niveaux(id) ON DELETE CASCADE
);

-- ============================================
-- TABLE : etudiants
-- ============================================
CREATE TABLE IF NOT EXISTS etudiants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matricule VARCHAR(20) NOT NULL UNIQUE,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150),
    classe_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (classe_id) REFERENCES classes(id) ON DELETE CASCADE
);

-- ============================================
-- TABLE : modules
-- ============================================
CREATE TABLE IF NOT EXISTS modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) NOT NULL UNIQUE,
    nom VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- TABLE : classe_module (relation module <-> classe)
-- ============================================
CREATE TABLE IF NOT EXISTS classe_module (
    id INT AUTO_INCREMENT PRIMARY KEY,
    classe_id INT NOT NULL,
    module_id INT NOT NULL,
    FOREIGN KEY (classe_id) REFERENCES classes(id) ON DELETE CASCADE,
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE,
    UNIQUE(classe_id, module_id)
);

-- ============================================
-- TABLE : evaluations
-- ============================================
CREATE TABLE IF NOT EXISTS evaluations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    etudiant_id INT NOT NULL,
    module_id INT NOT NULL,
    type_eval ENUM('devoir', 'examen', 'tp') NOT NULL,
    note DECIMAL(5,2) NOT NULL CHECK (note >= 0 AND note <= 20),
    date_eval DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE,
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE
);

-- ============================================
-- TABLE : utilisateurs (connexion admin)
-- ============================================
CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('admin', 'enseignant') DEFAULT 'enseignant',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- DONNÉES DE TEST
-- ============================================

-- Niveaux
INSERT INTO niveaux (nom) VALUES ('Licence 1'), ('Licence 2'), ('Master 1'), ('Master 2');

-- Classes
INSERT INTO classes (nom, niveau_id) VALUES
('GL', 2), ('IAGE', 2), ('CYBER', 2),
('GL', 1), ('IAGE', 1),
('GL', 3), ('IAGE', 3);

-- Modules
INSERT INTO modules (code, nom) VALUES
('INF201', 'Programmation Web'),
('INF202', 'Base de Données'),
('INF203', 'Algorithmes'),
('INF204', 'Systèmes d''exploitation'),
('INF205', 'Réseaux');

-- Associer modules aux classes
INSERT INTO classe_module (classe_id, module_id) VALUES
(1,1),(1,2),(1,3),(1,4),(1,5),
(2,1),(2,2),(2,3),
(3,4),(3,5),(3,3);

-- Étudiants dans la classe GL L2 (id=1)
INSERT INTO etudiants (matricule, nom, prenom, email, classe_id) VALUES
('L2GL001', 'Diallo', 'Mamadou', 'mamadou@isi.sn', 1),
('L2GL002', 'Fall', 'Fatou', 'fatou@isi.sn', 1),
('L2GL003', 'Ndiaye', 'Ibrahima', 'ibrahima@isi.sn', 1),
('L2GL004', 'Ba', 'Aminata', 'aminata@isi.sn', 1),
('L2GL005', 'Sarr', 'Ousmane', 'ousmane@isi.sn', 1),
('L2IA001', 'Sow', 'Aissatou', 'aissatou@isi.sn', 2),
('L2IA002', 'Mbaye', 'Cheikh', 'cheikh@isi.sn', 2),
('L2CY001', 'Gueye', 'Abdou', 'abdou@isi.sn', 3);

-- Évaluations (devoirs et examens - pas de TP pour le calcul de moyenne)
INSERT INTO evaluations (etudiant_id, module_id, type_eval, note, date_eval) VALUES
(1,1,'devoir',14.00,'2025-11-15'),
(1,1,'examen',12.50,'2026-01-20'),
(1,2,'devoir',16.00,'2025-11-16'),
(1,2,'examen',15.00,'2026-01-21'),
(1,3,'devoir',11.00,'2025-11-17'),
(1,3,'examen',13.00,'2026-01-22'),
(1,1,'tp',18.00,'2025-12-01'),

(2,1,'devoir',8.00,'2025-11-15'),
(2,1,'examen',9.50,'2026-01-20'),
(2,2,'devoir',7.00,'2025-11-16'),
(2,2,'examen',6.00,'2026-01-21'),

(3,1,'devoir',17.00,'2025-11-15'),
(3,1,'examen',18.00,'2026-01-20'),
(3,2,'devoir',16.00,'2025-11-16'),
(3,2,'examen',19.00,'2026-01-21'),

(4,1,'devoir',4.00,'2025-11-15'),
(4,1,'examen',3.50,'2026-01-20'),
(4,2,'devoir',5.00,'2025-11-16'),
(4,2,'examen',4.00,'2026-01-21'),

(5,1,'devoir',12.00,'2025-11-15'),
(5,1,'examen',11.00,'2026-01-20'),
(5,2,'devoir',13.00,'2025-11-16'),
(5,2,'examen',14.00,'2026-01-21'),

(6,1,'devoir',10.00,'2025-11-15'),
(6,1,'examen',11.00,'2026-01-20'),
(7,2,'devoir',9.00,'2025-11-16'),
(7,2,'examen',8.50,'2026-01-21'),
(8,4,'devoir',15.00,'2025-11-17'),
(8,4,'examen',16.00,'2026-01-22');

-- Compte admin (mot de passe : admin123)
INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES
('Administrateur', 'admin@isi.sn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
