<?php
$message = '';
$erreur = '';
$filtre_classe = isset($_GET['classe_id']) ? (int)$_GET['classe_id'] : 0;

// Ajouter module
if (isset($_POST['action']) && $_POST['action'] === 'add_module') {
    $code = trim($_POST['code'] ?? '');
    $nom  = trim($_POST['nom'] ?? '');
    if ($code && $nom) {
        try {
            addModule($code, $nom);
            $message = "Module \"$nom\" créé.";
        } catch (Exception $e) {
            $erreur = "Erreur : " . $e->getMessage();
        }
    } else {
        $erreur = "Remplissez le code et le nom.";
    }
}

// Affecter module à une classe
if (isset($_POST['action']) && $_POST['action'] === 'assign_module') {
    $classe_id = (int)$_POST['classe_id'];
    $module_id = (int)$_POST['module_id'];
    if ($classe_id && $module_id) {
        addModuleToClasse($classe_id, $module_id);
        $message = "Module affecté à la classe.";
    }
}

$classes = getAllClasses();
$modules = getAllModules();
?>
<h1 class="mt-4">Gestion des Modules</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Accueil</a></li>
    <li class="breadcrumb-item active">Modules</li>
</ol>

<?php if ($message): ?><div class="alert alert-success alert-dismissible fade show"><?= $message ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if ($erreur): ?><div class="alert alert-danger alert-dismissible fade show"><?= $erreur ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

<div class="row">
    <div class="col-md-4">
        <!-- Créer module -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white"><i class="fas fa-plus me-2"></i>Créer un module</div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="action" value="add_module"/>
                    <div class="mb-2">
                        <label class="form-label">Code du module</label>
                        <input type="text" name="code" class="form-control form-control-sm" placeholder="Ex: INF301" required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nom du module</label>
                        <input type="text" name="nom" class="form-control form-control-sm" placeholder="Ex: Intelligence Artificielle" required/>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-save me-2"></i>Créer</button>
                </form>
            </div>
        </div>

        <!-- Affecter module à classe -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-secondary text-white"><i class="fas fa-link me-2"></i>Affecter module à une classe</div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="action" value="assign_module"/>
                    <div class="mb-2">
                        <label class="form-label">Classe</label>
                        <select name="classe_id" class="form-select form-select-sm" required>
                            <option value="">-- Choisir une classe --</option>
                            <?php foreach ($classes as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= ($filtre_classe == $c['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['niveau_nom'].' - '.$c['nom']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Module</label>
                        <select name="module_id" class="form-select form-select-sm" required>
                            <option value="">-- Choisir un module --</option>
                            <?php foreach ($modules as $m): ?>
                            <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['code'].' - '.$m['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-secondary btn-sm w-100"><i class="fas fa-link me-2"></i>Affecter</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Tous les modules -->
        <div class="card shadow-sm mb-4">
            <div class="card-header"><i class="fas fa-book me-2"></i>Tous les modules <span class="badge bg-secondary"><?= count($modules) ?></span></div>
            <div class="card-body p-0">
                <table class="table table-hover table-sm datatable mb-0">
                    <thead class="table-dark">
                        <tr><th>Code</th><th>Nom</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($modules as $m): ?>
                        <tr>
                            <td><code><?= htmlspecialchars($m['code']) ?></code></td>
                            <td><?= htmlspecialchars($m['nom']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modules par classe -->
        <?php if ($filtre_classe): ?>
        <?php $classe_info = getClasseById($filtre_classe); ?>
        <div class="card shadow-sm">
            <div class="card-header"><i class="fas fa-chalkboard me-2"></i>Modules de <?= htmlspecialchars($classe_info['niveau_nom'].' - '.$classe_info['nom']) ?></div>
            <div class="card-body">
                <?php $mods = getModulesByClasse($filtre_classe); ?>
                <?php if (empty($mods)): ?>
                    <p class="text-muted">Aucun module affecté.</p>
                <?php else: ?>
                    <?php foreach ($mods as $m): ?>
                    <span class="badge bg-primary me-2 mb-2 p-2"><?= htmlspecialchars($m['code'].' - '.$m['nom']) ?></span>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
