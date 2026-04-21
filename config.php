<?php
session_start();

$conn = new mysqli("localhost", "cftqptag_ngaji", "Semangat[]100", "cftqptag_ngaji");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>