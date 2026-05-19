<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <a class="navbar-brand ps-3" href="index.php?page=dashboard">
        <i class="fas fa-graduation-cap me-2"></i>Gestion Académique
    </a>
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle"><i class="fas fa-bars"></i></button>
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user fa-fw"></i>
                <?= isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']['nom']) : '' ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="index.php?page=deconnexion"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</a></li>
            </ul>
        </li>
    </ul>
</nav>
