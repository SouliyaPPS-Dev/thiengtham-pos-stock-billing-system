<?php

return [
    '/' => ['HomeController', 'index'],

    // Auth
    '/login' => ['LoginController', 'index'],
    '/logout' => ['LoginController', 'logout'],

    // Products
    '/products' => ['ProductController', 'index'],
    '/products/create' => ['ProductController', 'create'],
    '/products/store' => ['ProductController', 'store'],
    '/products/{id}/edit' => ['ProductController', 'edit'],
    '/products/{id}/update' => ['ProductController', 'update'],
    '/products/{id}/delete' => ['ProductController', 'delete'],
    '/products/{id}' => ['ProductController', 'show'],

    // Categories
    '/categories' => ['CategoryController', 'index'],
    '/categories/store' => ['CategoryController', 'store'],
    '/categories/{id}/update' => ['CategoryController', 'update'],
    '/categories/{id}/delete' => ['CategoryController', 'delete'],

    // POS
    '/pos' => ['POSController', 'index'],
    '/pos/checkout' => ['POSController', 'checkout'],

    // Sales
    '/sales' => ['SaleController', 'index'],
    '/sales/{id}' => ['SaleController', 'show'],

    // Customers
    '/customers' => ['CustomerController', 'index'],
    '/customers/create' => ['CustomerController', 'create'],
    '/customers/store' => ['CustomerController', 'store'],
    '/customers/{id}/edit' => ['CustomerController', 'edit'],
    '/customers/{id}/update' => ['CustomerController', 'update'],
    '/customers/{id}/delete' => ['CustomerController', 'delete'],
    '/customers/{id}/view' => ['CustomerController', 'view'],

    // Suppliers
    '/suppliers' => ['SupplierController', 'index'],
    '/suppliers/create' => ['SupplierController', 'create'],
    '/suppliers/store' => ['SupplierController', 'store'],
    '/suppliers/{id}/edit' => ['SupplierController', 'edit'],
    '/suppliers/{id}/update' => ['SupplierController', 'update'],
    '/suppliers/{id}/delete' => ['SupplierController', 'delete'],

    // Invoices / Print
    '/invoices/{id}/print' => ['InvoiceController', 'print'],
    '/invoices/{id}' => ['InvoiceController', 'show'],

    // Users
    '/users' => ['UserController', 'index'],
    '/users/create' => ['UserController', 'create'],
    '/users/store' => ['UserController', 'store'],
    '/users/{id}/edit' => ['UserController', 'edit'],
    '/users/{id}/update' => ['UserController', 'update'],
    '/users/{id}/delete' => ['UserController', 'delete'],

    // Settings
    '/settings' => ['SettingsController', 'index'],
    '/settings/update' => ['SettingsController', 'update'],
    '/settings/change-password' => ['SettingsController', 'changePassword'],
];
