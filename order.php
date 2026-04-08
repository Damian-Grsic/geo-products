<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// 1. Rohdaten empfangen
$rawData = file_get_contents("php://input");

if (!$rawData) {
    echo json_encode(["success" => false, "message" => "Keine Daten empfangen"]);
    exit;
}

// 2. JSON dekodieren
$data = json_decode($rawData, true);

if ($data === null) {
    echo json_encode(["success" => false, "message" => "Ungültiges JSON"]);
    exit;
}

// 3. Pflichtfelder prüfen
if (!isset($data["name"]) || !isset($data["address"]) || !isset($data["products"])) {
    echo json_encode(["success" => false, "message" => "Fehlende Felder"]);
    exit;
}

// 4. Bestellung speichern (als Datei)
$orderFile = "orders.txt";
$entry = "----- Neue Bestellung -----\n";
$entry .= "Name: " . $data["name"] . "\n";
$entry .= "Adresse: " . $data["address"] . "\n";
$entry .= "Produkte: " . json_encode($data["products"]) . "\n";
$entry .= "Zeit: " . date("Y-m-d H:i:s") . "\n\n";

file_put_contents($orderFile, $entry, FILE_APPEND);

// 5. Antwort an die App
echo json_encode(["success" => true, "message" => "Bestellung erfolgreich gespeichert"]);
?>
