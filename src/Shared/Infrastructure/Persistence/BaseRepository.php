<?php

namespace MediaLibrary\Shared\Infrastructure\Persistence;

use PDO;

abstract class BaseRepository
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    protected function tableExists(string $tableName): bool
    {
        try {
            $result = $this->db->query("SELECT 1 FROM `{$tableName}` LIMIT 1");
            return $result !== false;
        } catch (\Exception $e) {
            return false;
        }
    }
}
