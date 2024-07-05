
<?php
function dbconnection() {
    // Connection parameters
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'customer';
    $port = 3307;

    // Establish connection
    $con = mysqli_connect($host, $username, $password, $database, $port);

    // Check connection
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $con;
}

// Test connection
$con = dbconnection();
if ($con) {
    echo "Connected successfully";
}
