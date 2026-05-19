<?php
$message = '';
$erreur = '';
$etudiant_id = isset($_GET['etudiant_id']) ? (int)$_GET['etudiant_id'] : 0;

// Recherche par matricule et code module
$search_result = null;
if (isset($_POST['action']) && $_POST['action'] === 'search_eval') {
    $matricule   = trim($_POST['matricule'] ?? '');
    $code_module = trim($_POST['code_module'] ?? '');
    if ($matricule && $code_module) {
        $search_result = getEvaluationByMatriculeAndModule($matricule, $code_module);
        if (empty($search_result)) {
            $erreur = "Aucune évaluation trouvée pour ce matricule et code module.";
        }
    }
}

// Ajouter évaluation
if (isset($_POST['action']) && $_POST['action'] === 'add_eval') {
    $eid      = (int)($_POST['etudiant_id'] ?? 0);
    $mod_id   = (int)($_POST['module_id'] ?? 0);
    $type     = $_POST['type_eval'] ?? '';
    $note     = (float)($_POST['note'] ?? 0);
    $date_e   = $_POST['date_eval'] ?? date('Y-m-d');
    if ($eid && $mod_id && $type && $date_e) {
        addEvaluation($eid, $mod_id, $type, $note, $date_e);
        $message = "Évaluation enregistrée.";
        $etudiant_id = $eid;
    } else {
        $erreur = "Remplissez tous les champs.";
    }
}

// Modifier évaluation
if (isset($_POST['action']) && $_POST['action'] === 'edit_eval') {
    updateEvaluation($_POST['eval_id'], $_POST['note'], $_POST['type_eval'], $_POST['date_eval']);
    $message = "Évaluation modifiée.";
}

// Supprimer évaluation
if (isset($_GET['delete_eval'])) {
    $eval = getEvaluationById((int)$_GET['delete_eval']);
    if ($eval) {
        $etudiant_id = $eval['etudiant_id'];
        deleteEvaluation((int)$_GET['delete_eval']);
        $message = "Évaluation supprimée.";
    }
}

$tous_etudiants = getAllEtudiants();
$etudiant = $etudiant_id ? getEtudiantById($etudiant_id) : null;
$evaluations = $etudiant_id ? getEvaluationsByEtudiant($etudiant_id) : [];
$modules_etudiant = $etudiant ? getModulesByClasse($etudiant['classe_id']) : [];
?>
<h1 class="mt-4">Gestion des Évaluations</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Accueil</a></li>
    <li class="breadcrumb-item active">Évaluations</li>
</ol>

<?php if ($message): ?><div class="alert alert-success alert-dismissible fade show"><?= $message ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if ($erreur): ?><div class="alert alert-danger alert-dismissible fade show"><?= $erreur ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

