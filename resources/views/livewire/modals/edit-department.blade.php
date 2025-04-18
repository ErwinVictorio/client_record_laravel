<div>
    <div wire:ignore.self class="modal fade" id="EditDepartmentModal_{{$department_id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div style="background-color: #004998" class="modal-header text-light">
              <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Department</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form wire:submit='Update_Department'>
            <div class="modal-body">

                @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-check-circle-fill flex-shrink-0" viewBox="0 0 16 16" role="img" aria-label="Success:">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.97 11.03a.75.75 0 0 0 1.08-.02l3.992-4.99a.75.75 0 1 0-1.154-.958L7.477 9.417 5.383 7.323a.75.75 0 0 0-1.06 1.06l2.647 2.647z"/>
                    </svg>
                    <div>
                        {{session('success') }}
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
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
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy-fill" viewBox="0 0 16 16">
                    <path d="M0 1.5A1.5 1.5 0 0 1 1.5 0H3v5.5A1.5 1.5 0 0 0 4.5 7h7A1.5 1.5 0 0 0 13 5.5V0h.086a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5H14v-5.5A1.5 1.5 0 0 0 12.5 9h-9A1.5 1.5 0 0 0 2 10.5V16h-.5A1.5 1.5 0 0 1 0 14.5z"/>
                    <path d="M3 16h10v-5.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5zm9-16H4v5.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5zM9 1h2v4H9z"/>
                  </svg>                       
                Save Changes
            </button>
          </form>
            </div>
          </div>
        </div>
      </div>
</div>
