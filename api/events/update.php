<?php

require_once '../../services/EventService.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../events.html");
    exit;
}

$service = new EventService();

try {
    $id = (int) ($_POST['event_id'] ?? 0);

    $service->update($id, [
        'event_name'     => trim($_POST['event_name']     ?? ''),
        'event_date'     => trim($_POST['event_date']     ?? ''),
        'event_time'     => trim($_POST['event_time']     ?? ''),
        'event_location' => trim($_POST['event_location'] ?? ''),
    ]);

    header("Location: ../../events.html?success=event_updated");

} catch (Exception $e) {
    header("Location: ../../events.html?error=" . urlencode($e->getMessage()));
}
exit;