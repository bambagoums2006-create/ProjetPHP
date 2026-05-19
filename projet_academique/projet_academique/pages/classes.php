<?php
$message = '';
$erreur = '';
$filtre_niveau = isset($_GET['niveau_id']) ? (int)$_GET['niveau_id'] : 0;

// Ajouter une classe
if (isset($_POST['action']) && $_POST['action'] === 'add_classe') {
    $nom = trim($_POST['nom'] ?? '');
    $niveau_id = (int)($_POST['niveau_id'] ?? 0);
    if ($nom && $niveau_id) {
        try {
            addClass($nom, $niveau_id);
            $message = "Classe \"$nom\" ajoutée avec succès.";
        } catch (Exception $e) {
            $erreur = "Erreur : " . $e->getMessage();
        }
    } else {
        $erreur = "Veuillez remplir tous les champs.";
    }
}

// Supprimer une classe
if (isset($_GET['delete'])) {
    deleteClasse((int)$_GET['delete']);
    $message = "Classe supprimée.";
}

$niveaux = getAllNiveaux();
$groupes = getClassesGroupedByNiveau();
?>
<h1 class="mt-4">Gestion des Classes</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Accueil</a></li>
    <li class="breadcrumb-item active">Classes</li>
</ol>

<?php if ($message): ?><div class="alert alert-success alert-dismissible fade show"><?= $message ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if ($erreur): ?><div class="alert alert-danger alert-dismissible fade show"><?= $erreur ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

<div class="row">
    <!-- Formulaire -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white"><i class="fas fa-plus me-2"></i>Ajouter une classe</div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="action" value="add_classe"/>
                    <div class="mb-3">
                        <label class="form-label">Nom de la classe</label>
                        <input type="text" name="nom" class="form-control" placeholder="Ex: GL, IAGE, CYBER" required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Niveau</label>
                        <select name="niveau_id" class="form-select" required>
                            <option value="">-- Choisir un niveau --</option>
                            <?php foreach ($niveaux as $n): ?>
                            <option value="<?= $n['id'] ?>" <?= ($filtre_niveau == $n['id']) ? 'selected' : '' ?>><?= htmlspecialchars($n['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save me-2"></i>Enregistrer</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Liste par niveau -->
    <div class="col-md-8 mb-4">
        <div class="card shadow-sm">
            <div class="card-header"><i class="fas fa-chalkboard me-2"></i>Classes regroupées par niveau</div>
            <div class="card-body">
                <?php foreach ($groupes as $niveau_nom => $classes): ?>
                <h6 class="text-primary mt-3 mb-2 border-bottom pb-1"><i class="fas fa-layer-group me-2"></i><?= htmlspecialchars($niveau_nom) ?></h6>
                <?php if (empty($classes)): ?>
                    <p class="text-muted ms-3"><em>Aucune classe dans ce niveau</em></p>
                <?php else: ?>
                <div class="row mb-3">
                    <?php foreach ($classes as $c):
                        $etudiants = getEtudiantsByClasse($c['id']);
                    ?>
                    <div class="col-md-6 mb-2">
                        <div class="card border h-100">
                            <div class="card-body py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?= htmlspecialchars($c['nom']) ?></strong>
                                        <div class="text-muted small"><?= count($etudiants) ?> étudiant(s)</div>
                                    </div>
                                    <div class="d-flex gap-1">
                                        <a href="index.php?page=etudiants&classe_id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-primary" title="Voir étudiants"><i class="fas fa-users"></i></a>
                                        <a href="index.php?page=modules&classe_id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Voir modules"><i class="fas fa-book"></i></a>
                                        <a href="index.php?page=classes&delete=<?= $c['id'] ?>" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="return confirm('Supprimer cette classe ?')"><i class="fas fa-trash"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
