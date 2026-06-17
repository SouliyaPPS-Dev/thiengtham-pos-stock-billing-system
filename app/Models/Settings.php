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
        $stmt = $this->db()->prepare("SELECT setting_key, setting_value FROM settings");
        $stmt->execute();
        $result = [];
        foreach ($stmt->fetchAll() as $row) {
            $result[$row['setting_key']] = $row['setting_value'];
        }
        return $result;
    }

    public function get($key, $default = null)
    {
        $stmt = $this->db()->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $row = $stmt->fetch();
        return $row ? $row['setting_value'] : $default;
    }

    public function set($key, $value)
    {
        $stmt = $this->db()->prepare("INSERT INTO settings (setting_key, setting_value, updated_at)
                                      VALUES (?, ?, NOW())
                                      ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), updated_at = NOW()");
        return $stmt->execute([$key, $value]);
    }
}
