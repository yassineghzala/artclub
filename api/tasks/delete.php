<?php

require_once '../../services/TaskService.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../members.html");
    exit;
}

$service = new TaskService();

try {
    $id = (int) ($_POST['task_id'] ?? 0);
    $service->delete($id);

    header("Location: ../../members.html?success=task_deleted");

} catch (Exception $e) {
    header("Location: ../../members.html?error=" . urlencode($e->getMessage()));
}
exit;