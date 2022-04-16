<?php
/**
 *
 * @author     Paolo Randone
 * @author     <mail@paolorandone.it>
 * @version    4.0
 * @note       Powered for Croce Verde Torino. All rights reserved
 *
 */
session_start();
//parametri DB
include "../config/config.php";

if (!isset($_SESSION["ID"])){
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta name="author" content="Paolo Randone">
    <title>Gestione autoparco</title>

    <? require "../config/include/header.html";?>

</head>
<!-- NAVBAR -->
<div class="container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../index.php" style="color: #078f40">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Autoparco</li>
        </ol>
    </nav>
</div>
<body>
<div class="container-fluid">
    <div class="row text-center">
        <div class="col-md-3 col-md-offset-3"></div>
        <div class="text-center col-md-6">
            <div class="jumbotron">
                <a role="button" class="btn btn-outline-cv btn-block" href="request.php"><i class="fas fa-question"></i> Richiesta</a>
                <a role="button" class="btn btn-outline-cv btn-block <?if($_SESSION['livello']<=3){echo "disabled";}?>" href="magazzino.php"><i class="fas fa-key"></i> Giacenza</a>
                <a role="button" class="btn btn-outline-cv btn-block <?if($_SESSION['livello']<=3){echo "disabled";}?>" href="lavaggi.php"><i class="fas fa-shower"></i> Lavaggio mezzi</a>
            </div>
        </div>
    </div>
</div>
</body>

<?php include('../config/include/footer.php'); ?>
</html>