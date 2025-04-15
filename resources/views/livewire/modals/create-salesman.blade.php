<div>
    <div wire:ignore.self class="modal fade" id="crerateSalesman" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="staticBackdropLabel">Create New Salesman</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             
            </div>
             @if (session()->has('success'))
            <div 
                x-data="{ show: true }" 
                x-init="setTimeout(() => show = false, 2000)" 
                x-show="show" 
                class="alert alert-success"
            >
                {{ session('success') }}
            </div>
        @endif

            <form wire:submit='create_salesman'>
            <div class="modal-body">
                <div class="form-floating mb-3">
                    <input wire:model='first_name' type="text" class="form-control" id="FirstName" placeholder="First Name">
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
                    <input wire:model='middle_name' type="text" class="form-control" id="LastName" placeholder="Middle Name">
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


                  <div class="form-floating mb-3">
                    <input wire:model='password' type="password" class="form-control" id="Password" placeholder="Password">
                    <label for="Password">Password</label>
                    @error('password')
                    <span class="text-danger">{{$message}}</span>
                  @enderror
                  </div>

                  <div class="form-floating mb-3">
                    <input wire:model='password_confirmation' type="password" class="form-control" id="Password" placeholder="Password">
                    <label for="Password">Confirm Password</label>
                    @error('password_confirmation')
                    <span class="text-danger">{{$message}}</span>
                  @enderror
                  </div>

                  <select wire:model='department' class="form-select form-select-lg mb-3" aria-label="Large select example">
                    <option selected>Select Department</option>
                    <option value="Deparment1">Deparment1</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                  </select>
                  @error('department')
                  <span class="text-danger">{{$message}}</span>
                @enderror
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill-add" viewBox="0 0 16 16">
                    <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                    <path d="M2 13c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4"/>
                  </svg>
                Crete
            </button>
            </div>
        </form>
          </div>
        </div>
      </div>
</div>
