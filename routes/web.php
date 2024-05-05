<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\{PosController, HomeController, UnitController, UserController, BrandController, SalesController, StockController, ReportController, AccountController, DepositController, ExpenseController, PaymentController, ProductController, SettingController, CategoryController, CurrencyController, CustomerController, PurchaseController, SupplierController, WarehouseController, PaymentMethodController, DepositCategoryController, ExpenseCategoryController};

$installed = Storage::disk('public')->exists('installed');

if ($installed === true) {
    Auth::routes([
        'register' => false, // Register Routes...

        'reset' => false, // Reset Password Routes...

        'verify' => false, // Email Verification Routes...
    ]);

    // sales payment

    Route::middleware(['auth'])->group(function () {
        // Home Routes
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('/home', [HomeController::class, 'index'])->name('home.index');

        // Users
        Route::resource('users', UserController::class)->names([
            'index' => 'users.index',
            'store' => 'users.store',
            'edit' => 'users.edit',
            'update' => 'users.update',
            'destroy' => 'users.destroy',
        ]);

        Route::resource('roles', RolesController::class)->names([
            'index' => 'roles.index',
            'create' => 'roles.create',
            'store' => 'roles.store',
            'edit' => 'roles.edit',
            'update' => 'roles.update',
            'destroy' => 'roles.destroy',
        ]);

        Route::get('/profile', [UserController::class, 'profile'])->name('profile.index');

        Route::post('/profile', [UserController::class, 'save_profile'])->name('profile.save');

        //jquery post calling
        Route::post('/sales/create-sale', [SalesController::class, 'createSale'])->name('sales.createSale');
        Route::post('/sales/{sale}/edit', [SalesController::class, 'editSale'])->name('sales.editSale');
        Route::post('customers/addcustomer', [CustomerController::class, 'addcustomer'])->name('customers.addcustomer');
        Route::post('customers/addsupplier', [SupplierController::class, 'addsupplier'])->name('suppliers.addsupplier');
        Route::post('/purchases/{id}/edit', [PurchaseController::class, 'editPurchase'])->name('purchases.editPurchase');
        Route::post('/purchases/create-purchase', [PurchaseController::class, 'createPurchase'])->name('purchases.createPurchase');
        Route::post('/sales-return/{sale}/create-return', [SalesController::class, 'storeSalesReturn'])->name('sales.create.return');
        Route::post('/sales-return/{sale}/edit-return', [SalesController::class, 'storeEditSalesReturn'])->name('sales.edit.return');
        Route::post('/stocks/create/transfer', [StockController::class, 'createTransfer'])->name('stocks.create.transfer');

        Route::post('/sales/payment', [PaymentController::class, 'salespayment'])->name('sales.payment');

        // Categories Routes
        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');

        // Brand Routes
        Route::get('brands', [BrandController::class, 'index'])->name('brands.index');
        Route::post('brands/{id}/restore', [BrandController::class, 'restore'])->name('brands.restore');

        // Sales Routes
        Route::get('sales', [SalesController::class, 'index'])->name('sales.index');
        Route::get('/search-products', [SalesController::class, 'search'])->name('products.search');
        Route::get('sales/create', [SalesController::class, 'create'])->name('sales.create');
        Route::get('/sales/{id}', [SalesController::class, 'show'])->name('sales.show');
        Route::get('/sales/{sale}/edit', [SalesController::class, 'edit'])->name('sales.edit');
        Route::post('/sales/{sale}', [SalesController::class, 'update'])->name('sales.update');
        Route::get('sales-return', [SalesController::class, 'sales_return'])->name('salesreturn.list');
        Route::get('/sales-return/{id}', [SalesController::class, 'return_show'])->name('salesreturn.show');
        Route::get('/salesreturn/{sale}/edit', [SalesController::class, 'edit_return'])->name('salesreturn.edit');
        Route::get('/sales-return/{sale}/create', [SalesController::class, 'create_return'])->name('salesreturn.create');
        Route::get('/sales/export/{type}', [SalesController::class, 'export']);
        Route::get('/salereturn/export/{type}', [SalesController::class, 'export_return']);

        Route::get('/sales/download/{invoice}', [SalesController::class, 'download'])->name('sales.download');
        Route::get('/salesreturn/download/{invoice}', [SalesController::class, 'download_return'])->name('salesreturn.download');

        // Route::post('sales/{id}/restore', [SalesController::class, 'restore'])->name('sales.restore');

        // Purchases Routes
        Route::get('purchases', [PurchaseController::class, 'index'])->name('purchases.index');
        Route::get('purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
        Route::get('/purchases/{id}', [PurchaseController::class, 'show'])->name('purchases.show');
        Route::get('purchases/{purchase}/edit', [PurchaseController::class, 'edit'])->name('purchases.edit');
        Route::post('purchases/{id}/restore', [PurchaseController::class, 'restore'])->name('purchases.restore');
        Route::get('/purchases-return/{id}', [PurchaseController::class, 'return_show'])->name('purchasesreturn.show');
        Route::get('/purchases-return/{purchase}/create', [PurchaseController::class, 'create_return'])->name('purchasesreturn.create');
        Route::post('/purchases-return/{purchase}/store', [PurchaseController::class, 'storePurchasesReturn'])->name('purchasesreturn.store');
        Route::post('/purchases-return/{purchase}/update', [PurchaseController::class, 'updatePurchasesReturn'])->name('purchasesreturn.update');
        Route::get('purchases-return', [PurchaseController::class, 'return_list'])->name('purchasesreturn.list');
        Route::get('/purchasesreturn/{id}/edit', [PurchaseController::class, 'edit_return'])->name('purchasesreturn.editreturn');
        Route::get('/purchases/export/{type}', [PurchaseController::class, 'export']);
        Route::get('/purchasereturn/export/{type}', [PurchaseController::class, 'export_return']);

        Route::get('/purchases/download/{invoice}', [PurchaseController::class, 'download'])->name('purchases.download');
        Route::get('/purchasereturn/download/{invoice}', [PurchaseController::class, 'download_return'])->name('purchases.download');

        // Unit Routes
        Route::get('units', [UnitController::class, 'index'])->name('units.index');
        Route::post('units/{id}/restore', [UnitController::class, 'restore'])->name('units.restore');

        // warehouse Routes
        Route::get('warehouses', [WarehouseController::class, 'index'])->name('warehouses.index');
        Route::post('warehouses/{id}/restore', [WarehouseController::class, 'restore'])->name('warehouses.restore');

        // Stocks Routes
        Route::get('stocks', [StockController::class, 'index'])->name('stocks.index');
        Route::get('stocks/history', [StockController::class, 'history'])->name('stocks.history');
        Route::get('stocks/update', [StockController::class, 'create'])->name('stocks.update');

        Route::get('stocks/transfers', [StockController::class, 'transfers'])->name('stocks.transfers');
        Route::get('stocks/transfers/create', [StockController::class, 'create_transfers'])->name('stocks.transfers.create');

        // Customers Routes
        Route::resource('customers', CustomerController::class);
        Route::post('customers/{id}/restore', [CustomerController::class, 'restore'])->name('customers.restore');

        // Suppliers Routes
        Route::resource('suppliers', SupplierController::class);
        Route::post('suppliers/{id}/restore', [SupplierController::class, 'restore'])->name('suppliers.restore');

        // Products Routes
        Route::resource('products', ProductController::class)->except(['show']);
        Route::get('products/export/{type}', [ProductController::class, 'export']);

        Route::get('products/updateprice', [ProductController::class, 'updateprice'])->name('products.updateprice');
        //Route::post('products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');

        Route::get('pos', [PosController::class, 'index'])->name('pos.index');
        Route::get('getitems', [PosController::class, 'getitems'])->name('getitems');
        Route::get('pos/{id}', [PosController::class, 'viewpos'])->name('pos.show');
        Route::post('/pos/create-sale', [PosController::class, 'createSale'])->name('pos.createSale');

        //------------------------------- Accounting -----------------------\\
        //----------------------------------------------------------------\\
        Route::prefix('accounting')->group(function () {
            Route::resource('account', AccountController::class);
            Route::resource('deposit', DepositController::class);
            Route::resource('expense', ExpenseController::class);
            Route::resource('expense_category', ExpenseCategoryController::class);
            Route::resource('deposit_category', DepositCategoryController::class);
            Route::resource('payment_method', PaymentMethodController::class);
        });

        // Settings Routes

        Route::prefix('settings')->group(function () {
            Route::get('system', [SettingController::class, 'system'])->name('settings.system');
            Route::post('store', [SettingController::class, 'store'])->name('settings.store');
            Route::get('module', [SettingController::class, 'module'])->name('settings.module');
            Route::resource('currency', CurrencyController::class);
        });

        // Reports Routes

        Route::prefix('reports')->group(function () {
            Route::get('profit-loss', [ReportController::class, 'profitloss'])->name('reports.profit-loss');
            Route::post('profit-loss', [ReportController::class, 'profitloss'])->name('reports.profit-loss');
            Route::get('stocks', [ReportController::class, 'stocks'])->name('reports.stocks');
            Route::get('products', [ReportController::class, 'products'])->name('reports.products');
            Route::get('sales', [ReportController::class, 'sales'])->name('reports.sales');
            Route::get('purchases', [ReportController::class, 'purchases'])->name('reports.purchases');
            Route::get('suppliers', [ReportController::class, 'suppliers'])->name('reports.suppliers');
            Route::get('customers', [ReportController::class, 'customers'])->name('reports.customers');
        });
    });
} else {

    Route::get('/', function () {

        return redirect('/install');
    });
    Route::get('/install',  [InstallController::class, 'index'])->name('install.index');

    Route::get('/install/step-1',  [InstallController::class, 'step1'])->name('install.step1');

    Route::post('/install/setup',  [InstallController::class, 'setup'])->name('install.setup');

    Route::get('/install/getAppKey',  [InstallController::class, 'getAppKey'])->name('install.getAppKey');

    Route::get('/install/step-2',  [InstallController::class, 'step2'])->name('install.step2');

    Route::post('/install/setup_database', [InstallController::class, 'setupDatabase'])->name('install.setup_database');

    Route::get('/install/step-3',  [InstallController::class, 'step3'])->name('install.step3');

    Route::post('/install/finalsetup',  [InstallController::class, 'finalsetup'])->name('install.finalsetup');

      
}
