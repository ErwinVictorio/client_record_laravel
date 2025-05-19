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
                    <h1 class="mt-4">Repair & Maintenance Records (维修与保养记录)</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">repair & maintenance records (维修与保养记录)</li>
                    </ol>
             
                    {{-- table --}}
                    <div class="card mb-4">
                          <div class="card-header d-flex justify-content-between align-items-center">
                             Repair And Maintenance Record List
                              (记录列表)
                            <div class="d-flex justify-content-center align-items-center">
                                <input type="text" class="form-control" wire:model.live="clientSearch" placeholder="Search clients...">
                            <div>
                                <button class="btn btn-primary rounded-0" type="button" wire:click="ApplySearch">
                                    <i class="fas fa-search me-1"></i>
                                </button>
                            </div>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Job Order Number</th>
                                        <th>Company Name</th>
                                        <th>Contact Number</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>Contact Person</th>
                                        <th>Contact Number</th>
                                        <th>Bank Account Number</th>
                                         <th>Action</th>
                                    </tr>
                                </thead>
                                 
                                <tbody>
                                @foreach ($records as $record )
                                    <tr>
                                        <td>{{$record->job_order_number}}</td>
                                        <td>{{$record->company_name}}</td>
                                        <td>{{$record->contact_number}}</td>
                                        <td>{{$record->email}}</td>
                                        <td>{{$record->address}}</td>
                                        <td>{{$record->contact_person}}</td>
                                        <td>{{$record->contact_number_person}}</td>
                                        <td>{{$record->bank_account_number ?? 'N/A' }}</td>
                                        <td>
                                            <button data-bs-target="#EditAtutoRepairMaintence_{{$record->id}}" data-bs-toggle="modal" style="background-color: #0d629b" class="btn text-light">
                                              Edit
                                            </button>
                                            <button data-bs-target="#DeleteMaintenanceRecord_{{$record->id}}" data-bs-toggle="modal" class="btn btn-danger">
                                              Delete
                                            </button>
                                        </td>
                                    </tr>
                                    {{-- For Editing Modal --}}
                                    <livewire:modals.edit-repair-and-maintence wire:key='edit-maintenace{{$record->id}}' :recordId="$record->id" />
                                    <livewire:modals.delete-record-for-repair-and-maintenance wire:key='delete-maintenance{{$record->id}}' :recordId="$record->id" />
                                    @endforeach
                                </tbody>
                            </table>
                            {{$records->links()}}
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

