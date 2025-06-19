<?php require_once __DIR__ . '/vendor/autoload.php'; ?>

<?php 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!isset($_FILES['csv'])) {
            throw new Exception('Nessun file ricevuto');
        }

        if ($_FILES['csv']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Errore nel caricamento del file. Codice errore: ' . $_FILES['csv']['error']);
        }

        $uploadDir = 'uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = basename($_FILES['csv']['name']);
        $targetPath = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['csv']['tmp_name'], $targetPath)) {
            throw new Exception('Errore durante il salvataggio del file');
        }

        // Leggi e analizza il file CSV
        $file = fopen($targetPath, 'r');
        if (!$file) {
            throw new Exception("Impossibile aprire il file CSV.");
        }

        $header = fgetcsv($file); // intestazione
        $indexId = array_search('id', $header);
        $indexPotere = array_search('potere', $header);
        $indexHelps = array_search('helps', $header);
        $indexData = array_search('data', $header); // opzionale

        if ($indexId === false || $indexPotere === false || $indexHelps === false) {
            throw new Exception('Colonne richieste mancanti: assicurati che esistano "id", "potere" e "helps".');
        }

        $playerIds = [];
        $totalePotere = 0;
        $totaleHelps = 0;
        $righeValide = 0;
        $dataReport = null;

        while (($row = fgetcsv($file)) !== false) {
            $id = $row[$indexId];
            $potere = floatval($row[$indexPotere]);
            $helps = floatval($row[$indexHelps]);

            $playerIds[$id] = true;
            $totalePotere += $potere;
            $totaleHelps += $helps;
            $righeValide++;

            if ($indexData !== false && !$dataReport) {
                $dataReport = $row[$indexData];
            }
        }

        fclose($file);

        $numeroPlayer = count($playerIds);
        $potereMedio = $numeroPlayer > 0 ? $totalePotere / $numeroPlayer : 0;
        $helpsMedio = $numeroPlayer > 0 ? $totaleHelps / $numeroPlayer : 0;

        $response = [
            'numero_player' => $numeroPlayer,
            'potere_totale' => $totalePotere,
            'helps_totali' => $totaleHelps,
            'potere_medio' => round($potereMedio, 2),
            'helps_medio' => round($helpsMedio, 2),
            'data_report' => $dataReport ?? 'Non disponibile'
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;

    } catch (Exception $e) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}
?>
