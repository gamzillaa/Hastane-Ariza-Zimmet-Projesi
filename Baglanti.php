<?php
$conn = mysqli_connect("localhost", "root", "", "hastane_db");
if (!$conn) {
    die("Bağlantı hatası: " . mysqli_connect_error());
}

?>