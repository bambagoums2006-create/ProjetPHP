<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <title>Gestion Académique - ISI</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="<?= $dossier_public ?>css/styles.css" rel="stylesheet"/>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .card-stat { border-left: 4px solid; }
        .card-stat-primary { border-left-color: #4e73df; }
        .card-stat-success { border-left-color: #1cc88a; }
        .card-stat-warning { border-left-color: #f6c23e; }
        .card-stat-danger  { border-left-color: #e74a3b; }
        .badge-admis    { background-color: #1cc88a; }
        .badge-ajourné  { background-color: #f6c23e; color: #000; }
        .badge-exclu    { background-color: #e74a3b; }
    </style>
</head>
<body class="sb-nav-fixed">
