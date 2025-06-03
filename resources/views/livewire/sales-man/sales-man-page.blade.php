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
    <div class="col-lg-3 mb-4">
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

    <div class="col-lg-3 mb-4">
        <div style="background-color: #f6f6f6; color:#004998" class="card rounded-2 shadow-sm h-100 border-0">
            <div class="card-body d-flex flex-column justify-content-between">
                <div class="d-flex align-items-center mb-3">
                    <div class="me-3">
                        <i class="fas fa-user-clock fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase mb-bold mb-1">Total Approval (未成交客户总计)</h6>
                        <h3 class="fw-bold mb-0">{{$countedPending}}</h3>
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
                        <h3 class="fw-bold mb-0">{{$totalPending}}</h3>
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-car-front-fill" viewBox="0 0 16 16">
                        <path d="M2.52 3.515A2.5 2.5 0 0 1 4.82 2h6.362c1 0 1.904.596 2.298 1.515l.792 1.848c.075.175.21.319.38.404.5.25.855.715.965 1.262l.335 1.679q.05.242.049.49v.413c0 .814-.39 1.543-1 1.997V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.338c-1.292.048-2.745.088-4 .088s-2.708-.04-4-.088V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.892c-.61-.454-1-1.183-1-1.997v-.413a2.5 2.5 0 0 1 .049-.49l.335-1.68c.11-.546.465-1.012.964-1.261a.8.8 0 0 0 .381-.404l.792-1.848ZM3 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2m10 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2M6 8a1 1 0 0 0 0 2h4a1 1 0 1 0 0-2zM2.906 5.189a.51.51 0 0 0 .497.731c.91-.073 3.35-.17 4.597-.17s3.688.097 4.597.17a.51.51 0 0 0 .497-.731l-.956-1.913A.5.5 0 0 0 11.691 3H4.309a.5.5 0 0 0-.447.276L2.906 5.19Z"/>
                      </svg>
                   Finish Vehicle
                </button>
              </li>
              
              <li class="mb-2">
                <a href="/salesman/autorepair" class="btn btn-dark w-100 d-flex align-items-center gap-2 py-2 rounded-3 shadow-sm">
                  <i class="fas fa-wrench"></i> 
                  Auto Parts
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
     <div class="card-header d-flex justify-content-between align-items-center">
        <h6>Client List</h6>

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
        <table class="table">
            <thead>
                <tr>
                    <th>Sales List No</th>
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
                   <td>{{$client->salesList_no ?? 'N/A'}}</td>
                    <td>{{$client->company_name}}</td>
                    <td>{{$client->address}}</td>
                    <td>{{$client->email}}</td>
                    <td>{{$client->contact_number}}</td>
                    
                    <td>
                        @php
                            $style;
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
                        <button {{$status}} data-bs-target="#ModalChangeStatus_{{$client->id}}" wire:key="change-status-{{$client->id}}" data-bs-toggle="modal" 
                    
                            style="background-color: #004998"  class="btn rounded-0  text-light btn-sm">
                            <i class="fas fa-sync-alt"></i>
                            Change Status
                            (更改状态)
                        </button>
                    </td>
                </tr>
                 {{-- Modal updating status --}}    
                 <livewire:modals.client-status-update :clientId="$client->id" :wire:key="'client-status-update-'.$client->id"/>        
                @endforeach
            </tbody>
        </table>
        {{$clients->links()}}
    </div>
</div>

{{-- modal --}}
<livewire:modals.client-create/>
</div>
