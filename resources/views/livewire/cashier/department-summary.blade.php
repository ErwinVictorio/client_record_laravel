<div>
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="/admin/dashboard">Cashier Dashboard</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li>
                        <livewire:auth.logout/>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
      {{-- sidebar --}}
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Reports</div>
                        <a class="nav-link" href="{{route('summary')}}">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-bar"></i></div>
                            Department Summary
                            (部门摘要)
                        </a>
                    
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    Cashier
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
        
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Department Summary  (部门摘要)</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">department dummary</li>
                    </ol>
                    {{-- table --}}
                    <div class="card mb-4">
                        <div class="card-body">
                            <table class="table table-bordered table-hover align-middle text-center">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Department (部门)</th>
                                        <th>Total Sold Client (总销售客户)</th>
                                        <th>Total Pending Client (总待处理客户)</th>
                                        <th>Total Registered Client (已登记客户总计)</th>
                                        <th>Sold Percentage (%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($departmentSummary as $summary)
                                        @php
                                            $totalRegisteredClient = $summary->Total_sold + $summary->Total_pending;
                                            $percentageSold = $totalRegisteredClient > 0 
                                                ? round(($summary->Total_sold / $totalRegisteredClient) * 100, 2) 
                                                : 0;
                                        @endphp
                                        <tr>
                                            <td>{{ $summary->department }}</td>
                                            <td>
                                                <span class="badge bg-success fs-6">
                                                    {{ $summary->Total_sold }}
                                                </span>
                                            </td>
                                            <td>{{ $summary->Total_pending }}</td>
                                            <td>{{ $totalRegisteredClient }}</td>
                                            <td>
                                                <span class="badge bg-info text-dark fs-6">
                                                    {{ $percentageSold }}%
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                </div>
            </main>
            {{-- modal --}}
           <livewire:modals.create-department/>

            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2023</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</div>

