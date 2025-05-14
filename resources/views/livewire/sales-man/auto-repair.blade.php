<div>
   <header style="background-color:  #004998" class="container-fluid p-4 d-flex justify-content-between align-items-center">
    <button data-bs-toggle="modal" data-bs-target="#createAtutoRepair" class="btn rounded-3 btn-sm btn-light">
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
         Auto Parts Records 
        </li>
      </ol>
    </nav>
  </div>

   <section class="container-fluid mt-3">
     <table class="table">        
      <thead>
        <tr>
            <th>#</th>
            <th>Company Name</th>
            <th>Stock out Form No</th>
            <th>Address</th>
            <th>Email</th>
            <th>Contact Number</th>
            <th>Contact Person</th>
            <th>Contact Number</th>
            <th>Bank Account Number</th>
            {{-- <th>Action</th> --}}
        </tr>
    </thead>

    @foreach ($records as $record )
      <tr>
        <td>{{$record->id}}</td>
         <td>{{$record->company_name}}</td>
         <td>{{$record->stock_out_number}}</td>
         <td>{{$record->address}}</td>
         <td>{{$record->email}}</td>  
         <td>{{$record->contact_number}}</td>
         <td>{{$record->contact_person}}</td>
         <td>{{$record->contact_number_person}}</td>
         <td>{{$record->bank_account_number === '' ? 'N/A' : $record->bank_account_number}}</td>
      </tr>
    @endforeach

    <tbody>
    </tbody>
     </table>
     {{$records->links()}}
   </section>


   {{-- modal for creating Record --}}
   <livewire:modals.create-auto-repair-records/>
</div>
