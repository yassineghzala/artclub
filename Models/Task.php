<?php

class Task {

    public $task_id;
    public $task_name;
    public $task_description;
    public $task_deadline;

    public function __construct(
        $task_id,
        $task_name,
        $task_description,
        $task_deadline
    ) {

        $this->task_id = $task_id;
        $this->task_name = $task_name;
        $this->task_description = $task_description;
        $this->task_deadline = $task_deadline;

    }

    public function toArray(): array
    {
        return [
            'task_id' => $this->task_id,
            'task_name' => $this->task_name,
            'task_description' => $this->task_description,
            'task_deadline' => $this->task_deadline
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }

    public function getTaskId(): int
    {
        return $this->task_id;
    }

    public function getTaskName(): string
    {
        return $this->task_name;
    }

    public function getTaskDescription(): string
    {
        return $this->task_description;
    }

    public function getTaskDeadline(): string
    {
        return $this->task_deadline;
    }

}