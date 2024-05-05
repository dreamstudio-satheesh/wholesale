<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionsGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissionGroups = [
            ['id' => 1, 'name' => 'Dashboard', 'description' => 'Permissions related to dashboard access and overview.'],
            ['id' => 2, 'name' => 'User Management', 'description' => 'Permissions for managing users including creating, viewing, editing, and deleting users.'],
            ['id' => 3, 'name' => 'Roles and Permissions', 'description' => 'Permissions to manage roles and permissions.'],
            ['id' => 4, 'name' => 'Product Management', 'description' => 'Permissions for managing products including adding, viewing, editing, and deleting products.'],
            ['id' => 5, 'name' => 'Category Management', 'description' => 'Permissions for managing product categories.'],
            ['id' => 6, 'name' => 'Brands Management', 'description' => 'Permissions for managing brands.'],
            ['id' => 7, 'name' => 'Units Management', 'description' => 'Permissions for managing units of measurement.'],
            ['id' => 8, 'name' => 'Warehouse Management', 'description' => 'Permissions for managing warehouses.'],
            ['id' => 9, 'name' => 'Stock Management', 'description' => 'Permissions related to stock management including viewing, adding, editing, and deleting stocks.'],
            ['id' => 10, 'name' => 'Sales Management', 'description' => 'Permissions related to sales management.'],
            ['id' => 11, 'name' => 'Purchase Orders Management', 'description' => 'Permissions related to managing purchase orders.'],
            ['id' => 12, 'name' => 'Financial Transactions', 'description' => 'Permissions related to managing financial transactions.'],
            ['id' => 13, 'name' => 'Reports', 'description' => 'Permissions for generating various reports.'],
            ['id' => 14, 'name' => 'Settings and Configuration', 'description' => 'Permissions for modifying system and module settings.'],
            ['id' => 15, 'name' => 'Supplier Management', 'description' => 'Permissions for managing suppliers.'],
            ['id' => 16, 'name' => 'Customer Management', 'description' => 'Permissions for managing customers.'],
        ];

        foreach ($permissionGroups as $group) {
            PermissionGroup::create($group);
        }
    }
}
