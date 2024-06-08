<?php
$servername = "localhost";
$username = "root";
$password = "Holmestrand@992012";
$dbname = "hopk";

try {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Check if form data is set
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $requiredFields = ['navn', 'dato', 'klubb', 'aktivitet'];
        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                throw new Exception("All form fields are required.");
            }
        }

        $navn = $conn->real_escape_string(trim($_POST['navn']));
        $dato = $conn->real_escape_string(trim($_POST['dato']));
        $klubb = $conn->real_escape_string(trim($_POST['klubb']));
        $aktivitet = $conn->real_escape_string(trim($_POST['aktivitet']));

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO registrer (navn, dato, klubb, aktivitet) VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            throw new Exception("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("ssss", $navn, $dato, $klubb, $aktivitet);

        // Execute statement
        if ($stmt->execute()) {
            
            echo '<script>alert("Du har blitt registrert!");</script>';

            echo '<script>window.location.href = "hopk.html";</script>';
        } else {
            throw new Exception("Error executing SQL query: " . $stmt->error);
        }

        $stmt->close();
    }

    // Close connection
    $conn->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    error_log($e->getMessage(), 3, "/var/tmp/my-errors.log"); // Log errors to file
}
?>
