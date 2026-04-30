<?php

include 'connect.php';
require_once '../Models/Member.php';


$member_name       = $_POST['member_name'];
$member_lastname   = $_POST['member_lastname'];
$member_number     = $_POST['member_number'];
$member_email      = $_POST['member_email'];
$member_class      = $_POST['member_class'];
$member_department = $_POST['member_department'];

$member_password = $_POST['member_password']; 
$confirm_password  = $_POST['confirm_password'];

if ($member_password !== $confirm_password) {
    die("Error: Passwords do not match.");
}

$hashed_password = password_hash($member_password, PASSWORD_DEFAULT);

$member_id = null;
$join_date = date("Y-m-d");

$member = new Member(
    $member_id,
    $member_name,
    $member_lastname,
    $member_number,
    $member_email,
    $member_class,
    $member_department,
    $join_date,
    $hashed_password
    
);


$stmt = $conn->prepare("
    INSERT INTO members
    (
        member_name,
        member_lastname,
        member_number,
        member_email,
        member_class,
        member_department,
        join_date,
        member_password
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "ssssssss",
    $member->member_name,
    $member->member_lastname,
    $member->member_number,
    $member->member_email,
    $member->member_class,
    $member->member_department,
    $member->join_date,
    $member->member_password
);


if ($stmt->execute()) {
    echo "Saved successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();


