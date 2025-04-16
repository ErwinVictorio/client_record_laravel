<div class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="/cashier/dashboard">Cashier Dashboard</a>
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
                    <li><a class="dropdown-item" href="#!">Logout</a></li>
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
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link" href="/cashier/dashboard">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">Interface</div>
                       
    
                        <a class="nav-link" href="tables.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                             Sales Department
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    Cashier
                </div>
            </nav>
        </div>
     {{-- use include --}}
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card text-white bg-primary shadow rounded-4 h-100 border-0">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="me-3">
                                            <i class="fas fa-user-clock fa-2x"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-uppercase fw-semibold mb-1">Total Approve Client</h6>
                                            <h3 class="fw-bold mb-0">{{$countedPending}}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between bg-transparent border-0 pt-0">
                                    <a class="small text-white text-decoration-underline stretched-link" href="#">View Details</a>
                                    <i class="fas fa-arrow-circle-right text-white"></i>
                                </div>
                            </div>
                        </div>
                        
       
          
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card text-white bg-danger shadow rounded-4 h-100 border-0">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="me-3">
                                            <i class="fas fa-user-check fa-2x"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-uppercase fw-semibold mb-1">Total Sold Client</h6>
                                            <h3 class="fw-bold mb-0">{{$counttedSoldClient}}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between bg-transparent border-0 pt-0">
                                    <a class="small text-white text-decoration-underline stretched-link" href="#">View Details</a>
                                    <i class="fas fa-arrow-circle-right text-white"></i>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                  
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                           Client List
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>Company Name</th>
                                        <th>Email</th>
                                        <th>Contact Number</th>
                                        <th>Address</th>
                                        <th>Contact Person</th>
                                        <th>Bank account Number</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($cliets as $client)
                                    <tr>
                                        <td>{{$client->company_name}}</td>
                                        <td>{{$client->email}}</td>
                                        <td>{{$client->contact_number}}</td>
                                        <td>{{$client->address}}</td>
                                        <td>{{$client->contact_person}}</td>
                                        <td>{{$client->bank_account_number ?? 'N/A'}}</td>
                                        <td>
                                            <span 
                                            class="badge rounded-pill text-white px-3 py-2"
                                            style="{{ $client->status === 'Sold' 
                                                ? 'background-color: #4CAF50;'  /* Modern green for "Sold" */ 
                                                : 'background-color: #F44336;'  /* Modern red for other statuses */ }}">
                                            {{ $client->status }}
                                        </span>
                                        
                                           
                                        </td>
                                        <td>
                                            <button
                                             {{$client->status === 'Sold' ? 'disabled' : ''}} 
                                             data-bs-toggle="modal" data-bs-target="#clientModal" class="btn btn-outline-primary">
                                                View
                                            </button>
                                            @php
                                                $Name = $client->first_name.' '.$client->last_name;
                                                $clientId = $client->id;
                                            @endphp
                                   
                                              {{-- Modal for Viewing Client Details --}}
                                             <livewire:modals.client-info :name="$Name" :clientId="$clientId"/>
                                        </td>
                                    </tr>
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
