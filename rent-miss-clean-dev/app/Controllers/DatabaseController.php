<?php

namespace App\Controllers;

class DatabaseController extends BaseController {
    public function export() {
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $user = $_ENV['DB_USERNAME'] ?? 'root';
        $pass = $_ENV['DB_PASSWORD'] ?? 'Admin123';
        $name = $_ENV['DB_DATABASE'] ?? 'if0_41710498_rent';

        $mysqli = new \mysqli($host, $user, $pass, $name);
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        $mysqli->set_charset("utf8mb4");
        $mysqli->query("SET time_zone = '+07:00'");

        $tables = [];
        $result = $mysqli->query("SHOW TABLES");
        while ($row = $result->fetch_row()) {
            $tables[] = $row[0];
        }

        $sql = "-- Database Backup: " . date('Y-m-d H:i:s') . "\n";
        $sql .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
        $sql .= "SET time_zone = \"+00:00\";\n\n";

        foreach ($tables as $table) {
            $res_create = $mysqli->query("SHOW CREATE TABLE `$table` ");
            $row_create = $res_create->fetch_assoc();
            
            $sql .= "\n\nDROP TABLE IF EXISTS `$table`;\n";
            $sql .= $row_create['Create Table'] . ";\n\n";

            $res_data = $mysqli->query("SELECT * FROM `$table` ");
            $numFields = $res_data->field_count;

            while ($row = $res_data->fetch_row()) {
                $sql .= "INSERT INTO `$table` VALUES(";
                for ($j = 0; $j < $numFields; $j++) {
                    if (isset($row[$j])) {
                        $val = $mysqli->real_escape_string($row[$j]);
                        $sql .= '"' . $val . '"';
                    } else {
                        $sql .= 'NULL';
                    }
                    if ($j < ($numFields - 1)) {
                        $sql .= ',';
                    }
                }
                $sql .= ");\n";
            }
            $sql .= "\n\n\n";
        }

        $mysqli->close();

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="backup_' . $name . '_' . date('Y-m-d_H-i-s') . '.sql"');
        echo $sql;
        exit;
    }

    public function import() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['backup_file'])) {
            $file = $_FILES['backup_file'];
            if ($file['error'] === UPLOAD_ERR_OK) {
                $sql = file_get_contents($file['tmp_name']);
                
                $host = $_ENV['DB_HOST'] ?? 'localhost';
                $user = $_ENV['DB_USERNAME'] ?? 'root';
                $pass = $_ENV['DB_PASSWORD'] ?? 'Admin123';
                $name = $_ENV['DB_DATABASE'] ?? 'if0_41710498_rent';

                $mysqli = new \mysqli($host, $user, $pass, $name);
                if ($mysqli->connect_error) {
                    $_SESSION['error'] = "Connection failed: " . $mysqli->connect_error;
                    header('Location: ' . url('/settings'));
                    exit;
                }

                $mysqli->set_charset("utf8mb4");
        $mysqli->query("SET time_zone = '+07:00'");

                // Disable foreign key checks for import
                $mysqli->query("SET FOREIGN_KEY_CHECKS = 0");

                // Execute multi-query
                if ($mysqli->multi_query($sql)) {
                    do {
                        if ($result = $mysqli->store_result()) {
                            $result->free();
                        }
                    } while ($mysqli->next_result());
                    
                    $mysqli->query("SET FOREIGN_KEY_CHECKS = 1");
                    $_SESSION['success'] = "ກູ້ຄືນຂໍ້ມູນສຳເລັດແລ້ວ";
                } else {
                    $_SESSION['error'] = "Error importing database: " . $mysqli->error;
                }
                $mysqli->close();
            } else {
                $_SESSION['error'] = "ເກີດຂໍ້ຜິດພາດໃນການອັບໂຫລດໄຟລ໌";
            }
        }
        header('Location: ' . url('/settings'));
        exit;
    }
}
