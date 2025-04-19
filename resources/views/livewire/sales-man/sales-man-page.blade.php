<div>
   <header style="background-color: #004998" class="container-fluid d-flex justify-content-between align-items-center  p-3">
    <button class="btn btn-light rounded-5 btn-sm p-2 shadow-sm d-flex  gap-1 justify-content-center align-items-center" data-bs-toggle="modal" data-bs-target="#add_client">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus" viewBox="0 0 16 16">
            <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
            <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5"/>
          </svg>
        Add New
    </button>

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

    <div class="col-xl-3 col-md-6 mb-4">
        <div style="background-color: #f6f6f6; color:#004998" class="card rounded-2 shadow-sm h-100 border-0">
            <div class="card-body d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center mb-3">
                    <div class="me-3">
                        <i class="fas fa-user-clock fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase mb-bold mb-1">Total Approve</h6>
                        <h3 class="fw-bold mb-0">{{$totalApprove}}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
   </section>

   
   <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
            <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/>
          </svg>
         Client list
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
                        @endphp
                        <button {{$status}}  data-bs-target="#ModalChangeStatus_{{$client->id}}" data-bs-toggle="modal" 
                            style="background-color: #004998"  class="btn rounded-0  text-light btn-sm">
                            <i class="fas fa-sync-alt"></i>
                            Change Status
                        </button>
                        <button data-bs-target="#delete_client{{$client->id}}" data-bs-toggle="modal" 
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
