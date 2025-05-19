<div>
  <div wire:ignore.self class="modal fade" id="ModalChangeStatus_{{ $clientId }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
              <div class="modal-header">
                  <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Status</h1>
                  <livewire:refresh-page/>
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

              <form wire:submit.prevent="change_status">
                  <div class="modal-body">
                      <select wire:model.live="SelectedStatus" class="form-select form-select-lg mb-3">
                          <option value="">Select Status (更改状态)</option>
                          <option value="For Approval">For Approval (供批准)</option>
                          <option value="Pending">Pending (未成交)</option>
                      </select>
                      @error('SelectedStatus') 
                          <span class="text-danger">{{ $message }}</span> 
                      @enderror

                      <div class="form-floating mb-3">
                        <input wire:model.live='salesList_no' type="text" class="form-control" id="SalesList_no" placeholder="Sales List No">
                        <label for="SalesList_no">SalesList No:</label>
                        </div>
                    </div>
                  <div class="modal-footer">
                      <button type="submit" class="btn text-light" style="background-color: #004998">
                          <span wire:loading wire:target="change_status" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                              <path d="M11 2H9v3h2z"/>
                              <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                          </svg>
                          Submit (提交)
                      </button>
                  </div>
              </form>
          </div>
      </div>
  </div>
</div>


