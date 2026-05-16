<?php

require_once __DIR__ . '/../repositories/SponsorRepository.php';

class SponsorService {

    private SponsorRepository $repo;

    public function __construct() {
        $this->repo = new SponsorRepository();
    }

    // ──────────────────────────────────────────
    // CREATE
    // ──────────────────────────────────────────
    public function create(array $data): bool {
        $this->validateSponsorData($data);

        if ($this->repo->findByEmail($data['sponsor_email'])) {
            throw new Exception("A sponsor with this email already exists.");
        }

        $sponsor = new Sponsor(
            null,
            $data['sponsor_name'],
            $data['sponsor_adress'],
            $data['sponsor_number'],
            $data['sponsor_email']
        );

        return $this->repo->create($sponsor);
    }

    // ──────────────────────────────────────────
    // READ
    // ──────────────────────────────────────────
    public function getSponsorById(int $id): Sponsor {
        $sponsor = $this->repo->findById($id);
        if (!$sponsor) {
            throw new Exception("Sponsor with ID $id not found.");
        }
        return $sponsor;
    }

    public function getAllSponsors(): array {
        return $this->repo->findAll();
    }

    // ──────────────────────────────────────────
    // UPDATE
    // ──────────────────────────────────────────
    public function update(int $id, array $data): bool {
        $this->getSponsorById($id); // throws if not found
        $this->validateSponsorData($data);

        $sponsor = new Sponsor(
            $id,
            $data['sponsor_name'],
            $data['sponsor_adress'],
            $data['sponsor_number'],
            $data['sponsor_email']
        );

        return $this->repo->update($sponsor);
    }

    // ──────────────────────────────────────────
    // DELETE
    // ──────────────────────────────────────────
    public function delete(int $id): bool {
        $this->getSponsorById($id); // throws if not found
        return $this->repo->delete($id);
    }

    // ──────────────────────────────────────────
    // VALIDATION
    // ──────────────────────────────────────────
    private function validateSponsorData(array $data): void {
        if (empty($data['sponsor_name'])) {
            throw new InvalidArgumentException("Sponsor name is required.");
        }
        if (empty($data['sponsor_email']) || !filter_var($data['sponsor_email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("A valid email is required.");
        }
        if (empty($data['sponsor_number'])) {
            throw new InvalidArgumentException("Sponsor phone number is required.");
        }
        if (empty($data['sponsor_adress'])) {
            throw new InvalidArgumentException("Sponsor address is required.");
        }
    }
}