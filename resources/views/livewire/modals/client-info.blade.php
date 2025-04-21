<div>
  <!-- Single shared modal -->
  <div wire:ignore.self wire:key="client-modal_{{$clientId}}" class="modal fade" id="clientModal_{{$clientId}}" tabindex="-1" aria-labelledby="clientModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
              <form wire:submit.prevent="soldForm">
                  <div class="modal-header">
                      <h5 class="modal-title">Product Details</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>

                  <div class="modal-body">
                      @if (session()->has('success'))
                          <div class="alert alert-success">
                              {{ session('success') }}
                          </div>
                      @endif

                      <div class="form-floating mb-3">
                          <input wire:model='itemName' type="text" class="form-control" id="ItemName" placeholder="Item Name">
                          <label for="ItemName">Item Name</label>
                          @error('itemName') <span class="text-danger">{{ $message }}</span> @enderror
                      </div>

                      <div class="form-floating mb-3">
                          <input wire:model='modelNumber' type="text" class="form-control" id="modelNumber" placeholder="Model Number">
                          <label for="modelNumber">Model Number</label>
                          @error('modelNumber') <span class="text-danger">{{ $message }}</span> @enderror
                      </div>
                      
                      <div class="mb-3">
                        <div class="form-floating">
                            <textarea wire:model='Specification'  class="form-control" placeholder="Specification" id="Specification" style="height: 100px"></textarea>
                            <label for="Specification">Specification</label>
                          </div>
                          @error('Specification') <span class="text-danger">{{ $message }}</span> @enderror
                      </div>
  

                      <div class="form-floating mb-3">
                          <input wire:model='Quantity' type="number" class="form-control" id="Quantity" placeholder="Quantity">
                          <label for="Quantity">Quantity</label>
                          @error('Quantity') <span class="text-danger">{{ $message }}</span> @enderror
                      </div>
                  </div>

                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Sold</button>
                  </div>
              </form>
          </div>
      </div>
  </div>
</div>
