<?php
require_once __DIR__ . '/connexion.php';

// ============================================
// NIVEAUX
// ============================================

function getAllNiveaux()
{
    $pdo = getConnexion();
    return $pdo->query("SELECT * FROM niveaux ORDER BY nom")->fetchAll();
}

function addNiveau($nom)
{
    $pdo = getConnexion();
    $stmt = $pdo->prepare("INSERT INTO niveaux (nom) VALUES (?)");
    return $stmt->execute([$nom]);
}

function getNiveauById($id)
{
    $pdo = getConnexion();
    $stmt = $pdo->prepare("SELECT * FROM niveaux WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function niveauSansClasse($niveau_id)
{
    $pdo = getConnexion();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM classes WHERE niveau_id = ?");
    $stmt->execute([$niveau_id]);
    return $stmt->fetchColumn() == 0;
}

// ============================================
// CLASSES
// ============================================

function getAllClasses()
{
    $pdo = getConnexion();
    return $pdo->query("SELECT c.*, n.nom AS niveau_nom FROM classes c JOIN niveaux n ON c.niveau_id = n.id ORDER BY n.nom, c.nom")->fetchAll();
}

function getClassesByNiveau($niveau_id)
{
    $pdo = getConnexion();
    $stmt = $pdo->prepare("SELECT * FROM classes WHERE niveau_id = ? ORDER BY nom");
    $stmt->execute([$niveau_id]);
    return $stmt->fetchAll();
}

function addClass($nom, $niveau_id)
{
    $pdo = getConnexion();
    $stmt = $pdo->prepare("INSERT INTO classes (nom, niveau_id) VALUES (?, ?)");
    return $stmt->execute([$nom, $niveau_id]);
}

function getClasseById($id)
{
    $pdo = getConnexion();
    $stmt = $pdo->prepare("SELECT c.*, n.nom AS niveau_nom FROM classes c JOIN niveaux n ON c.niveau_id = n.id WHERE c.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getClassesGroupedByNiveau()
{
    $niveaux = getAllNiveaux();
    $result = [];
    foreach ($niveaux as $n) {
        $result[$n['nom']] = getClassesByNiveau($n['id']);
    }
    return $result;
}

function deleteClasse($id)
{
    $pdo = getConnexion();
    $stmt = $pdo->prepare("DELETE FROM classes WHERE id = ?");
    return $stmt->execute([$id]);
}

// ============================================
// ÉTUDIANTS
// ============================================

function addEtudiant($matricule, $nom, $prenom, $email, $classe_id)
{
    $pdo = getConnexion();
    $stmt = $pdo->prepare("INSERT INTO etudiants (matricule, nom, prenom, email, classe_id) VALUES (?,?,?,?,?)");
    return $stmt->execute([$matricule, $nom, $prenom, $email, $classe_id]);
}

function getEtudiantsByClasse($classe_id)
{
    $pdo = getConnexion();
    $stmt = $pdo->prepare("SELECT * FROM etudiants WHERE classe_id = ? ORDER BY nom, prenom");
    $stmt->execute([$classe_id]);
    return $stmt->fetchAll();
}

function getAllEtudiants()
{
    $pdo = getConnexion();
    return $pdo->query("SELECT e.*, c.nom AS classe_nom, n.nom AS niveau_nom FROM etudiants e JOIN classes c ON e.classe_id = c.id JOIN niveaux n ON c.niveau_id = n.id ORDER BY e.nom")->fetchAll();
}

function getEtudiantById($id)
{
    $pdo = getConnexion();
    $stmt = $pdo->prepare("SELECT e.*, c.nom AS classe_nom, n.nom AS niveau_nom FROM etudiants e JOIN classes c ON e.classe_id = c.id JOIN niveaux n ON c.niveau_id = n.id WHERE e.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getEtudiantByMatricule($matricule)
{
    $pdo = getConnexion();
    $stmt = $pdo->prepare("SELECT * FROM etudiants WHERE matricule = ?");
    $stmt->execute([$matricule]);
    return $stmt->fetch();
}

function deleteEtudiant($id)
{
    $pdo = getConnexion();
    $stmt = $pdo->prepare("DELETE FROM etudiants WHERE id = ?");
    return $stmt->execute([$id]);
}

function updateEtudiant($id, $matricule, $nom, $prenom, $email, $classe_id)
{
    $pdo = getConnexion();
    $stmt = $pdo->prepare("UPDATE etudiants SET matricule=?, nom=?, prenom=?, email=?, classe_id=? WHERE id=?");
    return $stmt->execute([$matricule, $nom, $prenom, $email, $classe_id, $id]);
}

// ============================================
// MODULES
// ============================================

function getAllModules()
{
    $pdo = getConnexion();
    return $pdo->query("SELECT * FROM modules ORDER BY nom")->fetchAll();
}

function addModule($code, $nom)
{
    $pdo = getConnexion();
    $stmt = $pdo->prepare("INSERT INTO modules (code, nom) VALUES (?,?)");
    return $stmt->execute([$code, $nom]);
}

function getModulesByClasse($classe_id)
{
    $pdo = getConnexion();
    $stmt = $pdo->prepare("SELECT m.* FROM modules m JOIN classe_module cm ON m.id = cm.module_id WHERE cm.classe_id = ? ORDER BY m.nom");
    $stmt->execute([$classe_id]);
    return $stmt->fetchAll();
}

function addModuleToClasse($classe_id, $module_id)
{
    $pdo = getConnexion();
    $stmt = $pdo->prepare("INSERT IGNORE INTO classe_module (classe_id, module_id) VALUES (?,?)");
    return $stmt->execute([$classe_id, $module_id]);
}

function getModuleById($id)
{
    $pdo = getConnexion();
    $stmt = $pdo->prepare("SELECT * FROM modules WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// ============================================
// ÉVALUATIONS
// ============================================

function addEvaluation($etudiant_id, $module_id, $type_eval, $note, $date_eval)
{
    $pdo = getConnexion();
    $stmt = $pdo->prepare("INSERT INTO evaluations (etudiant_id, module_id, type_eval, note, date_eval) VALUES (?,?,?,?,?)");
    return $stmt->execute([$etudiant_id, $module_id, $type_eval, $note, $date_eval]);
}

function getEvaluationsByEtudiant($etudiant_id)
{
    $pdo = getConnexion();
    $stmt = $pdo->prepare("SELECT ev.*, m.nom AS module_nom, m.code AS module_code FROM evaluations ev JOIN modules m ON ev.module_id = m.id WHERE ev.etudiant_id = ? ORDER BY m.nom, ev.type_eval");
    $stmt->execute([$etudiant_id]);
    return $stmt->fetchAll();
}

function getEvaluationById($id)
{
    $pdo = getConnexion();
    $stmt = $pdo->prepare("SELECT * FROM evaluations WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function updateEvaluation($id, $note, $type_eval, $date_eval)
{
    $pdo = getConnexion();
    $stmt = $pdo->prepare("UPDATE evaluations SET note=?, type_eval=?, date_eval=? WHERE id=?");
    return $stmt->execute([$note, $type_eval, $date_eval, $id]);
}

function deleteEvaluation($id)
{
    $pdo = getConnexion();
    $stmt = $pdo->prepare("DELETE FROM evaluations WHERE id = ?");
    return $stmt->execute([$id]);
}

function getEvaluationByMatriculeAndModule($matricule, $code_module)
{
    $pdo = getConnexion();
    $stmt = $pdo->prepare("
        SELECT ev.* FROM evaluations ev
        JOIN etudiants e ON ev.etudiant_id = e.id
        JOIN modules m ON ev.module_id = m.id
        WHERE e.matricule = ? AND m.code = ?
    ");
    $stmt->execute([$matricule, $code_module]);
    return $stmt->fetchAll();
}

// ============================================
// CALCULS ET ANALYSES
// ============================================

/**
 * Calcule la moyenne d'un étudiant (hors TP)
 */
function calculerMoyenneEtudiant($etudiant_id)
{
    $pdo = getConnexion();
    $stmt = $pdo->prepare("SELECT AVG(note) FROM evaluations WHERE etudiant_id = ? AND type_eval IN ('devoir','examen')");
    $stmt->execute([$etudiant_id]);
    $moy = $stmt->fetchColumn();
    return $moy ? round($moy, 2) : 0;
}

/**
 * Calcule la moyenne générale d'une classe (hors TP)
 */
function calculerMoyenneClasse($classe_id)
{
    $pdo = getConnexion();
    $stmt = $pdo->prepare("
        SELECT AVG(ev.note) FROM evaluations ev
        JOIN etudiants e ON ev.etudiant_id = e.id
        WHERE e.classe_id = ? AND ev.type_eval IN ('devoir','examen')
    ");
    $stmt->execute([$classe_id]);
    $moy = $stmt->fetchColumn();
    return $moy ? round($moy, 2) : 0;
}

/**
 * Meilleur étudiant d'une classe
 */
function getMeilleurEtudiantClasse($classe_id)
{
    $pdo = getConnexion();
    $stmt = $pdo->prepare("
        SELECT e.*, AVG(ev.note) AS moyenne FROM etudiants e
        JOIN evaluations ev ON e.id = ev.etudiant_id
        WHERE e.classe_id = ? AND ev.type_eval IN ('devoir','examen')
        GROUP BY e.id
        ORDER BY moyenne DESC
        LIMIT 1
    ");
    $stmt->execute([$classe_id]);
    return $stmt->fetch();
}

/**
 * Meilleur étudiant d'un niveau
 */
function getMeilleurEtudiantNiveau($niveau_id)
{
    $pdo = getConnexion();
    $stmt = $pdo->prepare("
        SELECT e.*, c.nom AS classe_nom, AVG(ev.note) AS moyenne FROM etudiants e
        JOIN classes c ON e.classe_id = c.id
        JOIN evaluations ev ON e.id = ev.etudiant_id
        WHERE c.niveau_id = ? AND ev.type_eval IN ('devoir','examen')
        GROUP BY e.id
        ORDER BY moyenne DESC
        LIMIT 1
    ");
    $stmt->execute([$niveau_id]);
    return $stmt->fetch();
}

/**
 * Étudiants au-dessus de la moyenne de leur classe
 */
function getEtudiantsAuDessusMoyenneClasse($classe_id)
{
    $moy_classe = calculerMoyenneClasse($classe_id);
    $etudiants = getEtudiantsByClasse($classe_id);
    $result = [];
    foreach ($etudiants as $e) {
        $moy = calculerMoyenneEtudiant($e['id']);
        if ($moy > $moy_classe) {
            $e['moyenne'] = $moy;
            $result[] = $e;
        }
    }
    return $result;
}

/**
 * Statut de l'étudiant selon sa moyenne
 */
function getStatutEtudiant($moyenne)
{
    if ($moyenne >= 10) return ['statut' => 'Admis', 'class' => 'success'];
    if ($moyenne >= 5)  return ['statut' => 'Ajourné', 'class' => 'warning'];
    return ['statut' => 'Exclu', 'class' => 'danger'];
}

/**
 * Statistiques globales pour le tableau de bord
 */
function getStatistiques()
{
    $pdo = getConnexion();
    $stats = [];
    $stats['nb_niveaux']  = $pdo->query("SELECT COUNT(*) FROM niveaux")->fetchColumn();
    $stats['nb_classes']  = $pdo->query("SELECT COUNT(*) FROM classes")->fetchColumn();
    $stats['nb_etudiants'] = $pdo->query("SELECT COUNT(*) FROM etudiants")->fetchColumn();
    $stats['nb_modules']  = $pdo->query("SELECT COUNT(*) FROM modules")->fetchColumn();
    $stats['nb_evals']    = $pdo->query("SELECT COUNT(*) FROM evaluations")->fetchColumn();

    // Étudiants par niveau
    $stmt = $pdo->query("SELECT n.nom, COUNT(e.id) AS nb FROM niveaux n LEFT JOIN classes c ON c.niveau_id=n.id LEFT JOIN etudiants e ON e.classe_id=c.id GROUP BY n.id ORDER BY n.nom");
    $stats['etudiants_par_niveau'] = $stmt->fetchAll();

    // Admis / Ajournés / Exclus
    $all = getAllEtudiants();
    $admis = $ajournes = $exclus = 0;
    foreach ($all as $e) {
        $moy = calculerMoyenneEtudiant($e['id']);
        if ($moy >= 10) $admis++;
        elseif ($moy >= 5) $ajournes++;
        else $exclus++;
    }
    $stats['admis']    = $admis;
    $stats['ajournes'] = $ajournes;
    $stats['exclus']   = $exclus;
    return $stats;
}

// ============================================
// AUTHENTIFICATION
// ============================================
