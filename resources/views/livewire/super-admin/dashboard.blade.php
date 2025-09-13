  <div class="sb-nav-fixed">
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
    {{-- sidebar --}}
     @include('partials.superAdmin_nav')
     {{-- use include --}}
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4 fw-bold">Chief Administrator</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                    <div class="row">
                                        <!-- Total Pending Card -->
                        <div class="col-lg-3 mb-4">
                            <div class="card text-white" style="background-color: #004998">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <h5 class="mb-2">
                                            <i class="fas fa-hourglass-half me-2"></i>
                                            Total Pending (未成交客户总计 )
                                        </h5>
                                        <span class="fw-bold fs-4">{{$totalPending}}</span>
                                    </div>
                                    <i class="fas fa-clock fa-2x opacity-25"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Total Sold Card -->
                        <div class="col-lg-3 mb-4">
                            <div class="card text-white"  style="background-color: #004998">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <h5 class="mb-2">
                                            <i class="fas fa-user-check me-2"></i>
                                            Total Sold (已成交客户总计)
                                        </h5>
                                        <span class="fw-bold fs-4">
                                           {{$totalSold}}
                                        </span>
                                    </div>
                                    <i class="fas fa-cash-register fa-2x opacity-25"></i>
                                </div>
                            </div>
                        </div>

                           <!-- Total Approval Card -->
                        <div class="col-lg-3 mb-4">
                            <div class="card text-white"  style="background-color: #004998">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <h5 class="mb-2">
                                            <i class="fas fa-user-check me-2"></i>
                                            Total Approval (总批准)
                                        </h5>
                                        <span class="fw-bold fs-4">
                                           {{$totalApproval}}
                                        </span>
                                    </div>
                                    <i class="fas fa-cash-register fa-2x opacity-25"></i>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="card mb-4 border-0">
                       <div class="card-header d-flex justify-content-between align-items-center">
                         <div>
                             <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
                                <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/>
                              </svg>
                              client List
                             (客户列表)
                            </div>

                              <div class="d-flex justify-content-center align-items-center">
                                <input type="text" class="form-control" wire:model.live="ClientSearch" placeholder="Search clients...">
                                <button class="btn btn-primary" type="button" wire:click="applySearch">
                                    <i class="fas fa-search me-1"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body border-0">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Company Name</th>
                                        <th>Sales Executive</th>
                                        <th>Department</th>
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
                                        <td>{{$client->company_name}}</td>
                                        <td>{{$client->salesman->first_name . ' ' .$client->salesman->last_name }}</td>
                                        <td>{{$client->salesman->department}}</td>
                                        <td>{{$client->contact_number}}</td>
                                        <td>{{$client->email}}</td>
                                        <td>{{$client->address}}</td>
                                        <td>
                                            @php
                                                $Sold = $client->status === 'Sold' ? '卖出' : '待处理';

                                                $color = '';

                                                switch ($client->status) {
                                                    case 'Sold':
                                                         $color = 'background-color:  #004998;';
                                                        break;

                                                        case 'Pending':
                                                          $color = 'background-color: red;';
                                                           break;
                                                           
                                                     default:
                                                       $color = 'background-color: #4CAF50;';
                                                        break;
                                                }
                                            @endphp
                                            <span
                                             style="{{$color}}"
                                            class="badge rounded-pill text-white px-3 py-2">
                                            {{ $client->status .' / '. $Sold}}
                                        </span>
                                        </td>
                                        <td>
                                        <button data-bs-toggle="modal" data-bs-target="#viewClientDetails_{{$client->id}}" style="background-color: #004998" class="btn text-light rounded-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                              </svg>
                                            查看更多
                                        </button>
                                        <button data-bs-target="#EditClientIfo_{{$client->id}}" wire:key="'edit-info-'.$client->id "  data-bs-toggle="modal" class="btn text-light rounded-0"  style="background-color: #004998">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                              </svg>
                                           (编辑)
                                        </button>
                                            <button
                                             class="btn btn-outline-danger rounded-0"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteClientModal"
                                                wire:click="$dispatch('open-delete-modal', { clientId: {{ $client->id }} })"
                                            >
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                            </svg>
                                                Delete(删除客户端)
                                            </button>


                                    </tr>
                                    {{-- for show details ng mga clients --}}
                                    <livewire:modals.view-client-details :clientId="$client->id" :wire:key="'view-client-'.$client->id" />

                                    {{-- for Editing the Client Information --}}
                                    <livewire:modals.edit-client-info-for-admin :clientId="$client->id" :wire:key=" 'edit-client-info-'.$client->id "/>
                                    @endforeach

                                </tbody>
                            </table>
                            {{ $clientList->links() }}
                        </div>
                    </div>
                </div>
                {{-- dynamic modal delete --}}
                <livewire:modals.client-delete-part2/> 
                
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
