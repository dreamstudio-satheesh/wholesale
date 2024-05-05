        <header class="main-header " id="header">
            <nav class="navbar navbar-static-top navbar-expand-lg">
                <!-- Sidebar toggle button -->
                <button id="sidebar-toggler" class="sidebar-toggle">
                    <span class="sr-only">Toggle navigation</span>
                </button>
                <!-- search form -->
                <div class="search-form">
                </div>

                <div class="navbar-right ">
                    <ul class="nav navbar-nav">

                        @if ($moduleStatuses['pos'])
                            @if (auth()->user()->can('show_pos'))
                                <li class="right-sidebar-in right-sidebar-2-menu">
                                    <a href="{{ url('pos') }}" class="mb-1 btn btn-sm btn-outline-primary"> POS </a>
                                </li>
                            @endif
                        @endif
                        <!-- User Account -->
                        <li class="dropdown user-menu">
                            <button href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                                <span class="d-none d-lg-inline-block">{{ auth()->user()->name }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <!-- User image -->
                                <li class="dropdown-header">
                                    <img src="{{ assets('assets/img/user/user.png') }}" class="img-circle"
                                        alt="User Image" />
                                    <div class="d-inline-block">
                                        {{ auth()->user()->name }} <small class="pt-1"> {{ auth()->user()->username }}</small>
                                    </div>
                                </li>

                                <li>
                                    <a href="{{ route('profile.index') }}">
                                        <i class="mdi mdi-account"></i> My Profile
                                    </a>
                                </li>

                                <li class="right-sidebar-in">
                                    <a href="{{ url('settings/system') }}"> <i class="mdi mdi-settings"></i> Setting
                                    </a>
                                </li>

                                <li class="dropdown-footer">
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                        <i class="mdi mdi-logout"></i> {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
