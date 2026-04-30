<?php
namespace App\Models;
use \Database;
use PDO;


abstract class BaseModel
{
    protected PDO $db;
    protected string $table;
    protected string $primaryKey = "id";

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }


    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY {$this->primaryKey} DESC");
        return $stmt->fetchAll();
    }
}
