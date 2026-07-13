<?php

return [
    // ============================================================
    // E-COMMERCE SYSTEM (public, no auth required)
    // Route: /
    // ============================================================
    '/'                 => ['Ecommerce\HomeController', 'index'],
    '/products'         => ['Ecommerce\ProductController', 'index'],
    '/products/{slug}'  => ['Ecommerce\ProductController', 'show'],
    '/category/{slug}'  => ['Ecommerce\ProductController', 'category'],
    '/cart'             => ['Ecommerce\CartController', 'index'],
    '/cart/add'         => ['Ecommerce\CartController', 'add'],
    '/cart/update'      => ['Ecommerce\CartController', 'update'],
    '/cart/remove'      => ['Ecommerce\CartController', 'remove'],
    '/checkout'         => ['Ecommerce\CheckoutController', 'index'],
    '/checkout/process' => ['Ecommerce\CheckoutController', 'process'],
    '/order/{id}'       => ['Ecommerce\CheckoutController', 'orderDetail'],
    '/order/{id}/status' => ['Ecommerce\CheckoutController', 'orderStatusJson'],
    '/account/orders-status' => ['Ecommerce\AccountController', 'ordersStatusJson'],
    '/account'          => ['Ecommerce\AccountController', 'index'],
    '/account/update'   => ['Ecommerce\AccountController', 'update'],
    '/login-customer'   => ['Ecommerce\HomeController', 'login'],
    '/register'         => ['Ecommerce\HomeController', 'register'],
    '/logout-customer'  => ['Ecommerce\HomeController', 'logout'],

    // ============================================================
    // ADMIN / POS STOCK BILLING SYSTEM (requires authentication)
    // Route: /admin/*
    // ============================================================

    // Auth
    '/admin/login'      => ['LoginController', 'index'],
    '/admin/logout'     => ['LoginController', 'logout'],

    // Dashboard
    '/admin'            => ['Admin\HomeController', 'index'],

    // Products
    '/admin/products'           => ['Admin\ProductController', 'index'],
    '/admin/products/json'      => ['Admin\ProductController', 'jsonList'],
    '/admin/products/create'    => ['Admin\ProductController', 'create'],
    '/admin/products/store'     => ['Admin\ProductController', 'store'],
    '/admin/products/{id}/edit' => ['Admin\ProductController', 'edit'],
    '/admin/products/{id}/update' => ['Admin\ProductController', 'update'],
    '/admin/products/{id}/delete' => ['Admin\ProductController', 'delete'],
    '/admin/products/{id}/toggle-status' => ['Admin\ProductController', 'toggleStatus'],
    '/admin/products/{id}'      => ['Admin\ProductController', 'show'],

    // Categories
    '/admin/categories'             => ['Admin\CategoryController', 'index'],
    '/admin/categories/store'       => ['Admin\CategoryController', 'store'],
    '/admin/categories/{id}/update' => ['Admin\CategoryController', 'update'],
    '/admin/categories/{id}/delete' => ['Admin\CategoryController', 'delete'],

    // POS Terminal
    '/admin/pos'            => ['Admin\POSController', 'index'],
    '/admin/pos/checkout'   => ['Admin\POSController', 'checkout'],

    // Sales
    '/admin/sales'                    => ['Admin\SaleController', 'index'],
    '/admin/sales/{id}'               => ['Admin\SaleController', 'show'],
    '/admin/sales/{id}/update-status' => ['Admin\SaleController', 'updateStatus'],
    '/admin/sales/{id}/delete'        => ['Admin\SaleController', 'delete'],
    '/admin/sales/bulk-delete'        => ['Admin\SaleController', 'bulkDelete'],

    // Orders (e-commerce)
    '/admin/orders'                    => ['Admin\OrderController', 'index'],
    '/admin/orders/{id}'               => ['Admin\OrderController', 'show'],
    '/admin/orders/{id}/update-status' => ['Admin\OrderController', 'updateStatus'],
    '/admin/orders/{id}/delete'        => ['Admin\OrderController', 'delete'],
    '/admin/orders/bulk-delete'        => ['Admin\OrderController', 'bulkDelete'],

    // Customers
    '/admin/customers'           => ['Admin\CustomerController', 'index'],
    '/admin/customers/create'    => ['Admin\CustomerController', 'create'],
    '/admin/customers/store'     => ['Admin\CustomerController', 'store'],
    '/admin/customers/{id}/edit' => ['Admin\CustomerController', 'edit'],
    '/admin/customers/{id}/update' => ['Admin\CustomerController', 'update'],
    '/admin/customers/{id}/delete' => ['Admin\CustomerController', 'delete'],
    '/admin/customers/{id}/view' => ['Admin\CustomerController', 'view'],

    // Bid Customers
    '/admin/bid-customers'           => ['Admin\BidCustomerController', 'index'],
    '/admin/bid-customers/create'    => ['Admin\BidCustomerController', 'create'],
    '/admin/bid-customers/store'     => ['Admin\BidCustomerController', 'store'],
    '/admin/bid-customers/{id}/edit' => ['Admin\BidCustomerController', 'edit'],
    '/admin/bid-customers/{id}/update' => ['Admin\BidCustomerController', 'update'],
    '/admin/bid-customers/{id}/delete' => ['Admin\BidCustomerController', 'delete'],
    '/admin/bid-customers/{id}/update-tax' => ['Admin\BidCustomerController', 'updateTax'],

    // Promotions
    '/admin/promotions'              => ['Admin\PromotionController', 'index'],
    '/admin/promotions/create'       => ['Admin\PromotionController', 'create'],
    '/admin/promotions/store'        => ['Admin\PromotionController', 'store'],
    '/admin/promotions/{id}/edit'    => ['Admin\PromotionController', 'edit'],
    '/admin/promotions/{id}/update'  => ['Admin\PromotionController', 'update'],
    '/admin/promotions/{id}/delete'  => ['Admin\PromotionController', 'delete'],

    // Invoices / Print
    '/admin/invoices/{id}/print' => ['Admin\InvoiceController', 'print'],
    '/admin/invoices/{id}'       => ['Admin\InvoiceController', 'show'],

    // Expenses
    '/admin/expenses'               => ['Admin\ExpenseController', 'index'],
    '/admin/expenses/add'           => ['Admin\ExpenseController', 'add'],
    '/admin/expenses/edit'          => ['Admin\ExpenseController', 'edit'],
    '/admin/expenses/delete'        => ['Admin\ExpenseController', 'delete'],
    '/admin/expenses/category/add'  => ['Admin\ExpenseController', 'addCategory'],
    '/admin/expenses/category/edit' => ['Admin\ExpenseController', 'editCategory'],
    '/admin/expenses/category/delete' => ['Admin\ExpenseController', 'deleteCategory'],

    // Users / Staff
    '/admin/users'           => ['Admin\UserController', 'index'],
    '/admin/users/create'    => ['Admin\UserController', 'create'],
    '/admin/users/store'     => ['Admin\UserController', 'store'],
    '/admin/users/{id}/edit' => ['Admin\UserController', 'edit'],
    '/admin/users/{id}/update' => ['Admin\UserController', 'update'],
    '/admin/users/{id}/delete' => ['Admin\UserController', 'delete'],

    // Quotations
    '/admin/quotations'                 => ['Admin\QuotationController', 'index'],
    '/admin/quotations/create'          => ['Admin\QuotationController', 'create'],
    '/admin/quotations/store'           => ['Admin\QuotationController', 'store'],
    '/admin/quotations/{id}'            => ['Admin\QuotationController', 'show'],
    '/admin/quotations/{id}/edit'       => ['Admin\QuotationController', 'edit'],
    '/admin/quotations/{id}/update'     => ['Admin\QuotationController', 'update'],
    '/admin/quotations/{id}/delete'     => ['Admin\QuotationController', 'delete'],
    '/admin/quotations/{id}/print'      => ['Admin\QuotationController', 'printView'],
    '/admin/quotations/{id}/duplicate'  => ['Admin\QuotationController', 'duplicate'],
    '/admin/quotations/{id}/convert'    => ['Admin\QuotationController', 'convertToSale'],
    '/admin/quotations/{id}/status'     => ['Admin\QuotationController', 'updateStatus'],
    '/admin/quotations/export/csv'      => ['Admin\QuotationController', 'exportCsv'],

    // Settings
    '/admin/settings'                    => ['Admin\SettingsController', 'index'],
    '/admin/settings/update'             => ['Admin\SettingsController', 'update'],
    '/admin/settings/change-password'    => ['Admin\SettingsController', 'changePassword'],

    // Database Backup / Restore
    '/admin/settings/database/export'    => ['Admin\DatabaseController', 'export'],
    '/admin/settings/database/import'    => ['Admin\DatabaseController', 'import'],

    // Payment Methods
    '/admin/payment-methods/store'       => ['Admin\PaymentMethodController', 'store'],
    '/admin/payment-methods/{id}/update' => ['Admin\PaymentMethodController', 'update'],
    '/admin/payment-methods/{id}/delete' => ['Admin\PaymentMethodController', 'delete'],

    // Public invoice view (no auth)
    '/print-invoice/{id}' => ['Admin\InvoiceController', 'printPublic'],

    // Chatbot API (public, no auth)
    '/api/chat' => ['ChatbotController', 'chat'],
];
