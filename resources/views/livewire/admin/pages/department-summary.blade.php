<div>
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="/admin/dashboard">Dashboard</a>
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
      @include('partials.admin_nav')
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
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>Department(部门)</th>
                                        <th>Total Sold Client (总销售客户)</th>
                                        <th>Total Pending Client (总待处理客户)</th>
                                        <th>Total Registerd Client (已登记客户总计)</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($departmentSummary as $summary )
                                    @php
                                        // get the total of sold and Pending display as total registered client
                                        $totalRegisteredClient = $summary->Total_sold + $summary->Total_pending;
                                    @endphp
                                     <tr>
                                        <td>{{$summary->department}}</td>
                                        <td>{{$summary->Total_sold}}</td>
                                        <td>{{$summary->Total_pending}}</td>
                                        <td>{{$totalRegisteredClient}}</td>
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

