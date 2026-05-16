<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../Models/Event.php';

class EventRepository {

    private mysqli $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // ──────────────────────────────────────────
    // CREATE
    // ──────────────────────────────────────────
    public function create(Event $event): int|false {
        $stmt = $this->db->prepare(
            "INSERT INTO events (event_name, event_date, event_time, event_location)
             VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssss",
            $event->event_name,
            $event->event_date,
            $event->event_time,
            $event->event_location
        );

        $success = $stmt->execute();
        $insertedId = $success ? $this->db->insert_id : false;
        $stmt->close();

        return $insertedId;
    }

    // ──────────────────────────────────────────
    // READ — by ID
    // ──────────────────────────────────────────
    public function findById(int $id): ?Event {
        $stmt = $this->db->prepare("SELECT * FROM events WHERE event_id = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $row ? $this->hydrate($row) : null;
    }

    // READ — all
    public function findAll(): array {
        $result = $this->db->query("SELECT * FROM events ORDER BY event_date ASC");
        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = $this->hydrate($row);
        }
        return $events;
    }

    // READ — upcoming (today and future)
    public function findUpcoming(): array {
        $today = date("Y-m-d");
        $stmt  = $this->db->prepare(
            "SELECT * FROM events WHERE event_date >= ? ORDER BY event_date ASC"
        );
        $stmt->bind_param("s", $today);
        $stmt->execute();

        $events = [];
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $events[] = $this->hydrate($row);
        }
        $stmt->close();

        return $events;
    }

    // READ — past events
    public function findPast(): array {
        $today = date("Y-m-d");
        $stmt  = $this->db->prepare(
            "SELECT * FROM events WHERE event_date < ? ORDER BY event_date DESC"
        );
        $stmt->bind_param("s", $today);
        $stmt->execute();

        $events = [];
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $events[] = $this->hydrate($row);
        }
        $stmt->close();

        return $events;
    }

    // ──────────────────────────────────────────
    // UPDATE
    // ──────────────────────────────────────────
    public function update(Event $event): bool {
        $stmt = $this->db->prepare(
            "UPDATE events SET
                event_name     = ?,
                event_date     = ?,
                event_time     = ?,
                event_location = ?
             WHERE event_id = ?"
        );

        $stmt->bind_param(
            "ssssi",
            $event->event_name,
            $event->event_date,
            $event->event_time,
            $event->event_location,
            $event->event_id
        );

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    // ──────────────────────────────────────────
    // DELETE
    // ──────────────────────────────────────────
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM events WHERE event_id = ?");
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    // ──────────────────────────────────────────
    // HELPER — map DB row → Event object
    // ──────────────────────────────────────────
    private function hydrate(array $row): Event {
        return new Event(
            (int) $row['event_id'],
            $row['event_name'],
            $row['event_date'],
            $row['event_time'],
            $row['event_location']
        );
    }
}