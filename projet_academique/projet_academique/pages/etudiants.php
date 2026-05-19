<?php
$message = '';
$erreur = '';
$filtre_classe = isset($_GET['classe_id']) ? (int)$_GET['classe_id'] : 0;

// Ajouter étudiant
if (isset($_POST['action']) && $_POST['action'] === 'add_etudiant') {
    $matricule = trim($_POST['matricule'] ?? '');
    $nom       = trim($_POST['nom'] ?? '');
    $prenom    = trim($_POST['prenom'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $classe_id = (int)($_POST['classe_id'] ?? 0);
    if ($matricule && $nom && $prenom && $classe_id) {
        try {
            addEtudiant($matricule, $nom, $prenom, $email, $classe_id);
            $message = "Étudiant \"$prenom $nom\" inscrit avec succès.";
        } catch (Exception $e) {
            $erreur = "Erreur : " . $e->getMessage();
        }
    } else {
        $erreur = "Veuillez remplir tous les champs obligatoires.";
    }
}

// Modifier étudiant
if (isset($_POST['action']) && $_POST['action'] === 'edit_etudiant') {
    updateEtudiant($_POST['id'], $_POST['matricule'], $_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['classe_id']);
    $message = "Étudiant modifié.";
}

// Supprimer
if (isset($_GET['delete'])) {
    deleteEtudiant((int)$_GET['delete']);
    $message = "Étudiant supprimé.";
}

$classes = getAllClasses();
$niveaux = getAllNiveaux();

// Liste étudiants selon filtre
if ($filtre_classe) {
    $etudiants = getEtudiantsByClasse($filtre_classe);
    $classe_info = getClasseById($filtre_classe);
    $meilleur = getMeilleurEtudiantClasse($filtre_classe);
    $moy_classe = calculerMoyenneClasse($filtre_classe);
    $au_dessus = getEtudiantsAuDessusMoyenneClasse($filtre_classe);
} else {
    $etudiants = getAllEtudiants();
    $classe_info = null;
}
?>
<h1 class="mt-4">Gestion des Étudiants</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Accueil</a></li>
    <li class="breadcrumb-item active">Étudiants</li>
</ol>

<?php if ($message): ?><div class="alert alert-success alert-dismissible fade show"><?= $message ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if ($erreur): ?><div class="alert alert-danger alert-dismissible fade show"><?= $erreur ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

<!-- Filtre par classe -->
<div class="card shadow-sm mb-4">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <input type="hidden" name="page" value="etudiants"/>
            <div class="col-md-4">
                <label class="form-label mb-1">Filtrer par classe</label>
                <select name="classe_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Toutes les classes</option>
                    <?php foreach ($classes as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $filtre_classe == $c['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['niveau_nom'].' - '.$c['nom']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>
</div>

<!-- Stats de classe si filtre actif -->
<?php if ($filtre_classe && $classe_info): ?>
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-info text-white shadow-sm">
            <div class="card-body text-center">
                <i class="fas fa-chalkboard fa-2x mb-2"></i>
                <h5><?= htmlspecialchars($classe_info['niveau_nom'].' - '.$classe_info['nom']) ?></h5>
                <p class="mb-0"><?= count($etudiants) ?> étudiant(s)</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-primary text-white shadow-sm">
            <div class="card-body text-center">
                <i class="fas fa-chart-line fa-2x mb-2"></i>
                <h5>Moyenne de classe</h5>
                <h3><?= number_format($moy_classe, 2) ?>/20</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white shadow-sm">
            <div class="card-body text-center">
                <i class="fas fa-trophy fa-2x mb-2"></i>
                <h5>Meilleur étudiant</h5>
                <?php if ($meilleur): ?>
                    <p class="mb-0 fw-bold"><?= htmlspecialchars($meilleur['prenom'].' '.$meilleur['nom']) ?></p>
                    <p class="mb-0"><?= number_format($meilleur['moyenne'], 2) ?>/20</p>
                <?php else: ?>
                    <p class="mb-0">-</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php if (!empty($au_dessus)): ?>
<div class="alert alert-info mb-4">
    <strong><i class="fas fa-arrow-up me-2"></i>Au-dessus de la moyenne :</strong>
    <?php foreach ($au_dessus as $e): ?>
        <span class="badge bg-info text-dark ms-1"><?= htmlspecialchars($e['prenom'].' '.$e['nom']) ?> (<?= number_format($e['moyenne'],2) ?>)</span>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<?php endif; ?>

<div class="row">
    <!-- Formulaire -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white"><i class="fas fa-plus me-2"></i>Inscrire un étudiant</div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="action" value="add_etudiant"/>
                    <div class="mb-2">
                        <label class="form-label">Matricule <span class="text-danger">*</span></label>
                        <input type="text" name="matricule" class="form-control form-control-sm" placeholder="Ex: L2GL006" required/>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" name="nom" class="form-control form-control-sm" required/>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Prénom <span class="text-danger">*</span></label>
                        <input type="text" name="prenom" class="form-control form-control-sm" required/>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control form-control-sm" placeholder="email@isi.sn"/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Classe <span class="text-danger">*</span></label>
                        <select name="classe_id" class="form-select form-select-sm" required>
                            <option value="">-- Choisir --</option>
                            <?php foreach ($classes as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= ($filtre_classe == $c['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['niveau_nom'].' - '.$c['nom']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-save me-2"></i>Inscrire</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Liste -->
    <div class="col-md-8 mb-4">
        <div class="card shadow-sm">
            <div class="card-header">
                <i class="fas fa-users me-2"></i>
                Liste des étudiants 
                <?php if ($filtre_classe && $classe_info): ?>
                    — <?= htmlspecialchars($classe_info['niveau_nom'].' '.$classe_info['nom']) ?>
                <?php endif; ?>
                <span class="badge bg-secondary ms-2"><?= count($etudiants) ?></span>
            </div>
            <div class="card-body p-0">
                <?php if (empty($etudiants)): ?>
                    <p class="text-muted text-center p-4">Aucun étudiant trouvé</p>
                <?php else: ?>
                <div class="table-responsive">
                <table class="table table-hover table-sm datatable mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Matricule</th>
                            <th>Nom & Prénom</th>
                            <?php if (!$filtre_classe): ?><th>Classe</th><?php endif; ?>
                            <th>Moyenne</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($etudiants as $e):
                            $moy = calculerMoyenneEtudiant($e['id']);
                            $statut = getStatutEtudiant($moy);
                        ?>
                        <tr>
                            <td><code><?= htmlspecialchars($e['matricule']) ?></code></td>
                            <td><?= htmlspecialchars($e['prenom'].' '.$e['nom']) ?></td>
                            <?php if (!$filtre_classe): ?>
                            <td><small><?= htmlspecialchars($e['niveau_nom'].' - '.$e['classe_nom']) ?></small></td>
                            <?php endif; ?>
                            <td><?= $moy > 0 ? number_format($moy,2) : '<span class="text-muted">-</span>' ?></td>
                            <td><span class="badge bg-<?= $statut['class'] ?>"><?= $statut['statut'] ?></span></td>
                            <td>
                                <a href="index.php?page=evaluations&etudiant_id=<?= $e['id'] ?>" class="btn btn-xs btn-sm btn-outline-primary py-0 px-1" title="Évaluations"><i class="fas fa-clipboard-list"></i></a>
                                <a href="index.php?page=resultats&etudiant_id=<?= $e['id'] ?>" class="btn btn-xs btn-sm btn-outline-success py-0 px-1" title="Bulletin"><i class="fas fa-file-alt"></i></a>
                                <a href="index.php?page=etudiants&delete=<?= $e['id'] ?>" class="btn btn-xs btn-sm btn-outline-danger py-0 px-1" title="Supprimer" onclick="return confirm('Supprimer cet étudiant ?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
