<?php

return [
    'dashboard' => [
        'icon' => 'mdi mdi-view-dashboard-outline',
        'route' => 'home',
        'title' => 'Dashboard',
    ],
    'products' => [
        'icon' => 'mdi mdi-book-outline',
        'route' => 'javascript:void(0)',
        'title' => 'Products',
        'sub_menu' => [
            'products_list' => [
                'route' => 'products.index',
                'title' => 'Products List',
            ],
            'create_product' => [
                'route' => 'products.create',
                'title' => 'Create Product',
            ],
            'update_price' => [
                'route' => 'products.updateprice',
                'title' => 'Update Price',
            ],
            'print_labels' => [
                'route' => 'products.printlabels',
                'title' => 'Print Labels',
            ],
            'units' => [
                'route' => 'units.index',
                'title' => 'Units',
            ],
            'categories' => [
                'route' => 'categories.index',
                'title' => 'Categories',
            ],
            'warehouses' => [
                'route' => 'warehouses.index',
                'title' => 'Warehouses',
            ],
            'brands' => [
                'route' => 'brands.index',
                'title' => 'Brands',
            ],
        ],
    ],
    'sales' => [
        'icon' => 'mdi mdi-cart-outline',
        'route' => 'javascript:void(0)',
        'title' => 'Sales',
        'sub_menu' => [
            'sales_list' => [
                'route' => 'sales.index',
                'title' => 'Sales List',
            ],
            'create_sale' => [
                'route' => 'sales.create',
                'title' => 'Create Sale',
            ],
            'pos' => [
                'route' => 'pos.index',
                'title' => 'POS',
            ],
            'sale_return' => [
                'route' => 'sales.return',
                'title' => 'Sale Return',
            ],
        ],
    ],

    'purchases' => [
        'icon' => 'mdi mdi-shopping',
        'route' => 'javascript:void(0)',
        'title' => 'Purchases',
        'sub_menu' => [
            'purchases_list' => [
                'route' => 'purchases.index',
                'title' => 'Purchases List',
            ],
            'create_purchase' => [
                'route' => 'purchases.create',
                'title' => 'Create Purchase',
            ],
            'purchases_return' => [
                'route' => 'purchases.return',
                'title' => 'Purchases Return',
            ],
        ],
    ],
    'stocks' => [
        'icon' => 'mdi mdi-package-variant',
        'route' => 'javascript:void(0)',
        'title' => 'Stocks',
        'sub_menu' => [
            'view_stocks' => [
                'route' => 'stocks.index',
                'title' => 'View Stocks',
            ],
            'update_stock' => [
                'route' => 'stocks.update',
                'title' => 'Update Stock',
            ],
            'stock_transfers' => [
                'route' => 'stocks.transfers',
                'title' => 'Stock Transfers',
            ],
            'stock_history' => [
                'route' => 'stocks.history',
                'title' => 'Stock History',
            ],
        ],
    ],
    'peoples' => [
        'icon' => 'mdi mdi-account-group-outline',
        'route' => 'javascript:void(0)',
        'title' => 'Peoples',
        'sub_menu' => [
            'customers' => [
                'route' => 'customers.index',
                'title' => 'Customers',
            ],
            'suppliers' => [
                'route' => 'suppliers.index',
                'title' => 'Suppliers',
            ],
        ],
    ],
    'accounting' => [
        'icon' => 'mdi mdi-wallet-outline',
        'route' => 'javascript:void(0)',
        'title' => 'Accounting',
        'sub_menu' => [
            'accounts_deposits' => [
                'route' => 'accounting.account.index',
                'title' => 'Accounts Deposits',
            ],
            'expenses' => [
                'route' => 'accounting.expense.index',
                'title' => 'Expenses',
            ],
            'expense_categories' => [
                'route' => 'accounting.expense_category.index',
                'title' => 'Expense Categories',
            ],
            'deposit_categories' => [
                'route' => 'accounting.deposit_category.index',
                'title' => 'Deposit Categories',
            ],
            'payment_methods' => [
                'route' => 'accounting.payment_method.index',
                'title' => 'Payment Methods',
            ],
        ],
    ],
    'reports' => [
        'icon' => 'mdi mdi-chart-line',
        'route' => 'javascript:void(0)',
        'title' => 'Reports',
        'sub_menu' => [
            'profit_and_loss' => [
                'route' => 'report.profit-loss',
                'title' => 'Profit And Loss',
            ],
            'stock_report' => [
                'route' => 'report.stocks',
                'title' => 'Stock Report',
            ],
            'product_report' => [
                'route' => 'report.products',
                'title' => 'Product Report',
            ],
            'sale_report' => [
                'route' => 'report.sales',
                'title' => 'Sale Report',
            ],
            'purchase_report' => [
                'route' => 'report.purchases',
                'title' => 'Purchase Report',
            ],
            'customer_report' => [
                'route' => 'report.customers',
                'title' => 'Customer Report',
            ],
            'supplier_report' => [
                'route' => 'report.suppliers',
                'title' => 'Supplier Report',
            ],
        ],
    ],

    'settings' => [
        'icon' => 'mdi mdi-settings-outline',
        'route' => 'javascript:void(0)',
        'title' => 'Settings',
        'sub_menu' => [
            'system_settings' => [
                'route' => 'settings.system',
                'title' => 'System Settings',
            ],
            'module_settings' => [
                'route' => 'settings.module',
                'title' => 'Module Settings',
            ],
            'currency' => [
                'route' => 'settings.currency',
                'title' => 'Currency',
            ],
        ],
    ],
    'admin_users' => [
        'icon' => 'mdi mdi-account-group-outline',
        'route' => 'javascript:void(0)',
        'title' => 'Admin Users',
        'sub_menu' => [
            'users' => [
                'route' => 'users.index',
                'title' => 'Users',
            ],
            'roles' => [
                'route' => 'permissions.index',
                'title' => 'Roles',
            ],
        ],
    ],
    // Continue adding other sections as needed
];
