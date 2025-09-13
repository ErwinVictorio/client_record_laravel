<div>
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="{{route('superAdminDashboard.view')}}">Dashbaord</a>
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
          @include('partials.superAdmin_nav')
        <div id="layoutSidenav_content">
            <main style="width: 100%">
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Manage  Sales Executive (业务员信息管理)</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="{{route('superAdminDashboard.view')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">sales executive</li>
                    </ol>

                      <nav class="mb-3">
                        <button data-bs-toggle="modal" data-bs-target="#crerateSalesman" style="background-color: #004998" class="btn text-light rounded-0">
                            New  Sales Executive (新业务员)
                        </button>
                      </nav>
                       
                      {{-- modal --}}
                      <livewire:modals.create-salesman/>

                    <div class="card mb-4">
                             <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
                                <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/>
                              </svg>
                            Salesman List
                            (业务员名单)
                            </div>

                          <div class="d-flex justify-content-center align-items-center">
                                <input type="text" class="form-control" wire:model.live="salesManSearch" placeholder="Search salesman...">
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
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Middle Name</th>
                                        <th>NickName</th>
                                        <th>Department</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($salesman as $user)
                                    <tr>
                                        <td>{{$user->first_name}}</td>
                                        <td>{{$user->last_name}}</td>
                                        <td>{{$user->middle_name}}</td>
                                        <td>{{$user->NickName}}</td>
                                        <td>{{$user->department}}</td>
                                        <td>
                                            <button style="background-color:  #004998" data-bs-target="#ModalViewSalesManInfo_{{$user->id}}" data-bs-toggle="modal" class="btn text-light rounded-0">
                                                <i class="fas fa-eye"></i>
                                                View (查看)
                                            </button>
                                            
                                            <button data-bs-toggle="modal" data-bs-target="#EditSalesman_{{$user->id}}" style="background-color:  #004998" class="btn text-light rounded-0">
                                                <i class="fas fa-pen"></i>
                                                Edit (编辑)
                                            </button>
                                            
                                            <button data-bs-target="#deleteSalesmanModal_{{$user->id}}" data-bs-toggle="modal" class="btn btn-danger rounded-0">
                                                <i class="fas fa-trash"></i>
                                                Delete (删除)
                                            </button>
                                        </td>
                                    </tr>
                                     {{-- Modal for Viewing Salesman Info  --}}
                                    <livewire:modals.view-salesman-info wire:key='view-salesman-info{{$user->id}}' :salesmanID="$user->id" />
                                    {{-- Modal for Edit --}}
                                    <livewire:modals.edit-salesman wire:key='edit-salesman{{$user->id}}' :salesmanID="$user->id" />
                                    {{--Modal for Delete Confirmation  --}}
                                    <livewire:modals.delete-salesman wire:key='delete-salesman{{$user->id}}' :salesmanID='$user->id'/>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                      {{ $salesman->links() }}

                  
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
