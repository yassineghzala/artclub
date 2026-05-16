<?php

class Member{
    
    public $member_id;
    public $member_name;
    public $member_lastname;
    public $member_number;
    public $member_email;
    public $member_class;
    public $member_department;
    public $join_date;
    public $member_password;
    private array $tasks = [];

    public function __construct(
        $member_id,
        $member_name,
        $member_lastname,
        $member_number,
        $member_email,
        $member_class,
        $member_department,
        $join_date,
        $member_password,) {

        $this->member_id = $member_id;
        $this->member_name = $member_name;
        $this->member_lastname = $member_lastname;
        $this->member_number = $member_number;
        $this->member_email = $member_email;
        $this->member_class = $member_class;
        $this->member_department = $member_department;
        $this->join_date = $join_date;
        $this->member_password = $member_password;

    }

    public function toArray(): array
{
    return [
        'member_id'         => $this->member_id,
        'member_name'       => $this->member_name,
        'member_lastname'   => $this->member_lastname,
        'member_number'     => $this->member_number,
        'member_email'      => $this->member_email,
        'member_class'      => $this->member_class,
        'member_department' => $this->member_department,
        'join_date'         => $this->join_date,
        'member_password' => $this->member_password
    ];
}

public function toJson(): string
{
    return json_encode($this->toArray(), JSON_PRETTY_PRINT);
}

    public function getMemberId(): int       { return $this->member_id; }
    public function getMemberName(): string  { return $this->member_name; }
    public function getLastname(): string    { return $this->member_lastname; }
    public function getNumber(): string      { return $this->member_number; }
    public function getEmail(): string       { return $this->member_email; }
    public function getClass(): string       { return $this->member_class; }
    public function getDepartment(): string  { return $this->member_department; }
    public function getJoinDate(): string    { return $this->join_date; }
    public function getPassword(): string    { return $this->member_password; }

    // Add a task to this member
    // public function addTask(Task $task): void {
    //     $this->tasks[] = $task;
    // }

    // // Remove a task
    // public function removeTask(Task $taskToRemove): void {
    //     $this->tasks = array_filter(
    //         $this->tasks,
    //         fn(Task $task) => $task->getTaskId() !== $taskToRemove->getTaskId()
    //     );
    // }

    // public function getTasks(): array  { return $this->tasks; }
}