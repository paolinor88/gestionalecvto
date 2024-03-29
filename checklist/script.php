<?php
include "../config/pdo.php";
if (isset($_POST["targa"])){
    $insert = "INSERT INTO mezzi  (ID, targa, tipo, stato, note, IMMATRICOLAZIONE) VALUES  (:ID, :targa, :tipo, :stato, :note, :IMMATRICOLAZIONE)";

    $statement = $connect->prepare($insert);
    $statement->execute(
        array(
            ':ID' => $_POST['ID'],
            ':targa' => $_POST['targa'],
            ':tipo' => $_POST['tipo'],
            ':stato' => 1,
            ':note' => $_POST['note'],
            ':IMMATRICOLAZIONE' => $_POST['IMMATRICOLAZIONE'],
        )
    );
}
if (isset($_POST["stato"])){
    $update = "UPDATE checklist SET STATO=:STATO WHERE IDCHECK=:id";

    $statement2 = $connect->prepare($update);
    $statement2->execute(
        array(
            ':STATO' => $_POST['stato'],
            ':id' => $_POST['id'],
        )
    );
}
if (isset($_POST["status"])){
    $update3 = "UPDATE images SET status=:status WHERE id=:id"; 

    $statement3 = $connect->prepare($update3);
    $statement3->execute(
        array(
            ':status' => $_POST['status'],
            ':id' => $_POST['id'],
        )
    );
}