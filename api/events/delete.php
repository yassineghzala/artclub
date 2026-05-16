<?php

require_once '../../services/EventService.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../events.html");
    exit;
}

$service = new EventService();

try {
    $id = (int) ($_POST['event_id'] ?? 0);
    $service->delete($id);

    header("Location: ../../events.html?success=event_deleted");

} catch (Exception $e) {
    header("Location: ../../events.html?error=" . urlencode($e->getMessage()));
}
exit;