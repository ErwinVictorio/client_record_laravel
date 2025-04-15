<div>
    <div wire:ignore.self class="modal fade" id="ModalChangeStatus_{{$clientId}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Status</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
    
            @if (session()->has('success'))
                <div class="alert alert-success m-2">
                    {{ session('success') }}
                </div>
            @endif
            @if (session()->has('error'))
                <div class="alert alert-danger m-2">
                    {{ session('error') }}
                </div>
            @endif
      
    
            <form wire:submit.prevent='change_status'> 
              <div class="modal-body">
                  <select wire:model='SelectedStatus' class="form-select form-select-lg mb-3">
                      <option value="">Select Status</option>
                      <option value="Approve">Approve</option>
                      <option value="Pending">Pending</option>
                  </select>
                  @error('SelectedStatus') 
                      <span class="text-danger">{{ $message }}</span> 
                  @enderror
              </div>
      
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button  style="background-color: #004998" type="submit" class="btn text-light">Submit</button>
              </div>
            </form>
          </div>
        </div>
      </div>
</div>
