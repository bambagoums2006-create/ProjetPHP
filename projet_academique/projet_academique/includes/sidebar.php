<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">Principal</div>
                    <a class="nav-link <?= (isset($_GET['page']) && $_GET['page']=='dashboard') ? 'active' : '' ?>" href="index.php?page=dashboard">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Tableau de bord
                    </a>

                    <div class="sb-sidenav-menu-heading">Organisation</div>
                    <a class="nav-link <?= (isset($_GET['page']) && $_GET['page']=='niveaux') ? 'active' : '' ?>" href="index.php?page=niveaux">
                        <div class="sb-nav-link-icon"><i class="fas fa-layer-group"></i></div>
                        Niveaux
                    </a>
                    <a class="nav-link <?= (isset($_GET['page']) && $_GET['page']=='classes') ? 'active' : '' ?>" href="index.php?page=classes">
                        <div class="sb-nav-link-icon"><i class="fas fa-chalkboard"></i></div>
                        Classes
                    </a>

                    <div class="sb-sidenav-menu-heading">Académique</div>
                    <a class="nav-link <?= (isset($_GET['page']) && $_GET['page']=='etudiants') ? 'active' : '' ?>" href="index.php?page=etudiants">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-graduate"></i></div>
                        Étudiants
                    </a>
                    <a class="nav-link <?= (isset($_GET['page']) && $_GET['page']=='modules') ? 'active' : '' ?>" href="index.php?page=modules">
                        <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
                        Modules
                    </a>
                    <a class="nav-link <?= (isset($_GET['page']) && $_GET['page']=='evaluations') ? 'active' : '' ?>" href="index.php?page=evaluations">
                        <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                        Évaluations
                    </a>

                    <div class="sb-sidenav-menu-heading">Résultats</div>
                    <a class="nav-link <?= (isset($_GET['page']) && $_GET['page']=='resultats') ? 'active' : '' ?>" href="index.php?page=resultats">
                        <div class="sb-nav-link-icon"><i class="fas fa-chart-bar"></i></div>
                        Résultats & Bulletins
                    </a>
                </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small">Connecté en tant que :</div>
                <?= isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']['nom']) : '' ?>
            </div>
        </nav>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
