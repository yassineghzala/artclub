<?php

require_once '../../services/MemberService.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../members.html");
    exit;
}

$service = new MemberService();

try {
    $id = (int) ($_POST['member_id'] ?? 0);
    $service->delete($id);

    header("Location: ../../members.html?success=member_deleted");

} catch (Exception $e) {
    header("Location: ../../members.html?error=" . urlencode($e->getMessage()));
}
exit;