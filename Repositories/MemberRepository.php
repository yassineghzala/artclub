<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Member.php';

class MemberRepository {

    private mysqli $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // CREATE
    public function create(Member $member): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO members 
                (member_name, member_lastname, member_number, member_email,
                 member_class, member_department, join_date, member_password)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssssssss",                  // one 's' per parameter (all strings)
            $member->member_name,
            $member->member_lastname,
            $member->member_number,
            $member->member_email,
            $member->member_class,
            $member->member_department,
            $member->join_date,
            $member->member_password
        );

        return $stmt->execute();
    }

    // READ — by ID
    public function findById(int $id): ?Member {
        $stmt = $this->db->prepare("SELECT * FROM members WHERE member_id = ?");
        $stmt->bind_param("i", $id);     // 'i' = integer
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();
        return $row ? $this->hydrate($row) : null;
    }

    // READ — all
    public function findAll(): array {
        $result = $this->db->query("SELECT * FROM members");
        $members = [];
        while ($row = $result->fetch_assoc()) {
            $members[] = $this->hydrate($row);
        }
        return $members;
    }

    // READ — by email
    public function findByEmail(string $email): ?Member {
        $stmt = $this->db->prepare("SELECT * FROM members WHERE member_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();
        return $row ? $this->hydrate($row) : null;
    }

    // UPDATE
    public function update(Member $member): bool {
        $stmt = $this->db->prepare(
            "UPDATE members SET
                member_name       = ?,
                member_lastname   = ?,
                member_number     = ?,
                member_email      = ?,
                member_class      = ?,
                member_department = ?,
                join_date         = ?,
                member_password   = ?
             WHERE member_id = ?"
        );

        $stmt->bind_param(
            "ssssssssi",                 // 8 strings + 1 integer (id)
            $member->member_name,
            $member->member_lastname,
            $member->member_number,
            $member->member_email,
            $member->member_class,
            $member->member_department,
            $member->join_date,
            $member->member_password,
            $member->member_id
        );

        return $stmt->execute();
    }

    // DELETE
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM members WHERE member_id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // HELPER
    private function hydrate(array $row): Member {
        return new Member(
            $row['member_id'],
            $row['member_name'],
            $row['member_lastname'],
            $row['member_number'],
            $row['member_email'],
            $row['member_class'],
            $row['member_department'],
            $row['join_date'],
            $row['member_password'],
        );
    }
}