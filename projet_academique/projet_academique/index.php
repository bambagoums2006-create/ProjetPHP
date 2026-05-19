<?php
session_start();

$dossier_public = "http://localhost/projet_academique/public/";

// Rediriger vers connexion si non connecté
if (!isset($_SESSION['user']) && (!isset($_GET['page']) || $_GET['page'] !== 'connexion')) {
    header("Location: index.php?page=connexion");
    exit;
}

require_once "traitements/requetes.php";

include_once "includes/header.php";

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Pages accessibles sans être connecté
$pages_publiques = ['connexion'];

if (!in_array($page, $pages_publiques)) {
    include_once "includes/navbar.php";
    include_once "includes/sidebar.php";
}

if (file_exists("pages/$page.php")) {
    include_once "pages/$page.php";
} else {
    include_once "pages/erreur404.php";
}

if (!in_array($page, $pages_publiques)) {
    // fermer les divs ouverts par sidebar
    echo '</div></main></div></div>';
}

include_once "includes/footer.php";
?>
