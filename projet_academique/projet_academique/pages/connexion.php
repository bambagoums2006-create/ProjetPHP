<?php
if (isset($_POST['email']) && isset($_POST['password'])) {
    $user = login($_POST['email'], $_POST['password']);
    if ($user) {
        $_SESSION['user'] = $user;
        header("Location: index.php?page=dashboard");
        exit;
    } else {
        $erreur = "Email ou mot de passe incorrect.";
    }
}
if (isset($_GET['page']) && $_GET['page'] === 'deconnexion') {
    session_destroy();
    header("Location: index.php?page=connexion");
    exit;
}
?>
<div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
        <main>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <div class="card shadow-lg border-0 rounded-lg mt-5">
                            <div class="card-header text-center py-4" style="background: #4e73df;">
                                <h3 class="text-white mb-0"><i class="fas fa-graduation-cap me-2"></i>Gestion Académique</h3>
                                <small class="text-white-50">Institut Supérieur d'Informatique</small>
                            </div>
                            <div class="card-body p-4">
                                <?php if (isset($erreur)): ?>
                                    <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i><?= $erreur ?></div>
                                <?php endif; ?>
                                <form method="POST">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="inputEmail" name="email" type="email" placeholder="admin@isi.sn" required/>
                                        <label for="inputEmail">Adresse email</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="inputPassword" name="password" type="password" placeholder="Mot de passe" required/>
                                        <label for="inputPassword">Mot de passe</label>
                                    </div>
                                    <div class="d-grid mt-4">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center py-3 text-muted">
                                <small>Compte test : admin@isi.sn / admin123</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
