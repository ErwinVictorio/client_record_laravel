<div>
    <header style="background-color:  #004998" class="container-fluid p-4 d-flex justify-content-between align-items-center">
     <button data-bs-toggle="modal" data-bs-target="#createAtutoRepairMaintenance" class="btn rounded-3 btn-sm btn-light">
       New Record
    </button>
      <a  href="/salesman/dashboard" class="btn btn-light" href="#">
       Back
      </a>
    </header>
 
    <div class="container-fluid mt-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb  rounded-3 p-2">
          <li class="breadcrumb-item">
            <a href="/salesman/dashboard" class="text-decoration-none text-primary">Sales Executive</a>
          </li>
          <li class="breadcrumb-item active text-capitalize" aria-current="page">
           Auto Repair and Maintenance Records
          </li>
        </ol>
      </nav>
    </div>
    <section class="container-fluid mt-3">
      <div class="row justify-content-end mb-3">
        <div class="col-12 col-md-5 col-lg-4">
          <label for="maintenance-record-search" class="form-label">Search Records</label>
          <input
            id="maintenance-record-search"
            type="search"
            class="form-control"
            wire:model.live.debounce.300ms="search"
            placeholder="Search by company name or serial number"
          >
        </div>
      </div>
      <div class="table-responsive">
      <table class="table">
       <thead>
         <tr>
             <th>#</th>
             <th>Company Name</th>
             <th>Serial / Plate Number</th>
             <th>Date Sold</th>
             <th>Address</th>
             <th>Email</th>
             <th>Contact Number</th>
             <th>Contact Person</th>
             <th>Contact Number</th>
             <th>Bank Account Number</th>
             <th>Action</th>
         </tr>
     </thead>
     <tbody>

     @forelse ($records as $record )
     @php
       $maintenanceVehicles = $record->vehicle_specifications ?? [];
       $primaryVehicle = $maintenanceVehicles[0] ?? [];
       $serialOrPlateNumber = $primaryVehicle['serial_or_plate_number'] ?? $record->serial_number;
     @endphp
     <tr>
      <td>{{$record->id}}</td>
      <td>{{$record->company_name}}</td>
      <td>{{$serialOrPlateNumber ?? 'N/A'}}</td>
      <td>{{$record->date_sold?->format('F d, Y') ?? 'N/A'}}</td>
      <td>{{$record->address}}</td>
      <td>{{$record->email}}</td>
      <td>{{$record->contact_number}}</td>
      <td>{{$record->contact_person}}</td>
      <td>{{$record->contact_number_person}}</td>
      <td>{{$record->bank_account_number ?? 'N/A'}}</td>
      <td>
        <button data-bs-target="#MaintenanceRecordInfo_{{$record->id}}" data-bs-toggle="modal" class="btn btn-outline-primary btn-sm">
          View
        </button>
        <button data-bs-target="#EditAtutoRepairMaintence_{{$record->id}}" data-bs-toggle="modal" style="background-color: #0d629b" class="btn text-light btn-sm">
          Edit
        </button>
      </td>
     </tr>
     @empty
     <tr>
       <td colspan="11" class="text-center text-muted py-4">No matching maintenance records found.</td>
     </tr>
     @endforelse
     </tbody>
      </table>
      </div>
      @foreach ($records as $record)
        <livewire:modals.edit-repair-and-maintence
          :recordId="$record->id"
          :managesJobOrderNumber="false"
          :wire:key="'salesman-edit-maintenance-'.$record->id"
        />
        @include('livewire.modals.maintenance-record-info', [
          'record' => $record,
          'showJobOrderNumber' => false,
        ])
      @endforeach
      {{$records->links()}}
    </section>

    {{-- modal for creating Record --}}
    <livewire:modals.create-repair-and-maintenace-record :managesJobOrderNumber="false" />
 </div>
