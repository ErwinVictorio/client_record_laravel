<div>
  <div wire:ignore.self wire:key="client-modal_{{$clientId}}" class="modal fade" id="clientModal_{{$clientId}}" tabindex="-1" aria-labelledby="clientModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
              <form wire:submit.prevent="soldForm">
                  <div class="modal-header">
                      <h5 class="modal-title">Product Details (产品详情)</h5>
         
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>

                  <div class="modal-body">
                      @if (session()->has('success'))
                          <div class="alert alert-success">
                              {{ session('success') }}
                          </div>
                            <livewire:refresh-page/>
                      @endif

                      <div class="form-floating mb-3">
                          <input wire:model.live='itemName' type="text" class="form-control" id="ItemName" placeholder="Item Name">
                          <label for="ItemName">Item Name (物品名称)</label>
                          @error('itemName') <span class="text-danger">{{ $message }}</span> @enderror
                      </div>

                      <div class="form-floating mb-3">
                          <input wire:model.live='modelNumber' type="text" class="form-control" id="modelNumber" placeholder="Model Number">
                          <label for="modelNumber">Product Model (产品型号)</label>
                          @error('modelNumber') <span class="text-danger">{{ $message }}</span> @enderror
                      </div>
 
                      <div class="form-floating mb-3">
                          <input wire:model.live='Quantity' type="number" class="form-control" id="Quantity" placeholder="Quantity">
                          <label for="Quantity">Quantity (数量)</label>
                          @error('Quantity') <span class="text-danger">{{ $message }}</span> @enderror
                      </div>

                                           
                      <div class="mb-3">
                        <div class="form-floating">
                            <textarea wire:model.live='Specification'  class="form-control" placeholder="Specification" id="Specification" style="height: 100px"></textarea>
                            <label for="Specification">Specification (规格)</label>
                          </div>
                          @error('Specification') <span class="text-danger">{{ $message }}</span> @enderror
                      </div>
  
                  </div>

                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button style="background-color: #0d629b" type="submit" class="btn text-light">Sold(已售出)</button>
                  </div>
              </form>
          </div>
      </div>
  </div>
</div>
