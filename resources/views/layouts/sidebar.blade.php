        <!-- ====================================
          ——— LEFT SIDEBAR WITH OUT FOOTER
        ===================================== -->
        <aside class="left-sidebar bg-sidebar">
            <div id="sidebar" class="sidebar">
                <!-- Aplication Brand -->
                <div class="app-brand">
                    <a href="{{ url('') }}" title="Elite POS ">
                        <img src="{{ url('image/logo.png')}}" alt="Elite POS Logo" style="height: 50px;">


                        <span class="brand-name text-truncate">Elite POS </span>
                    </a>
                </div>

                <!-- begin sidebar scrollbar -->
                <div class="" data-simplebar style="height: 100%;">
                    <!-- sidebar menu -->
                    <ul class="nav sidebar-inner" id="sidebar-menu">
                        <li class="{{ active('home.index') }}">
                            <a class="sidenav-item-link" href="{{ url('/home') }}">
                                <i class="mdi mdi-view-dashboard-outline"></i>
                                <span class="nav-text">Dashboard</span>
                            </a>


                        </li>

                        <li class="has-sub {{ isActiveMenu('Products') }} ">
                            <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                                data-target="#app" aria-expanded="false" aria-controls="app">
                                <i class="mdi mdi-book-outline"></i>
                                <span class="nav-text">Products</span> <b class="caret"></b>
                            </a>

                            <ul class="collapse {{ show('Products') }}" id="app" data-parent="#sidebar-menu">
                                <div class="sub-menu">
                                    @if (auth()->user()->can('view_products'))
                                        <li class="{{ active('products.index') }}">
                                            <a class="sidenav-item-link" href="{{ url('products') }}">
                                                <span class="nav-text">Products List</span>
                                            </a>
                                        </li>
                                    @endif

                                    @if (auth()->user()->can('create_products'))
                                        <li class="{{ active('products.create') }}">
                                            <a class="sidenav-item-link" href="{{ url('products/create') }}">
                                                <span class="nav-text">Create Product</span>
                                            </a>
                                        </li>
                                    @endif

                                    @if (auth()->user()->can('edit_products'))
                                        <li class="{{ active('products.updateprice') }}">
                                            <a class="sidenav-item-link" href="{{ url('products/updateprice') }}">
                                                <span class="nav-text">Bulk Price Update</span>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- @if (auth()->user()->can('print_labels'))
                                        <li class="">
                                            <a class="sidenav-item-link" href="">
                                                <span class="nav-text">Print Labels</span>
                                            </a>
                                        </li>
                                    @endif --}}

                                    @if ($moduleStatuses['units'])
                                        @if (auth()->user()->can('manage_units'))
                                            <li class="{{ active('units.index') }}">
                                                <a class="sidenav-item-link" href="{{ url('units') }}">
                                                    <span class="nav-text">Units</span>
                                                </a>
                                            </li>
                                        @endif
                                    @endif

                                    @if ($moduleStatuses['categories'])
                                        @if (auth()->user()->can('manage_categories'))
                                            <li class="{{ active('categories.index') }}">
                                                <a class="sidenav-item-link" href="{{ url('categories') }}">
                                                    <span class="nav-text">Categories</span>
                                                </a>
                                            </li>
                                        @endif
                                    @endif

                                    @if ($moduleStatuses['warehouses'])
                                        @if (auth()->user()->can('manage_warehouses'))
                                            <li class="{{ active('warehouses.index') }}">
                                                <a class="sidenav-item-link" href="{{ url('warehouses') }}">
                                                    <span class="nav-text">Warehouses</span>
                                                </a>
                                            </li>
                                        @endif
                                    @endif


                                    @if ($moduleStatuses['brands'])
                                        @if (auth()->user()->can('manage_brands'))
                                            <li class="{{ active('brands.index') }}">
                                                <a class="sidenav-item-link" href="{{ url('brands') }}">
                                                    <span class="nav-text">Brands</span>
                                                </a>
                                            </li>
                                        @endif
                                    @endif



                                </div>
                            </ul>
                        </li>

                        <!-- <li class="section-title">
                  UI Elements
                </li> -->

                        <li class="has-sub {{ isActiveMenu('Sales') }}">
                            <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                                data-target="#components" aria-expanded="false" aria-controls="components">
                                <i class="mdi mdi-cart-outline"></i>
                                <span class="nav-text">Sales</span> <b class="caret"></b>
                            </a>

                            <ul class="collapse {{ show('Sales') }}" id="components" data-parent="#sidebar-menu">
                                <div class="sub-menu">
                                    @if (auth()->user()->can('view_sales'))
                                        <li class="{{ active('sales.index') }}">
                                            <a class="sidenav-item-link" href="{{ url('sales') }}">
                                                <span class="nav-text">Sales List</span>
                                            </a>
                                        </li>
                                    @endif

                                    @if (auth()->user()->can('create_sales'))
                                        <li class="{{ active('sales.create') }}">
                                            <a class="sidenav-item-link" href="{{ url('sales/create') }}">
                                                <span class="nav-text">Create Sale</span>
                                            </a>
                                        </li>
                                    @endif

                                    @if ($moduleStatuses['pos'])
                                        @if (auth()->user()->can('show_pos'))
                                            <li class="{{ active('pos.index') }}">
                                                <a class="sidenav-item-link" href="{{ url('pos') }}">
                                                    <span class="nav-text">POS</span>

                                                </a>
                                            </li>
                                        @endif
                                    @endif

                                    @if ($moduleStatuses['purchases'])
                                        @if (auth()->user()->can('view_sales'))
                                            <li class="{{ active('salesreturn.list') }}">
                                                <a class="sidenav-item-link" href="{{ route('salesreturn.list') }}">
                                                    <span class="nav-text">Sale Return</span>
                                                </a>
                                            </li>
                                        @endif
                                    @endif

                                </div>
                            </ul>
                        </li>

                        @if ($moduleStatuses['purchases'])
                            <li class="has-sub {{ isActiveMenu('Purchases') }}">
                                <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                                    data-target="#icons" aria-expanded="false" aria-controls="icons">
                                    <i class="mdi mdi-shopping"></i>
                                    <span class="nav-text">Purchases</span> <b class="caret"></b>
                                </a>

                                <ul class="collapse {{ show('Purchases') }}" id="icons"
                                    data-parent="#sidebar-menu">
                                    <div class="sub-menu">
                                        @if (auth()->user()->can('view_purchases'))
                                            <li class="{{ active('purchases.index') }}">
                                                <a class="sidenav-item-link" href="{{ url('purchases') }}">
                                                    <span class="nav-text">Purchases List</span>
                                                </a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->can('create_purchases'))
                                            <li class="{{ active('purchases.create') }}">
                                                <a class="sidenav-item-link" href="{{ url('purchases/create') }}">
                                                    <span class="nav-text">Create Purchase</span>
                                                </a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->can('view_purchases'))
                                            <li class="{{ active('purchasesreturn.list') }}">
                                                <a class="sidenav-item-link"
                                                    href="{{ route('purchasesreturn.list') }}">
                                                    <span class="nav-text">Purchases Return</span>
                                                </a>
                                            </li>
                                        @endif

                                    </div>
                                </ul>
                            </li>
                        @endif

                        @if ($moduleStatuses['stocks'])
                            @if (auth()->user()->can('manage_stocks'))
                                <li class="has-sub {{ isActiveMenu('Stocks') }}">
                                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                                        data-target="#stocks" aria-expanded="false" aria-controls="icons">
                                        <i class="mdi mdi-package-variant"></i>
                                        <span class="nav-text">Stocks</span> <b class="caret"></b>
                                    </a>

                                    <ul class="collapse {{ show('Stocks') }}" id="stocks"
                                        data-parent="#sidebar-menu">
                                        <div class="sub-menu">

                                            <li class="{{ active('stocks.index') }}">
                                                <a class="sidenav-item-link" href="{{ url('stocks') }}">
                                                    <span class="nav-text">View Stocks</span>
                                                </a>
                                            </li>

                                            <li class="{{ active('stocks.update') }}">
                                                <a class="sidenav-item-link" href="{{ url('stocks/update') }}">
                                                    <span class="nav-text">Update Stock</span>
                                                </a>
                                            </li>

                                            <li class="{{ active('stocks.history') }}">
                                                <a class="sidenav-item-link" href="{{ url('stocks/history') }}">
                                                    <span class="nav-text">Stock History </span>
                                                </a>
                                            </li>


                                            @if ($moduleStatuses['warehouses'])
                                                <li class="{{ active('stocks.transfers') }}">
                                                    <a class="sidenav-item-link"
                                                        href="{{ url('stocks/transfers') }}">
                                                        <span class="nav-text">Stock Transfers </span>
                                                    </a>
                                                </li>

                                                <li class="{{ active('stocks.transfers.create') }}">
                                                    <a class="sidenav-item-link"
                                                        href="{{ url('stocks/transfers/create') }}">
                                                        <span class="nav-text">Create Transfers </span>
                                                    </a>
                                                </li>
                                            @endif
                                        </div>
                                    </ul>
                                </li>
                            @endif
                        @endif

                        <li class="has-sub {{ isActiveMenu('Peoples') }}">
                            <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                                data-target="#forms" aria-expanded="false" aria-controls="forms">
                                <i class="mdi mdi-account-group-outline"></i>
                                <span class="nav-text">Peoples</span> <b class="caret"></b>
                            </a>

                            <ul class="collapse {{ show('Peoples') }}" id="forms" data-parent="#sidebar-menu">
                                <div class="sub-menu">
                                    @if (auth()->user()->can('manage_customers'))
                                        <li class="{{ active('customers.index') }}">
                                            <a class="sidenav-item-link" href="{{ url('customers') }}">
                                                <span class="nav-text">Customers</span>
                                            </a>
                                        </li>
                                    @endif

                                    @if ($moduleStatuses['purchases'])
                                        @if (auth()->user()->can('manage_suppliers'))
                                            <li class="{{ active('suppliers.index') }}">
                                                <a class="sidenav-item-link" href="{{ url('suppliers') }}">
                                                    <span class="nav-text">Suppliers</span>
                                                </a>
                                            </li>
                                        @endif
                                    @endif
                                </div>
                            </ul>
                        </li>

                        @if ($moduleStatuses['accounting'])
                            <li class="has-sub {{ isActiveMenu('Accounting') }}">
                                <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                                    data-target="#tables" aria-expanded="false" aria-controls="tables">
                                    <i class="mdi mdi-wallet-outline"></i>
                                    <span class="nav-text">Accounting</span> <b class="caret"></b>
                                </a>

                                <ul class="collapse {{ show('Accounting') }}" id="tables"
                                    data-parent="#sidebar-menu">
                                    <div class="sub-menu">

                                        @if (auth()->user()->can('view_account'))
                                            <li class="{{ active('account.index') }}">
                                                <a class="sidenav-item-link" href="{{ url('accounting/account') }}">
                                                    <span class="nav-text">Accounts</span>
                                                </a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->can('view_deposit'))
                                            <li class="{{ active('deposit.index') }}">
                                                <a class="sidenav-item-link" href="{{ url('accounting/deposit') }}">
                                                    <span class="nav-text">Deposits</span>
                                                </a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->can('view_expense'))
                                            <li class="{{ active('expense.index') }}">
                                                <a class="sidenav-item-link" href="{{ url('accounting/expense') }}">
                                                    <span class="nav-text">Expenses</span>
                                                </a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->can('manage_expense_categories'))
                                            <li class="{{ active('expense_category.index') }}">
                                                <a class="sidenav-item-link"
                                                    href="{{ url('accounting/expense_category') }}">
                                                    <span class="nav-text">Expense Categories</span>
                                                </a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->can('manage_deposit_categories'))
                                            <li class="{{ active('expense_category.index') }}">
                                                <a class="sidenav-item-link"
                                                    href="{{ url('accounting/deposit_category') }}">
                                                    <span class="nav-text">Deposit Categories</span>
                                                </a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->can('manage_payment_methods'))
                                            <li class="{{ active('payment_method.index') }}">
                                                <a class="sidenav-item-link"
                                                    href="{{ url('accounting/payment_method') }}">
                                                    <span class="nav-text">Payment Methods</span>
                                                </a>
                                            </li>
                                        @endif

                                    </div>
                                </ul>
                            </li>
                        @endif


                        @if (auth()->user()->can('generate_reports'))
                            <li class="has-sub {{ isActiveMenu('Reports') }} ">
                                <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                                    data-target="#widgets" aria-expanded="false" aria-controls="widgets">
                                    <i class="mdi mdi-chart-line"></i>
                                    <span class="nav-text">Reports</span> <b class="caret"></b>
                                </a>

                                <ul class="collapse {{ show('Reports') }}" id="widgets"
                                    data-parent="#sidebar-menu">
                                    <div class="sub-menu">
                                        <li class="{{ active('reports.profit-loss') }}">
                                            <a class="sidenav-item-link" href="{{ url('reports/profit-loss') }}">
                                                <span class="nav-text">Profit And Loss</span>
                                            </a>
                                        </li>
                                        @if ($moduleStatuses['stocks'])
                                            <li class="{{ active('reports.stocks') }}">
                                                <a class="sidenav-item-link" href="{{ url('reports/stocks') }}">
                                                    <span class="nav-text">Stock Report</span>
                                                </a>
                                            </li>
                                        @endif
                                        <li class="{{ active('reports.products') }}">
                                            <a class="sidenav-item-link" href="{{ url('reports/products') }}">
                                                <span class="nav-text">Product Report</span>
                                            </a>
                                        </li>

                                        <li class="{{ active('reports.sales') }}">
                                            <a class="sidenav-item-link" href="{{ url('reports/sales') }}">
                                                <span class="nav-text">Sale Report</span>
                                            </a>
                                        </li>



                                        <li class="{{ active('reports.customers') }}">
                                            <a class="sidenav-item-link" href="{{ url('reports/customers') }}">
                                                <span class="nav-text">Customer Report</span>
                                            </a>
                                        </li>

                                        @if ($moduleStatuses['purchases'])
                                            <li class="{{ active('reports.purchases') }}">
                                                <a class="sidenav-item-link" href="{{ url('reports/purchases') }}">
                                                    <span class="nav-text">Purchase Report</span>
                                                </a>
                                            </li>

                                            <li class="{{ active('reports.suppliers') }}">
                                                <a class="sidenav-item-link" href="{{ url('reports/suppliers') }}">
                                                    <span class="nav-text">Supplier Report</span>
                                                </a>
                                            </li>
                                        @endif

                                    </div>
                                </ul>
                            </li>
                        @endif

                        <li class="has-sub {{ isActiveMenu('Settings') }}">
                            <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                                data-target="#maps" aria-expanded="false" aria-controls="maps">
                                <i class="mdi mdi-settings-outline"></i>
                                <span class="nav-text">Settings</span> <b class="caret"></b>
                            </a>

                            <ul class="collapse {{ show('Settings') }}" id="maps" data-parent="#sidebar-menu">
                                <div class="sub-menu">

                                    @if (auth()->user()->can('system_settings'))
                                        <li class="{{ active('settings.system') }}">
                                            <a class="sidenav-item-link" href="{{ url('settings/system') }}">
                                                <span class="nav-text">System Settings</span>
                                            </a>
                                        </li>
                                    @endif


                                    @if (auth()->user()->can('module_settings'))
                                        <li class="{{ active('settings.module') }}">
                                            <a class="sidenav-item-link" href="{{ url('settings/module') }}">
                                                <span class="nav-text">Module Settings</span>
                                            </a>
                                        </li>
                                    @endif

                                    @if (auth()->user()->can('manage_currencies'))
                                        <li class="{{ active('currency.index') }}">
                                            <a class="sidenav-item-link" href="{{ url('settings/currency') }}">
                                                <span class="nav-text">Currency</span>
                                            </a>
                                        </li>
                                    @endif


                                </div>
                            </ul>
                        </li>


                        @if (auth()->user()->can('view_users'))
                            <li class="has-sub {{ isActiveMenu('AdminUsers') }}">
                                <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse"
                                    data-target="#adminusers" aria-expanded="false" aria-controls="adminusers">
                                    <i class="mdi mdi-account-group-outline"></i>
                                    <span class="nav-text">Admin Users</span> <b class="caret"></b>
                                </a>

                                <ul class="collapse {{ show('AdminUsers') }}" id="adminusers"
                                    data-parent="#sidebar-menu">
                                    <div class="sub-menu">

                                        @if (auth()->user()->can('view_users'))
                                            <li class="{{ active('users.index') }}">
                                                <a class="sidenav-item-link" href="{{ url('users') }}">
                                                    <span class="nav-text">Users</span>
                                                </a>
                                            </li>
                                        @endif

                                        @if (auth()->user()->can('manage_roles'))
                                            <li class="{{ active('roles.index') }}">
                                                <a class="sidenav-item-link" href="{{ url('roles') }}">
                                                    <span class="nav-text">Roles</span>
                                                </a>
                                            </li>
                                        @endif
                                    </div>
                                </ul>
                            </li>
                        @endif


                        <!-- <li class="section-title">
                  Pages
                </li> -->





                        <!-- <li class="section-title">
                  Documentation
                </li> -->
                    </ul>
                </div>


            </div>
        </aside>
