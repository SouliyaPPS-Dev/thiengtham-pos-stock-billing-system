<?php

namespace App\Controllers;

use App\Models\CustomerType;

class CustomerTypeController extends BaseController {
    private $typeModel;

    public function __construct() {
        $this->typeModel = new CustomerType();
    }

    public function index() {
        $types = $this->typeModel->getAll();
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'types' => $types]);
        exit;
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            
            if (empty($name)) {
                $this->jsonResponse(false, 'ຊື່ປະເພດລູກຄ້າແມ່ນຕ້ອງການ');
            }

            if ($this->typeModel->create(['name' => $name])) {
                $this->jsonResponse(true, 'ເພີ່ມປະເພດລູກຄ້າສຳເລັດ');
            } else {
                $this->jsonResponse(false, 'ບໍ່ສາມາດເພີ່ມປະເພດລູກຄ້າໄດ້');
            }
        }
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            
            if (empty($name)) {
                $this->jsonResponse(false, 'ຊື່ປະເພດລູກຄ້າແມ່ນຕ້ອງການ');
            }

            if ($this->typeModel->update($id, ['name' => $name])) {
                $this->jsonResponse(true, 'ແກ້ໄຂປະເພດລູກຄ້າສຳເລັດ');
            } else {
                $this->jsonResponse(false, 'ບໍ່ສາມາດແກ້ໄຂປະເພດລູກຄ້າໄດ້');
            }
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->typeModel->delete($id)) {
                $this->jsonResponse(true, 'ລຶບປະເພດລູກຄ້າສຳເລັດ');
            } else {
                $this->jsonResponse(false, 'ບໍ່ສາມາດລຶບໄດ້ ເນື່ອງຈາກມີລູກຄ້າທີ່ໃຊ້ປະເພດນີ້ຢູ່');
            }
        }
    }

    private function jsonResponse($success, $message) {
        header('Content-Type: application/json');
        echo json_encode(['success' => $success, 'message' => $message]);
        exit;
    }
}
 