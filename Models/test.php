<?php
header('Content-Type: application/json');  // ← add this line

require_once "Member.php";

$member = new Member(
    member_id:         1,
    member_name:       'John',
    member_lastname:   'Doe',
    member_number:     '0612345678',
    member_email:      'john.doe@example.com',
    member_class:      'A',
    member_department: 'Engineering',
    join_date:         '2024-01-15'
);

echo $member->toJson();