<!-- Sélectionner étudiant -->
<div class="card shadow-sm mb-4">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <input type="hidden" name="page" value="evaluations"/>
            <div class="col-md-6">
                <label class="form-label mb-1">Sélectionner un étudiant</label>
                <select name="etudiant_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Choisir un étudiant --</option>
                    <?php foreach ($tous_etudiants as $e): ?>
                    <option value="<?= $e['id'] ?>" <?= ($etudiant_id == $e['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($e['matricule'].' - '.$e['prenom'].' '.$e['nom'].' ('.$e['niveau_nom'].' '.$e['classe_nom'].')') ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>
</div>

<!-- Recherche par matricule + code module -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-dark text-white"><i class="fas fa-search me-2"></i>Rechercher une évaluation (par matricule + code module)</div>
    <div class="card-body">
        <form method="POST" class="row g-2">
            <input type="hidden" name="action" value="search_eval"/>
            <div class="col-md-4">
                <input type="text" name="matricule" class="form-control form-control-sm" placeholder="Matricule étudiant (ex: L2GL001)" required/>
            </div>
            <div class="col-md-4">
                <input type="text" name="code_module" class="form-control form-control-sm" placeholder="Code module (ex: INF201)" required/>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-dark btn-sm w-100"><i class="fas fa-search me-1"></i>Rechercher</button>
            </div>
        </form>
        <?php if ($search_result): ?>
        <div class="mt-3">
            <table class="table table-sm table-bordered">
                <thead class="table-dark"><tr><th>ID</th><th>Type</th><th>Note</th><th>Date</th><th>Action</th></tr></thead>
                <tbody>
                <?php foreach ($search_result as $sr): ?>
                <tr>
                    <td><?= $sr['id'] ?></td>
                    <td><?= ucfirst($sr['type_eval']) ?></td>
                    <td><?= $sr['note'] ?>/20</td>
                    <td><?= $sr['date_eval'] ?></td>
                    <td>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="action" value="edit_eval"/>
                            <input type="hidden" name="eval_id" value="<?= $sr['id'] ?>"/>
                            <input type="number" name="note" value="<?= $sr['note'] ?>" min="0" max="20" step="0.25" class="form-control form-control-sm d-inline" style="width:80px"/>
                            <select name="type_eval" class="form-select form-select-sm d-inline" style="width:100px">
                                <option value="devoir" <?= $sr['type_eval']=='devoir'?'selected':'' ?>>Devoir</option>
                                <option value="examen" <?= $sr['type_eval']=='examen'?'selected':'' ?>>Examen</option>
                                <option value="tp" <?= $sr['type_eval']=='tp'?'selected':'' ?>>TP</option>
                            </select>
                            <input type="date" name="date_eval" value="<?= $sr['date_eval'] ?>" class="form-control form-control-sm d-inline" style="width:140px"/>
                            <button type="submit" class="btn btn-sm btn-success py-0"><i class="fas fa-save"></i></button>
                        </form>
                        <a href="index.php?page=evaluations&delete_eval=<?= $sr['id'] ?>" class="btn btn-sm btn-danger py-0" onclick="return confirm('Supprimer ?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php if ($etudiant): ?>
<div class="row">
    <!-- Formulaire ajout évaluation -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-plus me-2"></i>Ajouter une évaluation<br>
                <small><?= htmlspecialchars($etudiant['prenom'].' '.$etudiant['nom']) ?></small>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="action" value="add_eval"/>
                    <input type="hidden" name="etudiant_id" value="<?= $etudiant['id'] ?>"/>
                    <div class="mb-2">
                        <label class="form-label">Module</label>
                        <select name="module_id" class="form-select form-select-sm" required>
                            <option value="">-- Choisir --</option>
                            <?php foreach ($modules_etudiant as $m): ?>
                            <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['code'].' - '.$m['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Type</label>
                        <select name="type_eval" class="form-select form-select-sm" required>
                            <option value="devoir">Devoir</option>
                            <option value="examen">Examen</option>
                            <option value="tp">TP</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Note /20</label>
                        <input type="number" name="note" class="form-control form-control-sm" min="0" max="20" step="0.25" required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" name="date_eval" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>" required/>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-save me-2"></i>Enregistrer</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Liste évaluations -->
    <div class="col-md-8 mb-4">
        <div class="card shadow-sm">
            <div class="card-header">
                <i class="fas fa-clipboard-list me-2"></i>Évaluations de 
                <strong><?= htmlspecialchars($etudiant['prenom'].' '.$etudiant['nom']) ?></strong>
                <span class="badge bg-secondary ms-2"><?= count($evaluations) ?></span>
                <a href="index.php?page=resultats&etudiant_id=<?= $etudiant['id'] ?>" class="btn btn-sm btn-success float-end"><i class="fas fa-file-alt me-1"></i>Bulletin</a>
            </div>
            <div class="card-body p-0">
                <?php
                $moy = calculerMoyenneEtudiant($etudiant['id']);
                $statut = getStatutEtudiant($moy);
                ?>
                <div class="p-3 bg-light border-bottom">
                    <strong>Moyenne (hors TP) :</strong> 
                    <span class="fs-5 fw-bold text-<?= $statut['class'] ?>"><?= number_format($moy, 2) ?>/20</span>
                    <span class="badge bg-<?= $statut['class'] ?> ms-2"><?= $statut['statut'] ?></span>
                </div>
                <?php if (empty($evaluations)): ?>
                    <p class="text-muted text-center p-4">Aucune évaluation enregistrée</p>
                <?php else: ?>
                <table class="table table-hover table-sm mb-0">
                    <thead class="table-dark">
                        <tr><th>Module</th><th>Type</th><th>Note</th><th>Date</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($evaluations as $ev): ?>
                        <tr>
                            <td><small><?= htmlspecialchars($ev['module_code'].' - '.$ev['module_nom']) ?></small></td>
                            <td>
                                <span class="badge bg-<?= $ev['type_eval']=='examen' ? 'danger' : ($ev['type_eval']=='devoir' ? 'primary' : 'secondary') ?>">
                                    <?= ucfirst($ev['type_eval']) ?>
                                </span>
                            </td>
                            <td>
                                <strong class="text-<?= $ev['note'] >= 10 ? 'success' : 'danger' ?>">
                                    <?= number_format($ev['note'], 2) ?>/20
                                </strong>
                            </td>
                            <td><small><?= $ev['date_eval'] ?></small></td>
                            <td>
                                <a href="index.php?page=evaluations&etudiant_id=<?= $etudiant['id'] ?>&delete_eval=<?= $ev['id'] ?>" 
                                   class="btn btn-sm btn-outline-danger py-0 px-1" onclick="return confirm('Supprimer ?')">
                                    <i class="fas fa-trash"></i>
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
<?php endif; ?>
