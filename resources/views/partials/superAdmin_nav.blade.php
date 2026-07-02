<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">Administrator</div>
                    @php
                        $administratorMenuOpen = request()->routeIs(
                            'superAdminDashboard.view',
                            'superAdmin.departments_list',
                            'manageSalesExecutive.view',
                            'AutoPartsRecord.view',
                            'autoPartsMaintenence.view'
                        );
                    @endphp
                    <a class="nav-link {{ $administratorMenuOpen ? '' : 'collapsed' }}" href="#collapseAdmin"
                        data-bs-toggle="collapse" data-bs-target="#collapseAdmin"
                        aria-expanded="{{ $administratorMenuOpen ? 'true' : 'false' }}" aria-controls="collapseAdmin">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-shield"></i></div>
                        Administrator
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse {{ $administratorMenuOpen ? 'show' : '' }}" id="collapseAdmin" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{route('superAdmin.departments_list')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                                Manage Department (部门信息管理)
                            </a>
                            <a class="nav-link" href="{{route('manageSalesExecutive.view')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-tie"></i></div>
                                Manage Sales Executive (管理销售业务员)
                            </a>
                            <a class="nav-link" href="{{route('AutoPartsRecord.view')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-wrench"></i></div>
                                Auto Parts Records (配件销售记录)
                            </a>
                            <a class="nav-link" href="{{route('autoPartsMaintenence.view')}}">
                                <div class="sb-nav-link-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-car-front-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M2.52 3.515A2.5 2.5 0 0 1 4.82 2h6.362c1 0 1.904.596 2.298 1.515l.792 1.848c.075.175.21.319.38.404.5.25.855.715.965 1.262l.335 1.679q.05.242.049.49v.413c0 .814-.39 1.543-1 1.997V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.338c-1.292.048-2.745.088-4 .088s-2.708-.04-4-.088V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.892c-.61-.454-1-1.183-1-1.997v-.413a2.5 2.5 0 0 1 .049-.49l.335-1.68c.11-.546.465-1.012.964-1.261a.8.8 0 0 0 .381-.404l.792-1.848ZM3 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2m10 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2M6 8a1 1 0 0 0 0 2h4a1 1 0 1 0 0-2zM2.906 5.189a.51.51 0 0 0 .497.731c.91-.073 3.35-.17 4.597-.17s3.688.097 4.597.17a.51.51 0 0 0 .497-.731l-.956-1.913A.5.5 0 0 0 11.691 3H4.309a.5.5 0 0 0-.447.276L2.906 5.19Z" />
                                    </svg>
                                </div>
                                Repair & Maintenance Records (维修&保养记录)
                            </a>
                        </nav>
                    </div>
                    <div class="sb-sidenav-menu-heading">Cashier</div>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#cashier"
                        aria-expanded="false" aria-controls="collapseAdmin">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-shield"></i></div>
                        Cashier
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="cashier" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="{{route('cashier.view')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                                Cashier Dashboard (收银员仪表板)
                            </a>

                            <a class="nav-link" href="{{route('depSummary.view')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                                Department Summary (部门摘要)
                            </a>
                        </nav>
                    </div>

                    <div class="sb-sidenav-menu-heading">MANAGE CLIENTS</div>
                    <a class="nav-link collapsed" href="{{route('createFinishVhicle.view')}}">
                        <div class="sb-nav-link-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        My Client
                    </a>

                    <div class="sb-sidenav-menu-heading">Operations</div>
                    <a class="nav-link" href="{{ route('afterSales.dashboard') }}">
                        <div class="sb-nav-link-icon">
                            <i class="fas fa-screwdriver-wrench"></i>
                        </div>
                        After Sales
                    </a>
                    <a class="nav-link" href="{{ route('warehouse.dashboard') }}">
                        <div class="sb-nav-link-icon">
                            <i class="fas fa-warehouse"></i>
                        </div>
                        Warehouse
                    </a>

                    <div class="sb-sidenav-menu-heading">Account Management</div>
                    <a class="nav-link" href={{route('accountSetting.view')}}>
                        <div class="sb-nav-link-icon">
                            <i class="fas fa-gear"></i>
                        </div>
                        Accounts Settings
                    </a>
                </div>
            </div>

            <div class="sb-sidenav-footer">
                <div class="small">Logged in as:</div>
                Admin
            </div>
        </nav>
    </div>
