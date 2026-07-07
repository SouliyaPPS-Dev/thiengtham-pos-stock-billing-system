<?php

namespace App\Controllers\Admin;

use App\Core\Database;
use PDO;

class DatabaseController extends \App\Controllers\BaseController
{
    private function db(): PDO
    {
        return Database::getInstance()->getConnection();
    }

    public function export()
    {
        $db = $this->db();
        $name = $_ENV['DB_DATABASE'] ?? 'if0_42353445_thiengtham';

        $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

        $sql = "-- Database Backup: " . date('Y-m-d H:i:s') . "\n";
        $sql .= "-- System: POS Stock Billing System\n";
        $sql .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
        $sql .= "SET time_zone = \"+00:00\";\n\n";

        foreach ($tables as $table) {
            $stmt = $db->query("SHOW CREATE TABLE `$table`");
            $rowCreate = $stmt->fetch(PDO::FETCH_ASSOC);

            $sql .= "\n\nDROP TABLE IF EXISTS `$table`;\n";
            $sql .= $rowCreate['Create Table'] . ";\n\n";

            $dataStmt = $db->query("SELECT * FROM `$table`");
            $cols = $dataStmt->columnCount();

            while ($row = $dataStmt->fetch(PDO::FETCH_NUM)) {
                $vals = [];
                for ($j = 0; $j < $cols; $j++) {
                    if ($row[$j] === null) {
                        $vals[] = 'NULL';
                    } else {
                        $vals[] = $db->quote((string)$row[$j]);
                    }
                }
                $sql .= "INSERT INTO `$table` VALUES(" . implode(',', $vals) . ");\n";
            }
            $sql .= "\n\n\n";
        }

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="backup_' . $name . '_' . date('Y-m-d_H-i-s') . '.sql"');
        echo $sql;
        exit;
    }

    public function import()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['backup_file'])) {
            $file = $_FILES['backup_file'];
            if ($file['error'] === UPLOAD_ERR_OK) {
                $sql = file_get_contents($file['tmp_name']);
                $db = $this->db();

                try {
                    $db->exec("SET time_zone = '+07:00'");
                    $db->exec("SET FOREIGN_KEY_CHECKS = 0");

                    $statements = explode(';', $sql);
                    foreach ($statements as $stmt) {
                        $stmt = trim($stmt);
                        if (!empty($stmt)) {
                            $db->exec($stmt);
                        }
                    }

                    $db->exec("SET FOREIGN_KEY_CHECKS = 1");
                    $_SESSION['success'] = "ກູ້ຄືນຂໍ້ມູນສຳເລັດແລ້ວ";
                } catch (\Exception $e) {
                    $_SESSION['error'] = "Error importing database: " . $e->getMessage();
                }
            } else {
                $_SESSION['error'] = "ເກີດຂໍ້ຜິດພາດໃນການອັບໂຫລດໄຟລ໌";
            }
        }
        header('Location: ' . url('/admin/settings'));
        exit;
    }
}
