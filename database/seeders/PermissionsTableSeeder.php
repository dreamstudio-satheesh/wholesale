<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission; // Adjust the namespace according to your app structure

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {

        $permissions = [
            // Dashboard
            ['name' => 'view_dashboard', 'description' => 'Access the main dashboard.', 'group_id' => 1],
        
            // User Management
            ['name' => 'create_users', 'description' => 'Add new users to the system.', 'group_id' => 2],
            ['name' => 'view_users', 'description' => 'View user profiles and lists.', 'group_id' => 2],
            ['name' => 'edit_users', 'description' => 'Edit user information.', 'group_id' => 2],
            ['name' => 'delete_users', 'description' => 'Remove users from the system.', 'group_id' => 2],
        
            // Roles and Permissions
            ['name' => 'manage_roles', 'description' => 'Manage User roles.', 'group_id' => 3],
        
            // Product Management
            ['name' => 'create_products', 'description' => 'Add new products.', 'group_id' => 4],
            ['name' => 'view_products', 'description' => 'View product listings.', 'group_id' => 4],
            ['name' => 'edit_products', 'description' => 'Update product details.', 'group_id' => 4],
            ['name' => 'delete_products', 'description' => 'Remove products from the catalog.', 'group_id' => 4],
            ['name' => 'print_labels', 'description' => 'Manage Print Labels', 'group_id' => 4],
        
            // Category Management
            ['name' => 'manage_categories', 'description' => 'Manage product categories.', 'group_id' => 5],
        
            // Brands Management
            ['name' => 'manage_brands', 'description' => 'Manage brands.', 'group_id' => 6],
        
            // Units Management
            ['name' => 'manage_units', 'description' => 'Manage units.', 'group_id' => 7],
        
            // Warehouse Management
            ['name' => 'manage_warehouses', 'description' => 'Manage warehouses.', 'group_id' => 8],
        
            // Stock Management
            ['name' => 'view_all_stocks', 'description' => 'View All Users Stocks.', 'group_id' => 9],
            ['name' => 'view_own_stocks', 'description' => 'View Only Current User Stock.', 'group_id' => 9],
            ['name' => 'create_stocks', 'description' => 'Add new Stocks.', 'group_id' => 9],
            ['name' => 'manage_stocks', 'description' => 'View Stocks listings.', 'group_id' => 9],
        
            // Sales Management
            ['name' => 'view_all_sales', 'description' => 'Access All Sale Records.', 'group_id' => 10],
            ['name' => 'view_own_sales', 'description' => 'View Only Current User Sale Record.', 'group_id' => 10],
            ['name' => 'create_sales', 'description' => 'Process new sales transactions.', 'group_id' => 10],
            ['name' => 'view_sales', 'description' => 'View sales order records.', 'group_id' => 10],
            ['name' => 'edit_sales', 'description' => 'Modify sales transactions.', 'group_id' => 10],
            ['name' => 'delete_sales', 'description' => 'Cancel or remove sales transactions.', 'group_id' => 10],
            ['name' => 'show_pos', 'description' => 'Access POS Page.', 'group_id' => 10],
        
                // Purchase Orders Management
            ['name' => 'view_all_purchase', 'description' => 'Access All Purchase Records.', 'group_id' => 11],
            ['name' => 'view_own_purchase', 'description' => 'View Only Current User Purchase Record.', 'group_id' => 11],
            ['name' => 'create_purchases', 'description' => 'Create purchase orders.', 'group_id' => 11],
            ['name' => 'view_purchases', 'description' => 'View purchase order records.', 'group_id' => 11],
            ['name' => 'edit_purchases', 'description' => 'Edit purchase orders.', 'group_id' => 11],
            ['name' => 'delete_purchases', 'description' => 'Cancel or delete purchase orders.', 'group_id' => 11],

            // Financial Transactions
            ['name' => 'create_account', 'description' => 'Add new Account.', 'group_id' => 12],
            ['name' => 'view_account', 'description' => 'View Account information.', 'group_id' => 12],
            ['name' => 'edit_account', 'description' => 'Update Account details.', 'group_id' => 12],
            ['name' => 'delete_account', 'description' => 'Remove Account from the system.', 'group_id' => 12],
            ['name' => 'create_deposit', 'description' => 'Add new Deposit.', 'group_id' => 12],
            ['name' => 'view_deposit', 'description' => 'View Deposit information.', 'group_id' => 12],
            ['name' => 'edit_deposit', 'description' => 'Update Deposit details.', 'group_id' => 12],
            ['name' => 'delete_deposit', 'description' => 'Remove Deposit from the system.', 'group_id' => 12],
            ['name' => 'manage_deposit_categories', 'description' => 'Manage Deposit Category.', 'group_id' => 12],
            ['name' => 'create_expense', 'description' => 'Add new Expense.', 'group_id' => 12],
            ['name' => 'view_expense', 'description' => 'View Expense information.', 'group_id' => 12],
            ['name' => 'edit_expense', 'description' => 'Update Expense details.', 'group_id' => 12],
            ['name' => 'delete_expense', 'description' => 'Remove Expense from the system.', 'group_id' => 12],
            ['name' => 'manage_expense_categories', 'description' => 'Manage Expense Category.', 'group_id' => 12],
            ['name' => 'manage_payment_methods', 'description' => 'Manage Payment Methods.', 'group_id' => 12],

            // Reports
            ['name' => 'generate_reports', 'description' => 'Generate sales, purchases, and other reports.', 'group_id' => 13],

            // Settings and Configuration
            ['name' => 'system_settings', 'description' => 'Modify system settings.', 'group_id' => 14],
            ['name' => 'module_settings', 'description' => 'Modify Module settings.', 'group_id' => 14],
            ['name' => 'manage_currencies', 'description' => 'Add or edit currencies.', 'group_id' => 14],

            // Supplier  Management
            ['name' => 'view_all_suppliers', 'description' => 'Access All suppliers Records.', 'group_id' => 15],
            ['name' => 'view_own_suppliers', 'description' => 'View Only Current Users suppliers Records.', 'group_id' => 15],
            ['name' => 'manage_suppliers', 'description' => 'Manage supplier information.', 'group_id' => 15],

            // Customer Management
            ['name' => 'view_all_customers', 'description' => 'Access All customers Records.', 'group_id' => 16],
            ['name' => 'view_own_Customers', 'description' => 'View Only Current Users Customers Records.', 'group_id' => 16],
            ['name' => 'manage_customers', 'description' => 'View customer information.', 'group_id' => 16],
        ];

            
        

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
