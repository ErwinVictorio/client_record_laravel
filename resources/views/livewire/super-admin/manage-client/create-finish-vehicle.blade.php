<div>
    <header style="background-color:  #004998"
        class="container-fluid p-4 d-flex justify-content-end align-items-center">
        <a href="{{route('superAdminDashboard.view')}}" class="btn btn-light" href="#">
            Back
        </a>
    </header>

    <div class="container-fluid mt-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb  rounded-3 p-2">
                <li class="breadcrumb-item">
                    <a href="{{route('superAdminDashboard.view')}}"
                        class="text-decoration-none text-primary">Dashboard</a>
                </li>
                <li class="breadcrumb-item active text-capitalize" aria-current="page">
                    My Client
                </li>
            </ol>
        </nav>
    </div>

    <section class="container-fluid row gap-3 p-5">
        <div class="col-lg-3 mb-4">
            <div style="background-color: #f6f6f6; color:#004998" class="card rounded-2 shadow-sm h-100 border-0">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <i class="fas fa-user-check fa-2x "></i>
                        </div>
                        <div>
                            <h6 class="text-uppercase mb-bold mb-1">Total Sold (已成交客户总计)</h6>
                            <h3 class="fw-bold mb-0">{{$countedSold ?? 0}}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 mb-4">
            <div style="background-color: #f6f6f6; color:#004998" class="card rounded-2 shadow-sm h-100 border-0">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <i class="fas fa-user-clock fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="text-uppercase mb-bold mb-1">Total Approval (未成交客户总计)</h6>
                            <h3 class="fw-bold mb-0">{{$countedPending ?? 0}}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3  mb-4">
            <div style="background-color: #f6f6f6; color:#004998" class="card rounded-2 shadow-sm h-100 border-0">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <i class="fas fa-user-clock fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="text-uppercase mb-bold mb-1">Total Pending (未成交客户总计)</h6>
                            <h3 class="fw-bold mb-0">{{$totalPending ?? 0}}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="container-fluid mt-3">
        <div>
            <div class=" d-flex gap-2">
                <div class="dropdown">
                    <button style="background-color:#004998 "
                        class="btn text-light dropdown-toggle rounded-pill px-4 py-2 shadow" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-users me-2"></i> CLIENT TYPE
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end p-3 border-0 shadow rounded-4 mt-2"
                        style="min-width: 250px;">
                        <li class="mb-2">
                            <button class="btn btn-dark w-100 d-flex align-items-center gap-2 py-2 rounded-3 shadow-sm"
                                data-bs-toggle="modal" data-bs-target="#add_client">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-car-front-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M2.52 3.515A2.5 2.5 0 0 1 4.82 2h6.362c1 0 1.904.596 2.298 1.515l.792 1.848c.075.175.21.319.38.404.5.25.855.715.965 1.262l.335 1.679q.05.242.049.49v.413c0 .814-.39 1.543-1 1.997V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.338c-1.292.048-2.745.088-4 .088s-2.708-.04-4-.088V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.892c-.61-.454-1-1.183-1-1.997v-.413a2.5 2.5 0 0 1 .049-.49l.335-1.68c.11-.546.465-1.012.964-1.261a.8.8 0 0 0 .381-.404l.792-1.848ZM3 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2m10 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2M6 8a1 1 0 0 0 0 2h4a1 1 0 1 0 0-2zM2.906 5.189a.51.51 0 0 0 .497.731c.91-.073 3.35-.17 4.597-.17s3.688.097 4.597.17a.51.51 0 0 0 .497-.731l-.956-1.913A.5.5 0 0 0 11.691 3H4.309a.5.5 0 0 0-.447.276L2.906 5.19Z" />
                                </svg>
                                Finish Vehicle
                            </button>
                        </li>

                        <li class="mb-2">
                            <button data-bs-toggle="modal" data-bs-target="#createAtutoRepair"
                                class="btn btn-dark w-100 d-flex align-items-center gap-2 py-2 rounded-3 shadow-sm">
                                <i class="fas fa-wrench"></i>
                                Auto Parts
                            </button>
                        </li>

                        <li>
                            <button data-bs-toggle="modal" data-bs-target="#createAtutoRepairMaintenance"
                                class="btn btn-dark w-100 d-flex align-items-center gap-1 py-2 rounded-3 shadow-sm">
                                <i class="fas fa-tools"></i>
                                Repair and Maintenance
                            </button>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Sales List No</th>
                    <th>Company Name</th>
                    <th>Address</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>Status</th>
                    <th>Bank Account</th>
                    <th>Cretaed At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

                @foreach ( $ClientRecord as $client )
                <tr>
                    <td>
                        {{$client->salesList_no ?? 'N/A'}}
                    </td>
                    <td>
                        {{$client->company_name ?? 'N/A'}}
                    </td>
                    <td>
                        {{$client->address ?? 'N/A'}}
                    </td>
                    <td>
                        {{$client->email ?? 'N/A'}}
                    </td>
                    <td>
                        {{$client->contact_number ?? 'N/A'}}
                    </td>
                    <td>

                        @php
                        $status = '';
                        $label = '';

                        switch ($client->status) {
                        case 'For Approval':
                        $style = 'background-color: #47ba9b;';
                        $label = '(供批准)';
                        break;

                        case 'Sold':
                        $style = 'background-color: #004998;';
                        $label = '(已售)';
                        break;

                        default:
                        $style = 'background-color: #ff0000;';
                        $label = '(编辑)';
                        }
                        @endphp


                        <span class="badge" style="{{$style}}">
                            {{$client->status ?? 'N/A'}}
                            {{$label}}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-secondary">
                            {{$client->bank_account_number ?? 'N/A'}}
                        </span>
                    </td>
                    <td>
                        {{$client->created_at ?? 'N/A'}}
                    </td>

                    <td>

                        @php
                        $status = $client->status === 'Sold' ? 'disabled' : '';
                        @endphp
                        <span class="badge">
                            <button class="btn btn-outline-light" {{$status}} style="background-color: #004998;"
                                data-bs-target="#ModalChangeStatus_{{$client->id}}"
                                wire:key="change-status-{{$client->id}}" data-bs-toggle="modal">
                                <i class="fas fa-sync-alt"></i>
                                Change Status
                                (更改状态)
                            </button>
                        </span>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
        @foreach ($ClientRecord as $client)
        <livewire:modals.client-status-update
            :clientId="$client->id"
            :wire:key="'super-admin-finish-client-status-update-'.$client->id"
        />
        @endforeach
    </section>

    {{-- modal for creating Record --}}
    <livewire:modals.client-create />

    {{-- Modal For Creating Repair and maintenance --}}
    <livewire:modals.create-repair-and-maintenace-record />

        {{-- Modal for Creathing Auto parts Records --}}
        <livewire:modals.create-auto-repair-records />

        {{$ClientRecord->links()}}


</div>
