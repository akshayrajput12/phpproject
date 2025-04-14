<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "db_friend";

// First, try to connect without specifying a database
$conn = new mysqli($host, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if database exists, if not create it
$result = $conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$database'");
if ($result->num_rows == 0) {
    // Database doesn't exist, create it
    $sql = "CREATE DATABASE IF NOT EXISTS $database CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    if ($conn->query($sql) === TRUE) {
        echo "<script>console.log('Database created successfully');</script>";

        // Select the database
        $conn->select_db($database);

        // Create tables from database.sql file
        $sql_file = file_get_contents(__DIR__ . '/../database.sql');

        // Remove the CREATE DATABASE and USE statements as we've already created and selected the database
        $sql_file = preg_replace('/CREATE DATABASE.*?;/s', '', $sql_file);
        $sql_file = preg_replace('/USE.*?;/s', '', $sql_file);

        // Split the SQL file into individual statements
        $statements = array_filter(array_map('trim', explode(';', $sql_file)));

        // Execute each statement
        foreach ($statements as $statement) {
            if (!empty($statement)) {
                $conn->query($statement);
            }
        }
    } else {
        die("Error creating database: " . $conn->error);
    }
} else {
    // Database exists, select it
    $conn->select_db($database);
}

// Set character set
$conn->set_charset("utf8mb4");

// Function to sanitize input data
function sanitize($data) {
    global $conn;
    return $conn->real_escape_string(htmlspecialchars(trim($data)));
}

// Function to handle database errors
function handleDatabaseError($message) {
    global $conn;
    echo json_encode([
        'status' => 'error',
        'message' => $message,
        'error' => $conn->error
    ]);
    exit;
}
?>
