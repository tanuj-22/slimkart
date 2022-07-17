<?php
//These are the defined authentication environment in the db service

// The MySQL service named in the docker-compose.yml.
$host = 'db';

// Database use name
$user = 'u814170597_slim';

//database user password
$pass = 'Slim123#';

// check the MySQL connection status
$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("Connection faild: " . $conn->connect_error);
} else {
    echo "Connected to MySQL server successfully!";
}
?>