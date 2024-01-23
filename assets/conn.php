<?php
$user = "root";
$pass = "";
try {
    $dbh = new PDO('mysql:host=localhost;dbname=sophiane', $user, $pass);

    // Set the PDO error mode to exception
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch marker data from the 'markers' table
    $stmt = $dbh->query('SELECT * FROM markers');
    $markerData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output the marker data as JSON (assuming you're using JavaScript to fetch and process it)
    header('Content-Type: application/json');
    echo json_encode($markerData);
} catch (PDOException $e) {
    // Handle connection or query errors
    echo 'Connection failed: ' . $e->getMessage();
}
?>
