<div>
 <!-- Modal -->
 <div wire:key="client-modal-{{ $clientId }}" wire:ignore.self class="modal fade" id="clientModal_{{$clientId}}">

    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">
             Product Details
          </h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form  wire:submit="soldForm">
        <div class="modal-body">
             @if (session()->has('success'))
             <div class="alert alert-success">
                 {{session('success')}}
             </div>
             @endif
            <div class="form-floating mb-3">
                <input wire:model='itemName' type="text" class="form-control" id="ItemName" placeholder="Item Name">
                <label for="ItemSold">Item Name</label>
                @error('itemName')
                   <span class="text-danger"> {{$message}}</span>
                @enderror
              </div>
              <div class="form-floating mb-3">
                <input wire:model='modelNumber' type="text" class="form-control" id="modelNumber" placeholder="Model Number">
                <label for="modelNumber">Model Number</label>
                @error('modelNumber')
                <span class="text-danger"> {{$message}}</span>
               @enderror
              </div>
              <div class="form-floating mb-3">
                <input wire:model='Specification' type="text" class="form-control" id="Specification" placeholder="Specification">
                <label for="Specification">Specification</label>
                @error('Specification')
                <span class="text-danger"> {{$message}}</span>
               @enderror
            </div>
              <div class="form-floating mb-3">
                <input wire:model='Quantity' type="text" class="form-control" id="Quantity" placeholder="Quantity">
                <label for="Quantity">Quantity</label>
                @error('Quantity')
                <span class="text-danger"> {{$message}}</span>
               @enderror
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
