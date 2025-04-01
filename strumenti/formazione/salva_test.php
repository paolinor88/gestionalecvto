<?php
session_start();
include "../config/config.php";
global $db;

if (!isset($_SESSION['discente_id'])) {
    echo json_encode(["success" => false, "error" => "Utente non autenticato"]);
    exit();
}

$discente_id = $_SESSION['discente_id'];
$id_lezione = $_POST['id_lezione'] ?? 0;
$id_corso = $_POST['id_corso'] ?? 0;

$query = "SELECT id, risposta_corretta FROM test_domande WHERE id_lezione = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $id_lezione);
$stmt->execute();
$result = $stmt->get_result();

$corrette = 0;
$total = 0;
$error_questions = [];

while ($row = $result->fetch_assoc()) {
    $total++;
    $id_domanda = $row['id'];
    $risposta_corretta = $row['risposta_corretta'];
    $risposta_utente = $_POST["risposta_$id_domanda"] ?? 0;

    if ($risposta_utente == $risposta_corretta) {
        $corrette++;
    } else {
        $error_questions[] = ["id" => $id_domanda, "corretta" => $risposta_corretta];
    }
}

$soglia = 0.75;
$superato = ($corrette / $total) >= $soglia;

$lezione_successiva = null;

if ($superato) {
    $stmt = $db->prepare("
        UPDATE progresso_lezioni 
        SET superato_test = 1, 
            data_completamento = IF(completata = 1, NOW(), data_completamento) 
        WHERE discente_id = ? AND id_lezione = ?
    ");
    $stmt->bind_param("ii", $discente_id, $id_lezione);
    $stmt->execute();

    $query_next = "
        SELECT id_lezione 
        FROM lezioni 
        WHERE id_corso = ? AND ordine > (
            SELECT ordine FROM lezioni WHERE id_lezione = ?
        )
        ORDER BY ordine ASC
        LIMIT 1
    ";
    $stmt = $db->prepare($query_next);
    $stmt->bind_param("ii", $id_corso, $id_lezione);
    $stmt->execute();
    $stmt->bind_result($lezione_successiva);
    $stmt->fetch();
    $stmt->close();

    if (!$lezione_successiva) {
        $query_first = "SELECT id_lezione FROM lezioni WHERE id_corso = ? ORDER BY ordine ASC LIMIT 1";
        $stmt = $db->prepare($query_first);
        $stmt->bind_param("i", $id_corso);
        $stmt->execute();
        $stmt->bind_result($lezione_successiva);
        $stmt->fetch();
        $stmt->close();
    }
}

echo json_encode([
    "success" => $superato,
    "score" => $corrette,
    "total" => $total,
    "next_lezione" => $lezione_successiva,
    "error_questions" => $error_questions
]);
?>