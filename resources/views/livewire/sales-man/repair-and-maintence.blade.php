<div>
    <header style="background-color:  #004998" class="container-fluid p-4 d-flex justify-content-between align-items-center">
     <button data-bs-toggle="modal" data-bs-target="#createAtutoRepair" class="btn rounded-3 btn-sm btn-light">
       New Record
    </button>
      <a  href="/salesman/dashboard" class="btn btn-light" href="#">
       Back
      </a>
    </header>
 
    <section class="container-fluid mt-5">
      <table wire:ignore id="datatablesSimple">        
       <thead>
         <tr>
             <th>#</th>
             <th>Company Name</th>
             <th>Job Order Number</th>
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
     @foreach ($records as $record )
     <tr>
      <td>{{$record->id}}</td>
      <td>{{$record->company_name}}</td>
      <td>{{$record->job_order_number ?? 'N/A'}}</td>
      <td>{{$record->address}}</td>
      <td>{{$record->email}}</td>
      <td>{{$record->contact_number}}</td>
      <td>{{$record->contact_person}}</td>
      <td>{{$record->contact_number_person}}</td>
      <td>{{$record->bank_account_number}}</td>
       <td>
          <button data-bs-target="#EditAtutoRepairMaintence_{{$record->id}}" data-bs-toggle="modal" style="background-color: #004998" class="btn text-light">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
              <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
              <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
            </svg>
          </button>

          <button data-bs-toggle="modal" data-bs-target="#DeleteMaintenanceRecord_{{$record->id}}" style="background-color: #004998" class="btn text-light">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
          </svg>
          </button>
       </td>
     </tr>
     {{-- Confirm Delete --}}
     <livewire:modals.edit-repair-and-maintence :recordId="$record->id" />
      {{-- modal for Deleting Record --}}
      <livewire:modals.delete-record-for-repair-and-maintenance :recordId="$record->id"/>
     @endforeach
     </tbody>
      </table>
    </section>
 
 
    {{-- modal for creating Record --}}
    <livewire:modals.create-repair-and-maintenace-record/>
 </div>
 