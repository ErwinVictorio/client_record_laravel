<div>
    <header style="background-color:  #004998"
        class="container-fluid p-4 d-flex justify-content-between align-items-center">
        <button data-bs-toggle="modal" data-bs-target="#add_client" class="btn rounded-3 btn-sm btn-light">
            New Record
        </button>
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
                    Create Finish Vehicle Records
                </li>
            </ol>
        </nav>
    </div>
    <section class="container-fluid mt-3">
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

                {{-- Modal updating status --}}
                <livewire:modals.client-status-update :clientId="$client->id"
                    :wire:key="'client-status-update-'.$client->id" />
                @endforeach

            </tbody>
        </table>
    </section>

    {{-- modal for creating Record --}}
    <livewire:modals.client-create />
    {{$ClientRecord->links()}}
</div>