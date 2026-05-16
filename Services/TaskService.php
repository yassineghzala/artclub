<?php

require_once __DIR__ . '/../repositories/TaskRepository.php';
require_once __DIR__ . '/../repositories/MemberRepository.php';

class TaskService {

    private TaskRepository   $taskRepo;
    private MemberRepository $memberRepo;

    public function __construct() {
        $this->taskRepo   = new TaskRepository();
        $this->memberRepo = new MemberRepository();
    }

    // CREATE
    public function create(array $data, int $memberId): bool {
        $this->validateTaskData($data);

        if (!$this->memberRepo->findById($memberId)) {
            throw new Exception("Member with ID $memberId not found.");
        }

        $task = new Task(
            null,
            $data['task_name'],
            $data['task_description'],
            $data['task_deadline']
        );

        return $this->taskRepo->create($task, $memberId);
    }

    // READ
    public function getTaskById(int $id): Task {
        $task = $this->taskRepo->findById($id);
        if (!$task) {
            throw new Exception("Task with ID $id not found.");
        }
        return $task;
    }

    public function getAllTasks(): array {
        return $this->taskRepo->findAll();
    }

    public function getTasksForMember(int $memberId): array {
        if (!$this->memberRepo->findById($memberId)) {
            throw new Exception("Member with ID $memberId not found.");
        }
        return $this->taskRepo->findByMemberId($memberId);
    }

    // UPDATE
    public function update(int $id, array $data): bool {
        $this->getTaskById($id); // throws if not found
        $this->validateTaskData($data);

        $task = new Task(
            $id,
            $data['task_name'],
            $data['task_description'],
            $data['task_deadline']
        );

        return $this->taskRepo->update($task);
    }

    // DELETE
    public function delete(int $id): bool {
        $this->getTaskById($id); // throws if not found
        return $this->taskRepo->delete($id);
    }

    // VALIDATION
    private function validateTaskData(array $data): void {
        if (empty($data['task_name'])) {
            throw new InvalidArgumentException("Task name is required.");
        }
        if (empty($data['task_deadline'])) {
            throw new InvalidArgumentException("Task deadline is required.");
        }
        if (strtotime($data['task_deadline']) === false) {
            throw new InvalidArgumentException("Invalid deadline date format.");
        }
        if (strtotime($data['task_deadline']) < strtotime('today')) {
            throw new InvalidArgumentException("Deadline cannot be in the past.");
        }
    }
}