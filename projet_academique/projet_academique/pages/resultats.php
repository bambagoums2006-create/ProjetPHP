<?php
$etudiant_id  = isset($_GET['etudiant_id']) ? (int)$_GET['etudiant_id'] : 0;
$classe_id    = isset($_GET['classe_id']) ? (int)$_GET['classe_id'] : 0;
$tous_etudiants = getAllEtudiants();
$classes = getAllClasses();

$etudiant = $etudiant_id ? getEtudiantById($etudiant_id) : null;
?>
<h1 class="mt-4">Résultats & Bulletins</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="index.php?page=dashboard">Accueil</a></li>
    <li class="breadcrumb-item active">Résultats</li>
</ol>

<!-- Sélection -->
<div class="card shadow-sm mb-4">
    <div class="card-body py-2">
        <div class="row g-2">
            <div class="col-md-5">
                <form method="GET">
                    <input type="hidden" name="page" value="resultats"/>
                    <label class="form-label mb-1">Bulletin d'un étudiant</label>
                    <div class="input-group input-group-sm">
                        <select name="etudiant_id" class="form-select">
                            <option value="">-- Choisir un étudiant --</option>
                            <?php foreach ($tous_etudiants as $e): ?>
                            <option value="<?= $e['id'] ?>" <?= ($etudiant_id==$e['id'])?'selected':'' ?>>
                                <?= htmlspecialchars($e['matricule'].' - '.$e['prenom'].' '.$e['nom']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>
            <div class="col-md-5">
                <form method="GET">
                    <input type="hidden" name="page" value="resultats"/>
                    <label class="form-label mb-1">Résultats d'une classe</label>
                    <div class="input-group input-group-sm">
                        <select name="classe_id" class="form-select">
                            <option value="">-- Choisir une classe --</option>
                            <?php foreach ($classes as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= ($classe_id==$c['id'])?'selected':'' ?>>
                                <?= htmlspecialchars($c['niveau_nom'].' - '.$c['nom']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- BULLETIN ÉTUDIANT -->
<?php if ($etudiant): 
    $evaluations = getEvaluationsByEtudiant($etudiant['id']);
    $moy_generale = calculerMoyenneEtudiant($etudiant['id']);
    $statut = getStatutEtudiant($moy_generale);
    // Regrouper par module
    $par_module = [];
    foreach ($evaluations as $ev) {
        $par_module[$ev['module_nom']][$ev['type_eval']][] = $ev['note'];
    }
?>
<div class="card shadow-sm mb-4" id="bulletin">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <span><i class="fas fa-file-alt me-2"></i>Bulletin de notes</span>
        <button onclick="window.print()" class="btn btn-sm btn-light"><i class="fas fa-print me-2"></i>Imprimer</button>
    </div>
    <div class="card-body">
        <!-- En-tête bulletin -->
        <div class="text-center mb-4 border-bottom pb-3">
            <h4 class="text-primary mb-1"><i class="fas fa-graduation-cap me-2"></i>Institut Supérieur d'Informatique</h4>
            <h5>BULLETIN DE NOTES</h5>
            <p class="mb-0">Année académique 2025-2026</p>
        </div>
        <div class="row mb-4">
            <div class="col-md-6">
                <table class="table table-sm table-borderless">
                    <tr><th>Matricule :</th><td><code><?= htmlspecialchars($etudiant['matricule']) ?></code></td></tr>
                    <tr><th>Nom & Prénom :</th><td><?= htmlspecialchars($etudiant['prenom'].' '.$etudiant['nom']) ?></td></tr>
                    <tr><th>Email :</th><td><?= htmlspecialchars($etudiant['email'] ?? '-') ?></td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm table-borderless">
                    <tr><th>Niveau :</th><td><?= htmlspecialchars($etudiant['niveau_nom']) ?></td></tr>
                    <tr><th>Classe :</th><td><?= htmlspecialchars($etudiant['classe_nom']) ?></td></tr>
                    <tr><th>Date :</th><td><?= date('d/m/Y') ?></td></tr>
                </table>
            </div>
        </div>

        <!-- Tableau notes -->
        <table class="table table-bordered table-sm">
            <thead class="table-dark">
                <tr>
                    <th>Module</th>
                    <th class="text-center">Devoir(s)</th>
                    <th class="text-center">Examen(s)</th>
                    <th class="text-center">TP(s)</th>
                    <th class="text-center">Moy. Module<br><small>(hors TP)</small></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = 0; $count = 0;
                foreach ($par_module as $mod_nom => $types): 
                    $devoirs = $types['devoir'] ?? [];
                    $examens = $types['examen'] ?? [];
                    $tps     = $types['tp'] ?? [];
                    $notes_calc = array_merge($devoirs, $examens);
                    $moy_mod = count($notes_calc) > 0 ? round(array_sum($notes_calc)/count($notes_calc), 2) : null;
                    if ($moy_mod !== null) { $total += $moy_mod; $count++; }
                ?>
                <tr>
                    <td><?= htmlspecialchars($mod_nom) ?></td>
                    <td class="text-center"><?= implode(', ', array_map(fn($n)=>number_format($n,2), $devoirs)) ?: '-' ?></td>
                    <td class="text-center"><?= implode(', ', array_map(fn($n)=>number_format($n,2), $examens)) ?: '-' ?></td>
                    <td class="text-center text-muted"><?= implode(', ', array_map(fn($n)=>number_format($n,2), $tps)) ?: '-' ?></td>
                    <td class="text-center fw-bold <?= $moy_mod >= 10 ? 'text-success' : 'text-danger' ?>">
                        <?= $moy_mod !== null ? number_format($moy_mod, 2).'/20' : '-' ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="table-dark">
                    <th colspan="4" class="text-end">Moyenne générale (hors TP) :</th>
                    <th class="text-center fs-5 <?= $moy_generale >= 10 ? 'text-success' : 'text-danger' ?>">
                        <?= number_format($moy_generale, 2) ?>/20
                    </th>
                </tr>
            </tfoot>
        </table>

        <!-- Résultat final -->
        <div class="text-center mt-4 p-3 border rounded bg-light">
            <h4>Résultat : <span class="badge bg-<?= $statut['class'] ?> fs-5"><?= $statut['statut'] ?></span></h4>
            <?php if ($statut['statut'] === 'Admis'): ?>
                <p class="text-success mb-0"><i class="fas fa-check-circle me-2"></i>Félicitations ! L'étudiant est admis.</p>
            <?php elseif ($statut['statut'] === 'Ajourné'): ?>
                <p class="text-warning mb-0"><i class="fas fa-clock me-2"></i>L'étudiant est ajourné. Session de rattrapage conseillée.</p>
            <?php else: ?>
                <p class="text-danger mb-0"><i class="fas fa-times-circle me-2"></i>L'étudiant est exclu (moyenne inférieure à 5).</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- RÉSULTATS D'UNE CLASSE -->
<?php if ($classe_id):
    $classe_info = getClasseById($classe_id);
    $etudiants_classe = getEtudiantsByClasse($classe_id);
    $moy_classe = calculerMoyenneClasse($classe_id);
    $meilleur = getMeilleurEtudiantClasse($classe_id);
    $au_dessus = getEtudiantsAuDessusMoyenneClasse($classe_id);
    $admis = $ajournes = $exclus = 0;
    foreach ($etudiants_classe as $e) {
        $m = calculerMoyenneEtudiant($e['id']);
        if ($m >= 10) $admis++;
        elseif ($m >= 5) $ajournes++;
        else $exclus++;
    }
?>
<div class="card shadow-sm mb-4">
    <div class="card-header bg-dark text-white">
        <i class="fas fa-chart-bar me-2"></i>Résultats : <?= htmlspecialchars($classe_info['niveau_nom'].' - '.$classe_info['nom']) ?>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-3"><div class="card text-center border-primary"><div class="card-body py-2"><div class="text-primary fs-4 fw-bold"><?= number_format($moy_classe,2) ?>/20</div><div>Moy. générale</div></div></div></div>
            <div class="col-md-3"><div class="card text-center border-success"><div class="card-body py-2"><div class="text-success fs-4 fw-bold"><?= $admis ?></div><div>Admis</div></div></div></div>
            <div class="col-md-3"><div class="card text-center border-warning"><div class="card-body py-2"><div class="text-warning fs-4 fw-bold"><?= $ajournes ?></div><div>Ajournés</div></div></div></div>
            <div class="col-md-3"><div class="card text-center border-danger"><div class="card-body py-2"><div class="text-danger fs-4 fw-bold"><?= $exclus ?></div><div>Exclus</div></div></div></div>
        </div>

        <?php if ($meilleur): ?>
        <div class="alert alert-success"><i class="fas fa-trophy me-2"></i><strong>Meilleur étudiant :</strong> <?= htmlspecialchars($meilleur['prenom'].' '.$meilleur['nom']) ?> avec <strong><?= number_format($meilleur['moyenne'],2) ?>/20</strong></div>
        <?php endif; ?>

        <table class="table table-hover table-sm datatable">
            <thead class="table-dark">
                <tr><th>Matricule</th><th>Nom & Prénom</th><th>Moyenne</th><th>Statut</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php foreach ($etudiants_classe as $e):
                    $moy = calculerMoyenneEtudiant($e['id']);
                    $st  = getStatutEtudiant($moy);
                ?>
                <tr>
                    <td><code><?= htmlspecialchars($e['matricule']) ?></code></td>
                    <td><?= htmlspecialchars($e['prenom'].' '.$e['nom']) ?></td>
                    <td><?= $moy > 0 ? number_format($moy,2).'/20' : '<span class="text-muted">-</span>' ?></td>
                    <td><span class="badge bg-<?= $st['class'] ?>"><?= $st['statut'] ?></span></td>
                    <td><a href="index.php?page=resultats&etudiant_id=<?= $e['id'] ?>" class="btn btn-sm btn-outline-primary py-0"><i class="fas fa-file-alt"></i> Bulletin</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<style>
@media print {
    .sb-topnav, #layoutSidenav_nav, .breadcrumb, .card:not(#bulletin), nav, form { display: none !important; }
    #bulletin { border: none !important; box-shadow: none !important; }
    .btn { display: none !important; }
}
</style>
