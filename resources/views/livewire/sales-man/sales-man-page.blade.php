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
                        <h6 class="text-uppercase mb-bold mb-1">Total Sold (已成交客户总计)</h6>
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
                        <h6 class="text-uppercase mb-bold mb-1">Total Pending (未成交客户总计)</h6>
                        <h3 class="fw-bold mb-0">{{$countedPending}}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
   </section>

   
   <div class="card">
    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
      <div class=" d-flex gap-2">

        <div class="dropdown">
            <button style="background-color:#004998 " class="btn text-light dropdown-toggle rounded-pill px-4 py-2 shadow" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-users me-2"></i> CLIENT TYPE
            </button>
          
            <ul class="dropdown-menu dropdown-menu-end p-3 border-0 shadow rounded-4 mt-2" style="min-width: 250px;">
              <li class="mb-2">
                <button class="btn btn-dark w-100 d-flex align-items-center gap-2 py-2 rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#add_client">
                  <i class="bi bi-person-plus"></i> 
                  Add New
                   <small class="text-muted ms-auto">(增加新客户)</small>
                </button>
              </li>
              
              <li class="mb-2">
                <a href="/salesman/autorepair" class="btn btn-dark w-100 d-flex align-items-center gap-2 py-2 rounded-3 shadow-sm">
                  <i class="fas fa-wrench"></i> 
                  Auto Repair
                </a>
              </li>
              
              <li>
                <a href="/salesman/repair-and-maintenance" class="btn btn-dark w-100 d-flex align-items-center gap-1 py-2 rounded-3 shadow-sm">
                  <i class="fas fa-tools"></i>
                   Repair and Maintenance
                </a>
              </li>
            </ul>
          </div>
          
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
                            $label = '';

                            switch ($client->status) {
                                case 'Approve':
                                      $style = 'background-color: #47ba9b;';
                                      $label = '(批复)';
                                    break;

                                  case 'Sold':
                                    $style = 'background-color: #004998;';
                                     $label = '(已售)';
                                    break;

                                    default:
                                     $style = 'background-color:  #ff0000;';
                                     $label = '(编辑)';
                              }
                        @endphp
                        <span 
                        class="badge rounded-pill text-white px-3 py-2"
                        style="{{$style}}">
                        {{ $client->status }}
                         {{$label}}
                      <spa/>
                    </td>
                    <td>
                        @php
                            $status = $client->status === "Sold" ? "disabled" : '';
                        @endphp
                        <button {{$status}}  data-bs-target="#ModalChangeStatus_{{$client->id}}" data-bs-toggle="modal" 
                            style="background-color: #004998"  class="btn rounded-0  text-light btn-sm">
                            <i class="fas fa-sync-alt"></i>
                            Change Status
                            (更改状态)
                        </button>

                        <button {{$status}} data-bs-target="#edit_client_{{$client->id}}" data-bs-toggle="modal" style="background-color: #0609cd  ;" class="btn btn-sm btn-sm rounded-0 text-light">
                            <i class="fas fa-pen"></i>
                            Edit
                            (编辑)
                        </button>
                        <button {{$status}} data-bs-target="#DeleteClient_{{$client->id}}" data-bs-toggle="modal" 
                            class="btn rounded-0 btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                            Delete
                            (删除)
                        </button>
                    </td>
                </tr>
                 {{-- Modal updating status --}}  
                 <livewire:modals.client-status-update :clientId="$client->id"/>        

                 {{-- Modal for Editing Client Info --}}
                 <livewire:modals.edit-client-info :clientId="$client->id" />

                 {{--Modal Deleting client --}}
                 <livewire:modals.delete-client :clientId="$client->id"/>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- modal --}}
<livewire:modals.client-create/>
</div>
