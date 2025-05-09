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
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link" href="/cashier/dashboard">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>

                        <div class="sb-sidenav-menu-heading">Reports</div>
                        <a class="nav-link" href="department-summary">
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
                            <div class="card text-white shadow bg-danger rounded-4 h-100 border-0">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="me-3">
                                            <i class="fas fa-user-clock fa-2x"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-uppercase fw-semibold mb-1">Total Registered Clients (已登记客户总计)</h6>
                                            <h3 class="fw-bold mb-0">{{$countedAprove}}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
       
          
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div style="background-color:  #004998" class="card text-white shadow rounded-4 h-100 border-0">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="me-3"> 
                                            <i class="fas fa-user-check fa-2x"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-uppercase fw-semibold mb-1">Total Sold Client (总销售出客户)</h6>
                                            <h3 class="fw-bold mb-0">{{$counttedSoldClient}}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                  
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-table me-1"></i>
                                Client List
                                (客户列表)
                            </div>
                           
                           <div class="d-flex justify-content-center align-items-center">
                            <input type="text" class="form-control" wire:model.live="clientSearch" placeholder="Search clients...">
                          <div>
                            <button class="btn btn-primary rounded-0" type="button" wire:click="applySearch">
                                <i class="fas fa-search me-1"></i>
                            </button>
                          </div>
                        </div>
                        </div>
           
                        <div class="card-body">
                            <table class="table"
                                <thead>
                                    <tr>
                                        <th>Company Name</th>
                                        <th>Sales Executive</th>
                                        <th>Department</th>
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
                                    @foreach ($clientList as $client)
                                    <tr>
                                        <td>{{$client->company_name}}</td>
                                        <td>{{$client->salesman->first_name . ' ' .$client->salesman->last_name}}</td>
                                        <td>{{$client->salesman->department}}</td>
                                        <td>{{$client->email}}</td>
                                        <td>{{$client->contact_number}}</td>
                                        <td>{{$client->address}}</td>
                                        <td>{{$client->contact_person}}</td>
                                        <td>{{$client->bank_account_number ?? 'N/A'}}</td>
                                        <td>
                                            @php
                                                $style = '';
                                                $label = '';

                                                switch ($client->status) {
                                                    case 'Sold':
                                                           $style = 'background-color:  #004998;';
                                                           $label = '已售出';
                                                        break;

                                                        case 'Approve':
                                                        $style = 'background-color: #F44336;';
                                                            $label = '批准';
                                                            break;
                                                    default:
                                                        # code...
                                                        break;
                                                }
                                            @endphp
                                            <span 
                                            style="{{$style}}"
                                            class="badge rounded-pill text-white px-3 py-2">
                                              {{$client->status}}
                                            {{$label}}
                                        </span>
            
                                        </td>
                                        <td>
                                            <button
                                             style="background-color: #004998 "
                                             {{$client->status === 'Sold' ? 'disabled' : ''}} 
                                             data-bs-toggle="modal" data-bs-target="#clientModal_{{$client->id}}" wire:key='record-transaction-{{$client->id}}' class="btn text-light">
                                                 Record Transaction
                                                 (记录交易)
                                            </button>
                                        </td>
                                    </tr>
                                     {{-- Modal for Viewing Client Details dapat nasa labas ng tr para hindi mag error --}}
                                     <livewire:modals.client-info :clientId="$client->id" wire:key='clientInfo-{{$client->id}}'/>
                                    @endforeach
                                </tbody>
                               
                            </table>
                            {{$clientList->links()}}
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
