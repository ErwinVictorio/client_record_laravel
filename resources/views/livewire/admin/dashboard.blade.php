<div class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="/admin/dashboard">Admin Dashaboard</a>
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
     @include('partials.admin_nav')
     {{-- use include --}}
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                    <div class="row">
                                        <!-- Total Pending Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card text-white" style="background-color: #1e293b">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <h5 class="mb-2">
                                            <i class="fas fa-hourglass-half me-2"></i>
                                            Total Pending (总待处理 )
                                        </h5>
                                        <span class="fw-bold fs-4">{{$totalPending}}</span>
                                    </div>
                                    <i class="fas fa-clock fa-2x opacity-25"></i>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="#">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Sold Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card text-white" style="background-color: #334155">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <h5 class="mb-2">
                                            <i class="fas fa-user-check me-2"></i>
                                            Total Sold (售出总额)
                                        </h5>
                                        <span class="fw-bold fs-4">
                                           {{$totalSold}}
                                        </span>
                                    </div>
                                    <i class="fas fa-cash-register fa-2x opacity-25"></i>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="#">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Approve Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card text-white bg-success">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <h5 class="mb-2">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Total Approve (总已批准)
                                    </h5>
                                    <span class="fw-bold fs-4">{{$totalApproved}}</span>
                                </div>
                                <i class="fas fa-thumbs-up fa-2x opacity-25"></i>
                            </div>
                            <div class="card-footer d-flex align-items-center justify-content-between">
                                <a class="small text-white stretched-link" href="#">View Details</a>
                                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                            </div>
                        </div>
                    </div>

                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
                                <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/>
                              </svg>
                            client List
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Company Name</th>
                                        <th>Contact Number</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($clientList as $client )
                                    <tr>
                                        <td>{{$client->id}}</td>
                                        <td>{{$client->company_name}}</td>
                                        <td>{{$client->contact_number}}</td>
                                        <td>{{$client->email}}</td>
                                        <td>{{$client->address}}</td>
                                        <td>
                                            <span 
                                            class="badge rounded-pill text-white px-3 py-2"
                                            style="{{ $client->status === 'Sold' 
                                                ? 'background-color: #004998;'  /* Modern green for "Sold" */ 
                                                : 'background-color: #F44336;'  /* Modern red for other statuses */ }}">
                                            {{ $client->status }}   
                                        </span>
                                        </td>
                                        <td> 
                                        <button data-bs-toggle="modal" data-bs-target="#viewClientDetails_{{$client->id}}" style="background-color: #004998" class="btn text-light rounded-0">
                                            View More
                                        </button>
                                    </tr>
                                    <livewire:modals.view-client-details :clientId="$client->id" />
                                    @endforeach
                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
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
