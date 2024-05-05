<?php

if (!function_exists('assets')) {
    function assets($path)
    {
        return asset("themes/sleek/$path");
    }
}

if (!function_exists('active')) {
    function active($routePattern)
    {
        return request()->routeIs($routePattern) ? 'active' : '';
    }
}

if (!function_exists('isActiveMenu')) {
    /**
     * Checks if the current URL matches any submenu URLs of a given menu key.
     *
     * @param string $menuKey The key of the menu (e.g., 'Products').
     * @return string Returns 'active' if a match is found, otherwise an empty string.
     */
    function isActiveMenu($menuKey) {
        $menuStructure = getMenuStructure();
        $currentUrl = url()->current();

        // Check if the menuKey exists and has submenus
        if (array_key_exists($menuKey, $menuStructure) && is_array($menuStructure[$menuKey])) {
            foreach ($menuStructure[$menuKey] as $url) {
                // Since we're comparing full URLs, ensure trailing slashes do not cause a mismatch
                $formattedCurrentUrl = rtrim($currentUrl, '/');
                $formattedMenuUrl = rtrim($url, '/');
                if ($formattedCurrentUrl == $formattedMenuUrl) {
                    return 'active';
                }
            }
        }

        return '';
    }
}



if (!function_exists('getMenuStructure')) {
    function getMenuStructure() {
        return [
            'Dashboard' => route('home.index'),
            'Products' => [
                'ProductsList' => route('products.index'),
                'CreateProduct' => route('products.create'),
                'UpdatePrice' => route('products.updateprice'), 
                'PrintLabels' => url('products.printlabels'), 
                'Units' => route('units.index'),
                'Categories' => route('categories.index'),
                'Warehouses' => route('warehouses.index'),
                'Brands' => route('brands.index'),
            ],
            'Sales' => [
                'SalesList' => route('sales.index'),
                'CreateSale' => route('sales.create'),
                'POS' => route('pos.index'),
                'SaleReturn' => route('salesreturn.list'),
            ],
            'Purchases' => [
                'PurchasesList' => route('purchases.index'),
                'CreatePurchase' => route('purchases.create'),
                'PurchasesReturn' => route('purchasesreturn.list'),
            ],
            'Stocks' => [
                'ViewStocks' => route('stocks.index'),
                'UpdateStock' => route('stocks.update'),
                'StockTransfers' => route('stocks.transfers'),
                'CreateTransfer' => route('stocks.transfers.create'),
                'StockHistory' => route('stocks.history'),
            ],
            'Peoples' => [
                'Customers' => route('customers.index'),
                'Suppliers' => route('suppliers.index'),
            ],
            'Accounting' => [
                'Accounts' => route('account.index'),
                'AccountsDeposits' => route('deposit.index'),
                'Expenses' => route('expense.index'),
                'ExpenseCategories' => route('deposit_category.index'),
                'DepositCategories' => route('expense_category.index'),
                'PaymentMethods' => route('payment_method.index'),
            ],
            'Reports' => [
                'ProfitAndLoss' => route('reports.profit-loss'),
                'StockReport' => route('reports.stocks'),
                'ProductReport' => route('reports.products'),
                'SaleReport' => route('reports.sales'),
                'PurchaseReport' => route('reports.purchases'),
                'CustomerReport' => route('reports.customers'),
                'SupplierReport' => route('reports.suppliers'),
            ],
            'Settings' => [
                'SystemSettings' => route('settings.system'),
                'ModuleSettings' => route('settings.module'),
                'Currency' => route('currency.index'),
            ],
            'AdminUsers' => [
                'Users' => route('users.index'),
                'Roles' => route('roles.index'),
            ],
        ];
    }
}


if (!function_exists('show')) {
    /**
     * Check if the current URL matches any submenu URLs of a given menu.
     *
     * @param string $menuKey The key of the menu (e.g., 'products').
     * @return string Returns 'show' if a match is found, otherwise an empty string.
     */
    function show($menuKey) {
        $menuStructure = getMenuStructure();
        $currentUrl = url()->current();

        if (array_key_exists($menuKey, $menuStructure) && is_array($menuStructure[$menuKey])) {
            foreach ($menuStructure[$menuKey] as $url) {
                if ($currentUrl == $url) {
                    return 'show';
                }
            }
        }

        return '';
    }
}


