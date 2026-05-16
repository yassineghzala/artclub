<?php

require_once __DIR__ . '/../repositories/MemberRepository.php';

class MemberService {

    private MemberRepository $repo;

    public function __construct() {
        $this->repo = new MemberRepository();
    }

    // CREATE
    public function create(array $data): bool {
        $this->validateMemberData($data);

        if ($this->repo->findByEmail($data['member_email'])) {
            throw new Exception("Email already in use.");
        }

        if ($data['member_password'] !== $data['confirm_password']) {
            throw new Exception("Passwords do not match.");
        }

        $member = new Member(
            null,
            $data['member_name'],
            $data['member_lastname'],
            $data['member_number'],
            $data['member_email'],
            $data['member_class'],
            $data['member_department'],
            date("Y-m-d"),
            password_hash($data['member_password'], PASSWORD_DEFAULT),
        );

        return $this->repo->create($member);
    }

    // READ
    public function getMemberById(int $id): Member {
        $member = $this->repo->findById($id);
        if (!$member) {
            throw new Exception("Member with ID $id not found.");
        }
        return $member;
    }

    public function getAllMembers(): array {
        return $this->repo->findAll();
    }

    // UPDATE
    public function update(int $id, array $data): bool {
        $this->getMemberById($id); // throws if not found
        $this->validateMemberData($data, false); // false = password not required on update

        $member = new Member(
            $id,
            $data['member_name'],
            $data['member_lastname'],
            $data['member_number'],
            $data['member_email'],
            $data['member_class'],
            $data['member_department'],
            $data['join_date'],
            $data['member_password'] ?? null,
        );

        return $this->repo->update($member);
    }

    // DELETE
    public function delete(int $id): bool {
        $this->getMemberById($id); // throws if not found
        return $this->repo->delete($id);
    }

    // VALIDATION
    private function validateMemberData(array $data, bool $passwordRequired = true): void {
        if (empty($data['member_name'])) {
            throw new InvalidArgumentException("First name is required.");
        }
        if (empty($data['member_lastname'])) {
            throw new InvalidArgumentException("Last name is required.");
        }
        if (empty($data['member_email']) || !filter_var($data['member_email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("A valid email is required.");
        }
        if (empty($data['member_department'])) {
            throw new InvalidArgumentException("Department is required.");
        }
        if ($passwordRequired && strlen($data['member_password']) < 8) {
            throw new InvalidArgumentException("Password must be at least 8 characters.");
        }
    }
}