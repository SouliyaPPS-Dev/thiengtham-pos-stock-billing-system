<?php

namespace App\Models;

use App\Core\Database;

class Settings
{
    protected function db()
    {
        return Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->db()->prepare("SELECT `key`, `value` FROM settings");
        $stmt->execute();
        $result = [];
        foreach ($stmt->fetchAll() as $row) {
            $result[$row['key']] = $row['value'];
        }
        return $result;
    }

    public function get($key, $default = null)
    {
        $stmt = $this->db()->prepare("SELECT `value` FROM settings WHERE `key` = ?");
        $stmt->execute([$key]);
        $row = $stmt->fetch();
        return $row ? $row['value'] : $default;
    }

    public function set($key, $value)
    {
        $stmt = $this->db()->prepare("INSERT INTO settings (`key`, `value`, updated_at)
                                      VALUES (?, ?, NOW())
                                      ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), updated_at = NOW()");
        return $stmt->execute([$key, $value]);
    }
}
