<?php
$host = "localhost";
$username = "ugrj543f7lree";
$password = "cgmq43woifko";
$dbname = "dbi0yp3jwgfsvn";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
