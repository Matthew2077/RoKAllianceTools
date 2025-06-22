<?php require_once __DIR__ . '/vendor/autoload.php'; ?>

<?php 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $source = $_POST["source"]; //source = zip, per quando carichi file zip | source = csv, per quando carichi csv



    switch ($source) {
        case "zip":
            //salvare il file in /uploads
            //chiamata a python con: nome file
            //return: file csv



        break;
        case "csv":
            try {
                if (!isset($_FILES['csv'])) {
                    throw new Exception('Nessun file csv ricevuto');
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

                
                $headers = fgetcsv($file); //get headers | id,file,name,power,helps
                //prende gli index, nome colonne
                //$indexId = array_search('id', $headers);
                $indexfile = array_search('file', $headers);
                $indexPower = array_search('power', $headers);
                $indexHelps = array_search('helps', $headers);

                //dichiara var 
                $totalPower = 0;
                $totalHelps = 0;
                $rows = 0;


                //lettura righe e calcolo totalPower e totalHelps
                while (($row = fgetcsv($file)) !== false) {
                    if (count($row) !== count($headers)) continue; // salta rows malformate

                    $rows++;
                    $power = (float) $row[$indexPower];
                    $helps = (int) $row[$indexHelps];

                    $totalPower += $power;
                    $totalHelps += $helps;

                    $record = array_combine($headers, $row);
                    $records[] = $record;
                } 
                fclose($file);

                $average_power = $totalPower / $rows;
                $average_helps = $totalHelps / $rows; 
                $dataReport = date('Y-m-d H:i:s', filemtime($targetPath));
            

                // Prepara la risposta
                $response = [
                    'player_count' => $rows, //quante righe ci sono
                    'total_power' => $totalPower,
                    'average_power' => round($average_power, 2),
                    'total_helps' => $totalHelps,
                    'average_helps' => round($average_helps, 2),
                    'data_report' => $dataReport,
                    'tableData' => $records,
                ];


/*
                $test = "ciao";
                $response = [
                     'debug' => $dataReptort,
                ];
*/
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;

            } catch (Exception $e) {
                http_response_code(400);
                header('Content-Type: application/json');
                echo json_encode(['error' => $e->getMessage()]);
                exit;
            }





        break;
    }

}













/*
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
        $indexPower = array_search('power', $header);
        $indexHelps = array_search('helps', $header);
        $indexData = array_search('data', $header); // opzionale

        if ($indexId === false || $indexPower === false || $indexHelps === false) {
            throw new Exception('Colonne richieste mancanti: assicurati che esistano "id", "power" e "helps".');
        }

        $playerIds = [];
        $totalPower = 0;
        $totalHelps = 0;
        $rowsValide = 0;
        $dataReport = null;

        while (($row = fgetcsv($file)) !== false) {
            $id = $row[$indexId];
            $power = floatval($row[$indexPower]);
            $helps = floatval($row[$indexHelps]);

            $playerIds[$id] = true;
            $totalPower += $power;
            $totalHelps += $helps;
            $rowsValide++;

            if ($indexData !== false && !$dataReport) {
                $dataReport = $row[$indexData];
            }
        }

        fclose($file);

        $numeroPlayer = count($playerIds);
        $powerMedio = $numeroPlayer > 0 ? $totalPower / $numeroPlayer : 0;
        $helpsMedio = $numeroPlayer > 0 ? $totalHelps / $numeroPlayer : 0;

        $response = [
            'numero_player' => $numeroPlayer,
            'power_totale' => $totalPower,
            'helps_totali' => $totalHelps,
            'power_medio' => round($powerMedio, 2),
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
*/