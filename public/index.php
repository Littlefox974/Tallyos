<?php
include_once('../src/dbConnect.php');
include_once('../src/models/Hive.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content="Les abeilles de Tallyos"/>
    <meta name="author" content="Pauline Moneil"/>
    <title>Les abeilles de Tallyos</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico"/>

    <!-- App CSS -->
    <link href="css/app.css" rel="stylesheet"/>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet"/>

    <!-- Datatables, loading defered for performances: https://web.dev/defer-non-critical-css/ -->
    <link rel="preload" href="css/dataTables.bootstrap5.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="css/dataTables.bootstrap5.min.css"></noscript>

</head>
<body>
<!-- Responsive navbar-->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#home">Les abeilles de Tallyos</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-md-0" id="navbar-tabs">
                <li class="nav-item"><a class="nav-link active" href="#home">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="#ruches">Ruches <span class="badge bg-danger" id="navbarHiveCount"><?php echo Hive::getHiveCount() ?></span></a></li>
                <li class="nav-item"><a class="nav-link" href="#info">Informations</a></li>
            </ul>
            <ul class="navbar-nav ms-md-auto ms-sm-auto mb-2 mb-md-0">
                <li class="nav-item"><a class="nav-link" href="#logout">Déconnexion</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Page content-->
<main class="container py-2">
    <div id="app"></div>
</main>

<!-- Boostrap 5 -->
<script src="js/bootstrap.bundle.min.js"></script>

<!-- Datatables -->
<script src="js/jquery-3.5.1.js"></script>
<script src="js/jquery.dataTables.min.js" defer></script>
<!--<script src="js/dataTables.bootstrap5.min.js" defer></script>-->

<!-- Custom router & App JS -->
<script src="js/route.js"></script>
<script src="js/router.js"></script>
<script src="js/app.js"></script>

</body>
</html>
