<?php

class Sponsor{
    
    public $sponsor_id;
    public $sponsor_name;
    public $sponsor_adress;
    public $sponsor_number;
    public $sponsor_email;

    public function __construct(
        $sponsor_id,
        $sponsor_name,
        $sponsor_adress,
        $sponsor_number,
        $sponsor_email) {

        $this->sponsor_id = $sponsor_id;
        $this->sponsor_name = $sponsor_name;
        $this->sponsor_adress = $sponsor_adress;
        $this->sponsor_number = $sponsor_number;
        $this->sponsor_email = $sponsor_email;

    }

    public function toArray(): array
{
    return [
        'sponsor_id'         => $this->sponsor_id,
        'sponsor_name'       => $this->sponsor_name,
        'sponsor_adress'   => $this->sponsor_adress,
        'sponsor_number'     => $this->sponsor_number,
        'sponsor_email'      => $this->sponsor_email,
    ];
}

public function toJson(): string
{
    return json_encode($this->toArray(), JSON_PRETTY_PRINT);
}

    public function getSponsorId(): int       { return $this->sponsor_id; }
    public function getSponsorName(): string  { return $this->sponsor_name; }
    public function getAdress(): string    { return $this->sponsor_adress; }
    public function getNumber(): string      { return $this->sponsor_number; }
    public function getEmail(): string       { return $this->sponsor_email; }


}