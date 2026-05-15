<?php

class Notification {

    public $notification_id;
    public $notification_message;
    public $notification_time;
    public $notification_status;

    public function __construct(
        $notification_id,
        $notification_message,
        $notification_time,
        $notification_status
    ) {

        $this->notification_id = $notification_id;
        $this->notification_message = $notification_message;
        $this->notification_time = $notification_time;
        $this->notification_status = $notification_status;

    }

    public function toArray(): array
    {
        return [
            'notification_id' => $this->notification_id,
            'notification_message' => $this->notification_message,
            'notification_time' => $this->notification_time,
            'notification_status' => $this->notification_status
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }

    public function getNotificationId(): int
    {
        return $this->notification_id;
    }

    public function getNotificationMessage(): string
    {
        return $this->notification_message;
    }

    public function getNotificationTime(): string
    {
        return $this->notification_time;
    }

    public function getNotificationStatus(): string
    {
        return $this->notification_status;
    }

}