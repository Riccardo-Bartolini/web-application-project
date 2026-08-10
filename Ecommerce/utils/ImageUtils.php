<?php

function saveProductImages($files) {
    // Cartella di destinazione (assicurati che esista con i giusti permessi)
    $uploadDirectory = __ROOT__ . '/product_images/';

    // Verifica che la directory esista, altrimenti creala
    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true);
    }

    // Array per salvare i percorsi delle immagini
    $savedPaths = [];

    // Itera su ogni immagine
    $imageFields = ['imgPath1', 'imgPath2', 'imgPath3'];

    foreach ($imageFields as $field) {
        if (isset($files[$field]) && $files[$field]['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $files[$field]['tmp_name'];
            $fileName = basename($files[$field]['name']);

            // Verifica che il file sia un'immagine
            $fileType = mime_content_type($fileTmpPath);
            if (in_array($fileType, ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
                // Genera un nome univoco per evitare sovrascrizioni
                $uniqueFileName = uniqid() . '_' . $fileName;
                $destinationPath = $uploadDirectory . $uniqueFileName;

                // Sposta il file nella cartella di destinazione
                if (move_uploaded_file($fileTmpPath, $destinationPath)) {
                    // Aggiungi il percorso relativo all'array
                    $savedPaths[] = 'product_images/' . $uniqueFileName;
                } else {
                    throw new Exception("Errore nel salvataggio del file $fileName.");
                }
            } else {
                throw new Exception("Il file $fileName non è un'immagine valida.");
            }
        } elseif (isset($files[$field])) {
            throw new Exception("Errore durante il caricamento del file $field. Codice errore: " . $files[$field]['error']);
        }
    }

    // Controlla se tutte e tre le immagini sono state salvate
    if (count($savedPaths) === 3) {
        return $savedPaths;
    } else {
        throw new Exception("Non tutte le immagini sono state caricate correttamente.");
    }
}

function deleteImagesFromProjectRoot($image1, $image2, $image3) {
    // Array di risultati
    $results = [];

    // Array di immagini da elaborare
    $images = [$image1, $image2, $image3];

    foreach ($images as $image) {
        // Calcola il percorso completo dell'immagine
        $fullPath = __ROOT__ . '/' . $image;
        echo $fullPath;
        if (file_exists($fullPath)) {
            // Tenta di eliminare l'immagine
            if (unlink($fullPath)) {
                $results[$image] = "Cancellato con successo";
            } else {
                $results[$image] = "Errore durante l'eliminazione";
            }
        } else {
            $results[$image] = "File non trovato";
        }
    }

    return $results;
}