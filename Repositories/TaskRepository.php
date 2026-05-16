<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Task.php';

class TaskRepository {

    private mysqli $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // CREATE
    public function create(Task $task, int $memberId): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO tasks (task_name, task_description, task_deadline, member_id)
             VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "sssi",                      
            $task->task_name,
            $task->task_description,
            $task->task_deadline,
            $memberId
        );

        return $stmt->execute();
    }

    // READ — by ID
    public function findById(int $id): ?Task {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE task_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();
        return $row ? $this->hydrate($row) : null;
    }

    // READ — all
    public function findAll(): array {
        $result = $this->db->query("SELECT * FROM tasks");
        $tasks = [];
        while ($row = $result->fetch_assoc()) {
            $tasks[] = $this->hydrate($row);
        }
        return $tasks;
    }

    // READ — by member
    public function findByMemberId(int $memberId): array {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE member_id = ?");
        $stmt->bind_param("i", $memberId);
        $stmt->execute();

        $tasks = [];
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $tasks[] = $this->hydrate($row);
        }
        return $tasks;
    }

    // UPDATE
    public function update(Task $task): bool {
        $stmt = $this->db->prepare(
            "UPDATE tasks SET
                task_name        = ?,
                task_description = ?,
                task_deadline    = ?
             WHERE task_id = ?"
        );

        $stmt->bind_param(
            "sssi",                      // 3 strings + 1 integer (id)
            $task->task_name,
            $task->task_description,
            $task->task_deadline,
            $task->task_id
        );

        return $stmt->execute();
    }

    // DELETE
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM tasks WHERE task_id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // HELPER
    private function hydrate(array $row): Task {
        return new Task(
            $row['task_id'],
            $row['task_name'],
            $row['task_description'],
            $row['task_deadline']
        );
    }
}