<?php

require_once '../../services/TaskService.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../members.html");
    exit;
}

$service = new TaskService();

try {
    $memberId = (int) ($_POST['member_id'] ?? 0);

    $service->create([
        'task_name'        => trim($_POST['task_name']        ?? ''),
        'task_description' => trim($_POST['task_description'] ?? ''),
        'task_deadline'    => trim($_POST['task_deadline']    ?? ''),
    ], $memberId);

    header("Location: ../../members.html?success=task_created");

} catch (Exception $e) {
    header("Location: ../../members.html?error=" . urlencode($e->getMessage()));
}
exit;