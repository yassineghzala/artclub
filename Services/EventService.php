<?php

require_once __DIR__ . '/../repositories/EventRepository.php';

class EventService {

    private EventRepository $repo;

    public function __construct() {
        $this->repo = new EventRepository();
    }

    // ──────────────────────────────────────────
    // CREATE
    // ──────────────────────────────────────────
    public function create(array $data): int|false {
        $this->validateEventData($data);

        $event = new Event(
            null,
            $data['event_name'],
            $data['event_date'],
            $data['event_time'],
            $data['event_location']
        );

        return $this->repo->create($event);
    }

    // ──────────────────────────────────────────
    // READ
    // ──────────────────────────────────────────
    public function getEventById(int $id): Event {
        $event = $this->repo->findById($id);
        if (!$event) {
            throw new Exception("Event with ID $id not found.");
        }
        return $event;
    }

    public function getAllEvents(): array {
        return $this->repo->findAll();
    }

    public function getUpcomingEvents(): array {
        return $this->repo->findUpcoming();
    }

    public function getPastEvents(): array {
        return $this->repo->findPast();
    }

    // ──────────────────────────────────────────
    // UPDATE
    // ──────────────────────────────────────────
    public function update(int $id, array $data): bool {
        $this->getEventById($id); // throws if not found

        $this->validateEventData($data);

        $event = new Event(
            $id,
            $data['event_name'],
            $data['event_date'],
            $data['event_time'],
            $data['event_location']
        );

        return $this->repo->update($event);
    }

    // ──────────────────────────────────────────
    // DELETE
    // ──────────────────────────────────────────
    public function delete(int $id): bool {
        $this->getEventById($id); // throws if not found
        return $this->repo->delete($id);
    }

    // ──────────────────────────────────────────
    // VALIDATION
    // ──────────────────────────────────────────
    private function validateEventData(array $data): void {
        if (empty($data['event_name'])) {
            throw new InvalidArgumentException("Event name is required.");
        }
        if (empty($data['event_date']) || strtotime($data['event_date']) === false) {
            throw new InvalidArgumentException("A valid event date is required.");
        }
        if (empty($data['event_time'])) {
            throw new InvalidArgumentException("Event time is required.");
        }
        if (empty($data['event_location'])) {
            throw new InvalidArgumentException("Event location is required.");
        }
    }
}