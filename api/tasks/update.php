<?php

require_once '../../services/TaskService.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../members.html");
    exit;
}

$service = new TaskService();

try {
    $id = (int) ($_POST['task_id'] ?? 0);

    $service->update($id, [
        'task_name'        => trim($_POST['task_name']        ?? ''),
        'task_description' => trim($_POST['task_description'] ?? ''),
        'task_deadline'    => trim($_POST['task_deadline']    ?? ''),
    ]);

    header("Location: ../../members.html?success=task_updated");

} catch (Exception $e) {
    header("Location: ../../members.html?error=" . urlencode($e->getMessage()));
}
exit;