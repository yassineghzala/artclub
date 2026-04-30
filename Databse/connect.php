<?php
$host = "localhost";
$dbname = "artclub";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error){
    die("". $conn->connect_error);
}