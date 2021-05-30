<?php
/**
 *
 * @author     Paolo Randone
 * @author     <mail@paolorandone.it>
 * @version    2.4
 * @note       Powered for Croce Verde Torino. All rights reserved
 *
 */
session_start();
//parametri DB
include "../config/config.php";
include "../config/include/destinatari.php";

//controllo LOGIN / accesso consentito a logistica, segreteria e ADMIN
if (($_SESSION["livello"])<4){
    header("Location: ../error.php");
}
//GET variable
if (isset($_GET["ID"])){
    $id = $_GET["ID"];
    $modifica = $db->query("SELECT * FROM checklist WHERE IDCHECK='$id'")->fetch_array();
    $idoperatore = $modifica['IDOPERATORE'];
}
//OP variable
$select = $db->query("SELECT cognome, nome, squadra, sezione, email FROM utenti WHERE ID='$idoperatore'")->fetch_array();
$cognome = $select['cognome'];
$nome = $select['nome'];
$sezione = $select['sezione'];
$squadra = $select['squadra'];
$email = $select['email'];
//nicename livelli
$dictionaryLivello = array (
    1 => "Dipendente",
    2 => "Volontario",
    3 => "Altro",
    4 => "Logistica",
    5 => "Segreteria",
    6 => "ADMIN",
);
//nicename sezioni
$dictionarySezione = array (
    1 => "Torino",
    2 => "Alpignano",
    3 => "Borgaro/Caselle",
    4 => "Ciriè",
    5 => "San Mauro",
    6 => "Venaria",
    7 => "",
);
//nicename sezioni
$dictionarySquadra = array (
    1 => "Prima",
    2 => "Seconda",
    3 => "Terza",
    4 => "Quarta",
    5 => "Quinta",
    6 => "Sesta",
    7 => "Settima",
    8 => "Ottava",
    9 => "Nona",
    10 => "Sabato",
    11 => "Montagna",
    12 => "Direzione",
    13 => "Lunedì",
    14 => "Martedì",
    15 => "Mercoledì",
    16 => "Giovedì",
    17 => "Venerdì",
    18 => "Diurno",
    19 => "Giovani",
    20 => "Servizi Generali",
    21 => "Altro",
    22 => "",
);

if(isset($_POST["reply"])) {

    //TODO modificare destinatario

    $to= $email;//.', '.$bechis;
    $nome_mittente="Gestionale CVTO";
    $mail_mittente=$gestionale;
    $headers = "From: " .  $nome_mittente . " <" .  $mail_mittente . ">\r\n";
    $headers .= "Bcc: ".$randone."\r\n";
    //$headers .= "Reply-To: " .  $mail_mittente . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1";
    $subject = "Risposta segnalazione";
    $corpo = $_POST['testo'];
    mail($to, $subject, $corpo, $headers);

        echo '<script type="text/javascript">
        alert("Risposta inviata con successo");
        location.href="archivio.php";
        </script>';


}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta name="author" content="Paolo Randone">
    <title>Gestisci segnalazione</title>

    <? require "../config/include/header.html";?>

    <script>
        $(document).ready(function(){
            $('[data-toggle="popover"]').popover();
        });
    </script>
</head>
<!-- NAVBAR -->
<div class="container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../index.php" style="color: #078f40">Home</a></li>
            <li class="breadcrumb-item"><a href="index.php" style="color: #078f40">Checklist elettronica</a></li>
            <li class="breadcrumb-item"><a href="archivio.php" style="color: #078f40">Archivio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Checklist</li>
        </ol>
    </nav>
</div>
<!--content-->
<body>
<div class="container-fluid">
    <div class="row text-left">
    <div class="col-md-3 col-md-offset-3"></div>
    <div class="col-md-6">
        <div class="jumbotron">
            <form method="post" action="details.php">
                <input hidden id="xcheck" name="xcheck" value="<?=$id?>">
                <p>AUTO: <?=$modifica['IDMEZZO']?></p>
                <p>DATA: <?=$modifica['DATACHECK']?></p>
                <p>SEZIONE: <?=$dictionarySezione[$sezione]?></p>
                <p>SQUADRA: <?=$dictionarySquadra[$squadra]?></p>
                <p>COMPILATORE: <?=$cognome?> <?=$nome?></p>
                <p>MATRICOLA: <?=$modifica['IDOPERATORE']?></p>
                <hr>
                <div class="form-group">
                    <textarea class="form-control" id="xnote" name="xnote" readonly rows="15"><?=$modifica['NOTE']?></textarea>
                </div>
                <div style="text-align: center;">
                    <div class="btn-group" role="group">
                        <button type="button" id="rispondi" name="rispondi" class="btn btn-sm btn-outline-info" aria-label="Rispondi" data-toggle="modal" data-target="#modalrisposta"><i class="fas fa-reply"></i></button>

                        <a href="archivio.php" class="btn btn-sm btn-outline-secondary" id="indietro"><i class="fas fa-undo"></i></a>

                    </div>
                    <br>
                    <span style="font-size: smaller; "><em>Premi il pulsante <i class="fas fa-reply" style="color: steelblue"></i> per rispondere, oppure  <i class="fas fa-undo" style="color: grey"></i> per tornare alla pagina precedente</em></span>
                </div>
            </form>
        </div>
    </div>
</div>


</body>
<!-- Modal -->
<form action="details.php" method="post">
    <div class="modal" id="modalrisposta" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Rispondi a <?= $modifica['IDOPERATORE'] ?></h6>
                    <input hidden name="email" value="<?=$select['email']?>">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <textarea class="form-control" name="testo" rows="15" placeholder="Digita qui la risposta" autofocus><?='<messaggio originale>'."\r\n". $modifica['NOTE'] . "\r\n" .'<-------------------->'."\r\n"?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="reply" class="btn btn-info btn-sm">Invia</button>
                </div>
            </div>
        </div>
    </div>
</form>


<!-- FOOTER -->
<?php include('../config/include/footer.php'); ?>

</html>