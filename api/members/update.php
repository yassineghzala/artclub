<?php

require_once '../../services/MemberService.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../members.html");
    exit;
}

$service = new MemberService();

try {
    $id = (int) ($_POST['member_id'] ?? 0);

    $service->update($id, [
        'member_name'       => trim($_POST['member_name']       ?? ''),
        'member_lastname'   => trim($_POST['member_lastname']   ?? ''),
        'member_number'     => trim($_POST['member_number']     ?? ''),
        'member_email'      => trim($_POST['member_email']      ?? ''),
        'member_class'      => trim($_POST['member_class']      ?? ''),
        'member_department' => trim($_POST['member_department'] ?? ''),
        'join_date'         => trim($_POST['join_date']         ?? ''),
        'member_password'   =>      $_POST['member_password']   ?? null,
    ]);

    header("Location: ../../members.html?success=member_updated");

} catch (Exception $e) {
    header("Location: ../../members.html?error=" . urlencode($e->getMessage()));
}
exit;