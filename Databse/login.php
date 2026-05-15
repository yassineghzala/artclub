<?php
session_start();
include 'connect.php';

$member_email    = trim($_POST['member_email']);
$member_password = $_POST['member_password'];

if (empty($member_email) || empty($member_password)) {
    die("Error: Please fill in all fields.");
}

$stmt = $conn->prepare("
    SELECT member_id, member_name, member_lastname, member_password
    FROM members
    WHERE member_email = ?
    LIMIT 1
");

$stmt->bind_param("s", $member_email);
$stmt->execute();
$result = $stmt->get_result();

// --- DEBUG ---
echo "Email you typed: " . htmlspecialchars($member_email) . "<br>";
echo "Rows found: " . $result->num_rows . "<br>";

if ($result->num_rows === 0) {
    die("❌ No member found with that email.");
}

$row = $result->fetch_assoc();

echo "Hash from DB: " . $row['member_password'] . "<br>";
echo $member_password . "=" . $row['member_password'] . "?";
echo "Password verify result: " . $member_password === $row['member_password'] ? 'True' : 'False';
// echo "Password verify result: " . (password_verify($member_password, $row['member_password']) ? '✅ Match' : '❌ No match') . "<br>";