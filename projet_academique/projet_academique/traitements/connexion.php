<?php
// ============================================
// CONNEXION À LA BASE DE DONNÉES (PDO)
// ============================================

define('DB_HOST', 'localhost');
define('DB_NAME', 'gestion_academique');
define('DB_USER', 'root');       // Mets ton utilisateur MySQL
define('DB_PASS', '');           // Mets ton mot de passe MySQL (souvent vide sur XAMPP)
define('DB_CHARSET', 'utf8mb4');

function getConnexion() {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }
    return $pdo;
}
