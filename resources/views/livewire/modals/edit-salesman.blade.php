<div>
    <div wire:ignore.self class="modal fade" id="EditSalesman_{{$salesmanID}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div  class="modal-header">
              <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Salesman Details</h1>
               <livewire:refresh-page/>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="p-2">
              @if (session()->has('success'))
              <x-alert-message :color=" 'alert-success' ">
                {{session('success')}}
              </x-alert-message> 
             @endif
            </div>
            <form wire:submit='UpdateSalesman'>
            <div class="modal-body">
                <div class="form-floating mb-3">
                    <input wire:model='first_name' type="text"  class="form-control" id="FirstName" placeholder="First Name">
                    <label for="FirstName">First Name</label>
                    @error('first_name')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                  </div>

                  <div class="form-floating mb-3">
                    <input wire:model='last_name' type="text" class="form-control" id="LastName" placeholder="Last Name">
                    <label for="LastName">Last Name</label>
                    @error('last_name')
                    <span class="text-danger">{{$message}}</span>
                  @enderror
                  </div>

                  <div class="form-floating mb-3">
                    <input wire:model='middle_name' type="text" class="form-control" id="middle_name" placeholder="Middle Name">
                    <label for="middle_name">Middle Name</label>
                    @error('middle_name')
                    <span class="text-danger">{{$message}}</span>
                  @enderror
                  </div>

                  <div class="form-floating mb-3">
                    <input wire:model='username' type="text" class="form-control" id="Username" placeholder="Username">
                    <label for="Username">Username</label>
                    @error('username')
                    <span class="text-danger">{{$message}}</span>
                  @enderror
                  </div>
                  
                  <select wire:model='department' class="form-select form-select-lg mb-3" aria-label="Large select example">
                    <option value="{{$department}}" selected>{{$department}}</option>
                    @foreach ($departments as $department )
                    <option selected value="{{$department->department_name}}">
                      {{$department->department_name}}
                    </option>
                    @endforeach
                  </select>
                  @error('department')
                  <span class="text-danger">{{$message}}</span>
                @enderror
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button style="background-color:  #004998" type="submit" class="btn text-light rounded-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                    <path d="M11 2H9v3h2z"/>
                    <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                  </svg>
                 Save Changes
            </button>
            </div>
        </form>
          </div>
        </div>
      </div>
</div>
