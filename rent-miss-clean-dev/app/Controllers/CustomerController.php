<?php

namespace App\Controllers;

use App\Models\Customer as CustomerModel;
use App\Models\CustomerType as CustomerTypeModel;

class CustomerController extends BaseController {
    public function index() {
        $customerModel = new CustomerModel();
        $typeModel = new CustomerTypeModel();
        
        $customer_types_list = $typeModel->getAll();
        
        $search = $_GET['search'] ?? '';
        $customer_type = $_GET['customer_type'] ?? '';
        $status_filter = $_GET['status'] ?? '';
        $limit = 20;
        $offset = 0;
        
        $where = "WHERE 1=1";
        $params = [];
        
        if (!empty($search)) {
            $where .= " AND (c.fullname LIKE ? OR c.phone LIKE ? OR c.email LIKE ?)";
            $searchTerm = "%$search%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if (!empty($customer_type)) {
            $where .= " AND c.customer_type = ?";
            $params[] = $customer_type;
        }
        
        if (!empty($status_filter)) {
            $where .= " AND c.status = ?";
            $params[] = $status_filter;
        }
        
        $customers = $customerModel->getCustomers($where, $params, $limit, $offset);
        $total = $customerModel->getTotalCustomers($where, $params);
        
        return view('pages.customers.index', [
            'title' => 'Customer Management',
            'customers' => $customers,
            'total' => $total,
            'limit' => $limit,
            'offset' => $offset,
            'search' => $search,
            'customer_type' => $customer_type,
            'status' => $status_filter
        ]);
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customerModel = new CustomerModel();
            $data = [
                'fullname' => $_POST['fullname'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'email' => !empty($_POST['email']) ? $_POST['email'] : null,
                'id_card_no' => !empty($_POST['id_card_no']) ? $_POST['id_card_no'] : null,
                'address' => !empty($_POST['address']) ? $_POST['address'] : null,
                'province' => !empty($_POST['province']) ? $_POST['province'] : null,
                'district' => !empty($_POST['district']) ? $_POST['district'] : null,
                'village' => !empty($_POST['village']) ? $_POST['village'] : null,
                'occupation' => !empty($_POST['occupation']) ? $_POST['occupation'] : null,
                'gender' => !empty($_POST['gender']) ? $_POST['gender'] : null,
                'date_of_birth' => !empty($_POST['date_of_birth']) ? $_POST['date_of_birth'] : null,
                'contact_person' => !empty($_POST['contact_person']) ? $_POST['contact_person'] : null,
                'contact_phone' => !empty($_POST['contact_phone']) ? $_POST['contact_phone'] : null,
                'customer_type' => $_POST['customer_type'] ?? 'Walk-in',
                'status' => $_POST['status'] ?? 'Active',
                'notes' => !empty($_POST['notes']) ? $_POST['notes'] : null
            ];
            
            $errors = $customerModel->validateCustomer($data);
            
            if (empty($errors)) {
                $data['created_by'] = $_SESSION['user']['id'] ?? 1;
                if ($customerModel->createCustomer($data)) {
                    if ($this->isAjax()) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => true, 'message' => 'Customer created successfully']);
                        exit;
                    }
                    header('Location: ' . url('/customers?success=1'));
                    exit;
                }
            }
            
            if ($this->isAjax()) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => !empty($errors) ? implode(', ', $errors) : 'Failed to save customer']);
                exit;
            }
            header('Location: ' . url('/customers?error=1&error_msg=' . urlencode(implode(', ', $errors))));
            exit;
        }
        
        if ($this->isAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit;
        }
        header('Location: ' . url('/customers'));
    }
    
    public function store() {
        $this->create();
    }
    
    public function edit($id) {
        $customerModel = new CustomerModel();
        $customer = $customerModel->getCustomerById($id);
        
        if (!$customer) {
            if ($this->isAjax()) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Customer not found']);
                exit;
            }
            header('Location: ' . url('/customers?error=1&error_msg=Customer not found'));
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'fullname' => $_POST['fullname'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'email' => !empty($_POST['email']) ? $_POST['email'] : null,
                'id_card_no' => !empty($_POST['id_card_no']) ? $_POST['id_card_no'] : null,
                'address' => !empty($_POST['address']) ? $_POST['address'] : null,
                'province' => !empty($_POST['province']) ? $_POST['province'] : null,
                'district' => !empty($_POST['district']) ? $_POST['district'] : null,
                'village' => !empty($_POST['village']) ? $_POST['village'] : null,
                'occupation' => !empty($_POST['occupation']) ? $_POST['occupation'] : null,
                'gender' => !empty($_POST['gender']) ? $_POST['gender'] : null,
                'date_of_birth' => !empty($_POST['date_of_birth']) ? $_POST['date_of_birth'] : null,
                'contact_person' => !empty($_POST['contact_person']) ? $_POST['contact_person'] : null,
                'contact_phone' => !empty($_POST['contact_phone']) ? $_POST['contact_phone'] : null,
                'customer_type' => $_POST['customer_type'] ?? 'Walk-in',
                'status' => $_POST['status'] ?? 'Active',
                'notes' => !empty($_POST['notes']) ? $_POST['notes'] : null
            ];
            
            $errors = $customerModel->validateCustomer($data, $id);
            
            if (empty($errors)) {
                if ($customerModel->updateCustomer($id, $data)) {
                    if ($this->isAjax()) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => true, 'message' => 'Customer updated successfully']);
                        exit;
                    }
                    header('Location: ' . url('/customers?updated=1'));
                    exit;
                }
            }
            
            if ($this->isAjax()) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => !empty($errors) ? implode(', ', $errors) : 'Failed to update customer']);
                exit;
            }
            header('Location: ' . url('/customers?error=1&error_msg=' . urlencode(implode(', ', $errors))));
            exit;
        }
        
        if ($this->isAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit;
        }
        header('Location: ' . url('/customers'));
    }
    
    private function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }
    
    public function update($id) {
        $this->edit($id);
    }
    
    public function show($id) {
        $customerModel = new CustomerModel();
        $customer = $customerModel->getCustomerById($id);
        
        if (!$customer) {
            header('Location: ' . url('/customers?error=1&error_msg=Customer not found'));
            exit;
        }
        
        $rentalHistory = $customerModel->getCustomerRentalHistory($id);
        
        return view('pages.customers.show', [
            'title' => 'Customer Details',
            'customer' => $customer,
            'rentalHistory' => $rentalHistory
        ]);
    }
    
    public function view($id) {
        $customerModel = new CustomerModel();
        $customer = $customerModel->getCustomerById($id);
        
        if ($customer) {
            echo json_encode(['success' => true, 'customer' => $customer]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Customer not found']);
        }
    }
    
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {
            $customerModel = new CustomerModel();
            $customer = $customerModel->getCustomerById($id);
            
            if ($customer) {
                if ($customerModel->deleteCustomer($id)) {
                    header('Location: ' . url('/customers?deleted=1'));
                    exit;
                } else {
                    header('Location: ' . url('/customers?error=1&error_msg=Failed to delete customer'));
                    exit;
                }
            }
        }
        
        header('Location: ' . url('/customers'));
    }
} 