<div>
    <div wire:ignore.self class="modal fade" id="createModalDepartment" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div style="background-color: #004998" class="modal-header text-light">
              <h1 class="modal-title fs-5" id="staticBackdropLabel">New Department</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form wire:submit='Create_Department'>
            <div class="modal-body">

              @if (session()->has('success'))
                 <x-alert-message :color="'alert-success'">
                  {{session('success')}}
                 </x-alert-message>
              @endif

              @if (session()->has('error'))
                <x-alert-message :color=" 'alert-danger' ">
                  {{session('error')}}
                </x-alert-message>
              @endif

                <div class="form-floating mb-3">
                    <input wire:model='department_name' type="text" class="form-control" id="DepartmentName" placeholder="Department Name">
                    <label for="DepartmentName">Department Name</label>
                    @error('department_name')
                      <span class="text-danger">
                        {{$message}}
                      </span>
                    @enderror
                  </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button style="background-color: #004998" type="submit" class="btn text-light">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
                  </svg>
                Create Now
            </button>
          </form>
            </div>
          </div>
        </div>
      </div>
</div>
