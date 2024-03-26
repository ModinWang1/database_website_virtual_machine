<?php
$host = 'localhost'; 
$dbname = 'assign2db'; 
$username = 'root'; 
$password = 'cs3319'; 

// Function to connect to the database
function connectDB() {
    global $host, $dbname, $username, $password;

    // data souce name
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";


    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Turn on errors 
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
        PDO::ATTR_EMULATE_PREPARES => false, 
    ];

    try {
        // Create a PDO instance
        $pdo = new PDO($dsn, $username, $password, $options);
        return $pdo;
    } catch (PDOException $e) {
        // Catch any error
        die("Could not connect to the database $dbname :" . $e->getMessage());
    }
}

// function to disconnect from the database
function disconnectDB($pdo) {
    $pdo = null;
}
?>
