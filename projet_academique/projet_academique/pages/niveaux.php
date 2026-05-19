<?php
$message = '';
$erreur = '';

// Ajouter un niveau
if (isset($_POST['action']) && $_POST['action'] === 'add_niveau') {
    $nom = trim($_POST['nom'] ?? '');
    if ($nom) {
        try {
            addNiveau($nom);
            $message = "Niveau \"$nom\" ajouté avec succès.";
        } catch (Exception $e) {
            $erreur = "Ce niveau existe déjà ou erreur : " . $e->getMessage();
        }
    } else {
        $erreur = "Veuillez entrer un nom de niveau.";
    }
}

$niveaux = getAllNiveaux();
?>
<h1 class="mt-4">Gestion des Niveaux</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Accueil</a></li>
    <li class="breadcrumb-item active">Niveaux</li>
</ol>

<?php if ($message): ?><div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check me-2"></i><?= $message ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if ($erreur): ?><div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-times me-2"></i><?= $erreur ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

<div class="row">
    <!-- Formulaire ajout -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white"><i class="fas fa-plus me-2"></i>Ajouter un niveau</div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="action" value="add_niveau"/>
                    <div class="mb-3">
                        <label class="form-label">Nom du niveau</label>
                        <input type="text" name="nom" class="form-control" placeholder="Ex: Licence 3" required/>
                        <div class="form-text">Exemples : Licence 1, Licence 2, Master 1, Master 2</div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save me-2"></i>Enregistrer</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Liste des niveaux -->
    <div class="col-md-8 mb-4">
        <div class="card shadow-sm">
            <div class="card-header"><i class="fas fa-layer-group me-2"></i>Liste des niveaux (<?= count($niveaux) ?>)</div>
            <div class="card-body">
                <?php if (empty($niveaux)): ?>
                    <p class="text-muted text-center"><i class="fas fa-inbox fa-3x d-block mb-2"></i>Aucun niveau enregistré</p>
                <?php else: ?>
                <table class="table table-hover datatable">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Nb Classes</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($niveaux as $i => $n):
                            $classes = getClassesByNiveau($n['id']);
                            $sans_classe = empty($classes);
                        ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><strong><?= htmlspecialchars($n['nom']) ?></strong></td>
                            <td><span class="badge bg-secondary"><?= count($classes) ?></span></td>
                            <td>
                                <?php if ($sans_classe): ?>
                                    <span class="badge bg-warning text-dark"><i class="fas fa-exclamation-triangle me-1"></i>Sans classe</span>
                                <?php else: ?>
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>OK</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="index.php?page=classes&niveau_id=<?= $n['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-chalkboard"></i> Voir classes
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
