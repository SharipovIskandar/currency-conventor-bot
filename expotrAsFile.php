<?php

$servername = "localhost";
$username = "root";
$password = "iskan2066";
$dbname = "tgBot";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['export'])) {
    $filename = "report_" . date('YmdHis') . ".csv";

    // Fetch data from database
    $sql = "SELECT chatId, conType, amount, date FROM data";
    $stmt = $pdo->query($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Generate CSV content
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen('php://output', 'w');
    fputcsv($output, array('chatId', 'conType', 'amount', 'date'));

    foreach ($rows as $row) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit();
}
?>
