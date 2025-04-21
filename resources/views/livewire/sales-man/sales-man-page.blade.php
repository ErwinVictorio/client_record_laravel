<div>
   <header style="background-color: #004998" class="container-fluid d-flex justify-content-end align-items-center  p-3">
    <div class="btn-group">
        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user"></i>
        </button>
        <ul class="dropdown-menu">
           <livewire:auth.logout/>
        </ul>
      </div>
   </header>

   <section class="container-fluid row gap-3 p-5">
    <div class="col-xl-3 col-md-6 mb-4">
        <div style="background-color: #f6f6f6; color:#004998" class="card rounded-2 shadow-sm h-100 border-0">
            <div class="card-body d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center mb-3">
                    <div class="me-3">
                        <i class="fas fa-user-check fa-2x "></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase mb-bold mb-1">Total Sold</h6>
                        <h3 class="fw-bold mb-0">{{$countedSold}}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div style="background-color: #f6f6f6; color:#004998" class="card rounded-2 shadow-sm h-100 border-0">
            <div class="card-body d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center mb-3">
                    <div class="me-3">
                        <i class="fas fa-user-clock fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase mb-bold mb-1">Total Pending</h6>
                        <h3 class="fw-bold mb-0">{{$countedPending}}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

   </section>

   
   <div class="card">
    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
      <div>
        <button style="background-color: #004998" class="btn btn-light rounded-5 btn-sm text-light p-2 shadow-sm d-flex  gap-1 justify-content-center align-items-center" data-bs-toggle="modal" data-bs-target="#add_client">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus" viewBox="0 0 16 16">
                <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5"/>
              </svg>
            Add New
        </button>
      </div>
     
    </div>
    <div class="card-body">
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Company Name</th>
                    <th>Address</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($clients as $client)
                <tr>
                    <td>{{$client->id}}</td> 
                    <td>{{$client->company_name}}</td>
                    <td>{{$client->address}}</td>
                    <td>{{$client->email}}</td>
                    <td>{{$client->contact_number}}</td>
                    <td>
                        @php
                            $style;

                            switch ($client->status) {
                                case 'Approve':
                                      $style = 'background-color: #47ba9b;';
                                    break;

                                  case 'Sold':
                                    $style = 'background-color: #004998;';
                                    break;

                                    default:
                                     $style = 'background-color:  #ff0000;';
                            }

                            
                        @endphp
                        <span 
                        class="badge rounded-pill text-white px-3 py-2"
                        style="{{$style}}">
                        {{ $client->status }}
                    </td>
                    <td>
                        @php
                            $status = $client->status === "Sold" ? "disabled" : '';
                            $disabledDeleteButton = $client->status !== 'Pending' ? 'disabled' : '';
                        @endphp
                        <button {{$status}}  data-bs-target="#ModalChangeStatus_{{$client->id}}" data-bs-toggle="modal" 
                            style="background-color: #004998"  class="btn rounded-0  text-light btn-sm">
                            <i class="fas fa-sync-alt"></i>
                            Change Status
                        </button>
                        <button {{$disabledDeleteButton}} data-bs-target="#delete_client{{$client->id}}" data-bs-toggle="modal" 
                            class="btn rounded-0 btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                            Delete
                        </button>
                    </td>
                </tr>
                 {{-- Modal --}}  
                 <livewire:modals.client-status-update :clientId="$client->id"/>                                                                                                                                                                                                                                     
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- modal --}}
<livewire:modals.client-create/>
</div>
