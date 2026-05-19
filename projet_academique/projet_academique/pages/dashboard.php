<?php
$stats = getStatistiques();
$niveaux = getAllNiveaux();
?>
<h1 class="mt-4">Tableau de bord</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Accueil</li>
</ol>

<!-- Cartes statistiques -->
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-stat card-stat-primary h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Niveaux</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['nb_niveaux'] ?></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-layer-group fa-2x text-primary opacity-50"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-stat card-stat-success h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Classes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['nb_classes'] ?></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-chalkboard fa-2x text-success opacity-50"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-stat card-stat-warning h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Étudiants</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['nb_etudiants'] ?></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-user-graduate fa-2x text-warning opacity-50"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-stat card-stat-danger h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Modules</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['nb_modules'] ?></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-book fa-2x text-danger opacity-50"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Résultats globaux -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-success text-white"><i class="fas fa-check-circle me-2"></i>Admis (moy ≥ 10)</div>
            <div class="card-body text-center"><h2 class="display-4 text-success"><?= $stats['admis'] ?></h2></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-warning text-dark"><i class="fas fa-clock me-2"></i>Ajournés (5 ≤ moy < 10)</div>
            <div class="card-body text-center"><h2 class="display-4 text-warning"><?= $stats['ajournes'] ?></h2></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-danger text-white"><i class="fas fa-times-circle me-2"></i>Exclus (moy < 5)</div>
            <div class="card-body text-center"><h2 class="display-4 text-danger"><?= $stats['exclus'] ?></h2></div>
        </div>
    </div>
</div>

<!-- Étudiants par niveau -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header"><i class="fas fa-chart-bar me-2"></i>Étudiants par niveau</div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead><tr><th>Niveau</th><th class="text-end">Nb étudiants</th></tr></thead>
                    <tbody>
                        <?php foreach ($stats['etudiants_par_niveau'] as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nom']) ?></td>
                            <td class="text-end"><span class="badge bg-primary"><?= $row['nb'] ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header"><i class="fas fa-trophy me-2"></i>Meilleurs étudiants par niveau</div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead><tr><th>Niveau</th><th>Étudiant</th><th class="text-end">Moy.</th></tr></thead>
                    <tbody>
                        <?php foreach ($niveaux as $n):
                            $meilleur = getMeilleurEtudiantNiveau($n['id']);
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($n['nom']) ?></td>
                            <td><?= $meilleur ? htmlspecialchars($meilleur['prenom'].' '.$meilleur['nom']) : '<em class="text-muted">-</em>' ?></td>
                            <td class="text-end"><?= $meilleur ? '<span class="badge bg-success">'.number_format($meilleur['moyenne'],2).'</span>' : '-' ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
