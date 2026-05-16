<?php

require_once '../../services/SponsorService.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../sponsors.html");
    exit;
}

$service = new SponsorService();

try {
    $id = (int) ($_POST['sponsor_id'] ?? 0);
    $service->delete($id);

    header("Location: ../../sponsors.html?success=sponsor_deleted");

} catch (Exception $e) {
    header("Location: ../../sponsors.html?error=" . urlencode($e->getMessage()));
}
exit;