<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../Models/Sponsor.php';

class SponsorRepository {

    private mysqli $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // ──────────────────────────────────────────
    // CREATE
    // ──────────────────────────────────────────
    public function create(Sponsor $sponsor): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO sponsors (sponsor_name, sponsor_adress, sponsor_number, sponsor_email)
             VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssss",
            $sponsor->sponsor_name,
            $sponsor->sponsor_adress,
            $sponsor->sponsor_number,
            $sponsor->sponsor_email
        );

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    // ──────────────────────────────────────────
    // READ — by ID
    // ──────────────────────────────────────────
    public function findById(int $id): ?Sponsor {
        $stmt = $this->db->prepare("SELECT * FROM sponsors WHERE sponsor_id = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $row ? $this->hydrate($row) : null;
    }

    // READ — all
    public function findAll(): array {
        $result   = $this->db->query("SELECT * FROM sponsors ORDER BY sponsor_name ASC");
        $sponsors = [];
        while ($row = $result->fetch_assoc()) {
            $sponsors[] = $this->hydrate($row);
        }
        return $sponsors;
    }

    // READ — by email
    public function findByEmail(string $email): ?Sponsor {
        $stmt = $this->db->prepare("SELECT * FROM sponsors WHERE sponsor_email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $row ? $this->hydrate($row) : null;
    }

    // ──────────────────────────────────────────
    // UPDATE
    // ──────────────────────────────────────────
    public function update(Sponsor $sponsor): bool {
        $stmt = $this->db->prepare(
            "UPDATE sponsors SET
                sponsor_name   = ?,
                sponsor_adress = ?,
                sponsor_number = ?,
                sponsor_email  = ?
             WHERE sponsor_id = ?"
        );

        $stmt->bind_param(
            "ssssi",
            $sponsor->sponsor_name,
            $sponsor->sponsor_adress,
            $sponsor->sponsor_number,
            $sponsor->sponsor_email,
            $sponsor->sponsor_id
        );

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    // ──────────────────────────────────────────
    // DELETE
    // ──────────────────────────────────────────
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM sponsors WHERE sponsor_id = ?");
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    // ──────────────────────────────────────────
    // HELPER — map DB row → Sponsor object
    // ──────────────────────────────────────────
    private function hydrate(array $row): Sponsor {
        return new Sponsor(
            $row['sponsor_id'],
            $row['sponsor_name'],
            $row['sponsor_adress'],
            $row['sponsor_number'],
            $row['sponsor_email']
        );
    }
}