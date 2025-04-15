<div>
 <!-- Modal -->
 <div wire:ignore.self class="modal fade" id="clientModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">{{$name}}</h1>
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
                <input wire:model='itemSold' type="text" class="form-control" id="ItemSold" placeholder="Item Sold">
                <label for="ItemSold">Item Sold</label>
                @error('itemSold')
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
