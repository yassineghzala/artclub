<?php

class Event
{
    public ?int $event_id;
    public string $event_name;
    public string $event_date;
    public string $event_time;
    public string $event_location;

    private static ?\mysqli $db = null;

    public function __construct(
        ?int $event_id = null,
        string $event_name = '',
        string $event_date = '',
        string $event_time = '',
        string $event_location = ''
    ) {
        $this->event_id = $event_id;
        $this->event_name = $event_name;
        $this->event_date = $event_date;
        $this->event_time = $event_time;
        $this->event_location = $event_location;
    }

    private static function getConnection(): ?\mysqli
    {
        if (self::$db === null) {
            require_once __DIR__ . '/../Databse/connect.php';
            if (!isset($conn) || !($conn instanceof \mysqli)) {
                throw new \RuntimeException('Database connection is not available.');
            }
            self::$db = $conn;
        }

        return self::$db;
    }

    public static function getAllEvents(): array
    {
        $db = self::getConnection();
        $result = $db->query('SELECT * FROM events ORDER BY event_id DESC');

        if ($result === false) {
            return [];
        }

        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = self::fromArray($row);
        }

        return $events;
    }

    public static function getEventById(int $event_id): ?self
    {
        $db = self::getConnection();
        $stmt = $db->prepare('SELECT * FROM events WHERE event_id = ? LIMIT 1');
        $stmt->bind_param('i', $event_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return $row ? self::fromArray($row) : null;
    }

    public static function deleteEventById(int $event_id): bool
    {
        $db = self::getConnection();
        $stmt = $db->prepare('DELETE FROM events WHERE event_id = ?');
        $stmt->bind_param('i', $event_id);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public static function updateEventById(int $event_id, array $data): bool
    {
        $allowedFields = ['event_name', 'event_date', 'event_time', 'event_location'];
        $setClauses = [];
        $values = [];
        $types = '';

        foreach ($allowedFields as $field) {
            if (array_key_exists($field, $data)) {
                $setClauses[] = "$field = ?";
                $values[] = $data[$field];
                $types .= 's';
            }
        }

        if (empty($setClauses)) {
            return false;
        }

        $query = 'UPDATE events SET ' . implode(', ', $setClauses) . ' WHERE event_id = ?';
        $values[] = $event_id;
        $types .= 'i';

        $db = self::getConnection();
        $stmt = $db->prepare($query);
        $stmt->bind_param($types, ...$values);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public static function createEvent(self $event): int|false
    {
        $db = self::getConnection();
        $stmt = $db->prepare(
            'INSERT INTO events (event_name, event_date, event_time, event_location) VALUES (?, ?, ?, ?)'
        );
        $stmt->bind_param(
            'ssss',
            $event->event_name,
            $event->event_date,
            $event->event_time,
            $event->event_location
        );

        $success = $stmt->execute();
        $insertedId = $success ? $db->insert_id : false;
        $stmt->close();

        return $insertedId;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['event_id']) ? (int) $data['event_id'] : null,
            $data['event_name'] ?? '',
            $data['event_date'] ?? '',
            $data['event_time'] ?? '',
            $data['event_location'] ?? ''
        );
    }

    public function toArray(): array
    {
        return [
            'event_id' => $this->event_id,
            'event_name' => $this->event_name,
            'event_date' => $this->event_date,
            'event_time' => $this->event_time,
            'event_location' => $this->event_location,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }

    public function getEventId(): ?int
    {
        return $this->event_id;
    }

    public function getEventName(): string
    {
        return $this->event_name;
    }

    public function getEventDate(): string
    {
        return $this->event_date;
    }

    public function getEventTime(): string
    {
        return $this->event_time;
    }

    public function getEventLocation(): string
    {
        return $this->event_location;
    }

    public function setEventName(string $event_name): self
    {
        $this->event_name = $event_name;
        return $this;
    }

    public function setEventDate(string $event_date): self
    {
        $this->event_date = $event_date;
        return $this;
    }

    public function setEventTime(string $event_time): self
    {
        $this->event_time = $event_time;
        return $this;
    }

    public function setEventLocation(string $event_location): self
    {
        $this->event_location = $event_location;
        return $this;
    }
}
