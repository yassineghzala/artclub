<?php

require_once '../../services/SponsorService.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../sponsors.html");
    exit;
}

$service = new SponsorService();

try {
    $id = (int) ($_POST['sponsor_id'] ?? 0);

    $service->update($id, [
        'sponsor_name'   => trim($_POST['sponsor_name']   ?? ''),
        'sponsor_adress' => trim($_POST['sponsor_adress'] ?? ''),
        'sponsor_number' => trim($_POST['sponsor_number'] ?? ''),
        'sponsor_email'  => trim($_POST['sponsor_email']  ?? ''),
    ]);

    header("Location: ../../sponsors.html?success=sponsor_updated");

} catch (Exception $e) {
    header("Location: ../../sponsors.html?error=" . urlencode($e->getMessage()));
}
exit;