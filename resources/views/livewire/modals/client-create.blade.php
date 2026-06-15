<div wire:ignore.self class="modal fade" id="add_client" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl">
    <div class="modal-content">
      
      {{-- Modal Header --}}
      <div class="modal-header d-flex justify-content-between align-items-center">
        @if (!session()->has('success') && !session()->has('error'))
          <h1 class="modal-title fs-5" id="staticBackdropLabel">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen-fill me-2" viewBox="0 0 16 16">
              <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001"/>
            </svg>
            Client Information (客户信息)
          </h1>
        @else
          <div class="w-70">
            @if (session()->has('error'))
              <x-alert-message :color="'alert-danger'">{{ session('error') }}</x-alert-message>
            @endif
            @if (session()->has('success'))
              <x-alert-message :color="'alert-success'">{{ session('success') }}</x-alert-message>
            @endif
          </div>
        @endif
        
        <div class="d-flex align-items-center gap-2">
          <livewire:refresh-page/>  
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
      </div>

      {{-- Modal Body --}}
      <div class="modal-body">
        <form wire:submit.prevent='validateAndConfirm'>
          
          {{-- SECTION 1: CLIENT BASIC DETAILS --}}
          <div class="d-flex align-items-center mb-3">
            <h5 class="text-primary fw-bold mb-0">Basic Information (基本信息)</h5>
          </div>
          
          <section class="row g-3 mb-4">
            {{-- Company Name --}}
            <div class="col-lg-3">
              <div class="form-floating">
                <input wire:model='CompanyName' type="text" class="form-control" id="CompanyName" placeholder="Company Name">
                <label for="CompanyName">Company Name (公司名称)</label>
                @error('CompanyName') <span class="text-danger small">{{ $message }}</span> @enderror
              </div>
            </div>

            {{-- Address --}}
            <div class="col-lg-3">
              <div class="form-floating">
                <input wire:model='address' type="text" class="form-control" id="address" placeholder="Address">
                <label for="address">Address (地址)</label>
                @error('address') <span class="text-danger small">{{ $message }}</span> @enderror
              </div>
            </div>

            {{-- Email --}}
            <div class="col-lg-3">
              <div class="form-floating">
                <input wire:model='email' type="email" class="form-control" id="email" placeholder="Email Address">
                <label for="email">Email Address (邮箱)</label>
                @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
              </div>
            </div>

            {{-- Contact Number --}}
            <div class="col-lg-3">
              <div class="form-floating">
                <input wire:model='contact_number' type="tel" class="form-control" id="contact_number" placeholder="Contact Number">
                <label for="contact_number">Contact Number (联系电话)</label>
                @error('contact_number') <span class="text-danger small">{{ $message }}</span> @enderror
              </div>
            </div>

            {{-- Contact Person --}}
            <div class="col-lg-3">
              <div class="form-floating">
                <input wire:model='contact_person' type="text" class="form-control" id="contact_person" placeholder="Contact Person">
                <label for="contact_person">Contact Person (联系人)</label>
                @error('contact_person') <span class="text-danger small">{{ $message }}</span> @enderror
              </div>
            </div>

            {{-- Contact Person Number --}}
            <div class="col-lg-3">
              <div class="form-floating">
                <input wire:model='contact_person_number' type="tel" class="form-control" id="contact_person_number" placeholder="Contact Person Number">
                <label for="contact_person_number">Contact Number (联系电话)</label>
                @error('contact_person_number') <span class="text-danger small">{{ $message }}</span> @enderror
              </div>
            </div>

            {{-- Bank Account Number (Optional) --}}
            <div class="col-lg-6">
              <div class="form-floating">
                <input wire:model='bank_Account_number' type="text" class="form-control" id="bank_Account_number" placeholder="Bank Account Number">
                <label for="bank_Account_number">Bank Account Number (optional) 银行账号</label>
                @error('bank_Account_number') <span class="text-danger small">{{ $message }}</span> @enderror
              </div>
            </div>
          </section>

          <hr class="my-4">

          {{-- SECTION 2: DYNAMIC VEHICLE SPECIFICATIONS --}}
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="text-success fw-bold mb-0">Vehicle Specifications (车辆规格)</h5>
            <button type="button" wire:click="addVehicle" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-bold">
              + Add More Vehicle
            </button>
          </div>
          
          @foreach ($vehicles as $index => $vehicle)
            <div class="card mb-3 border shadow-sm" wire:key="vehicle-{{ $index }}">
              <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                <span class="fw-bold text-secondary">Vehicle #{{ $index + 1 }}</span>
                @if(count($vehicles) > 1)
                  <button type="button" wire:click="removeVehicle({{ $index }})" class="btn btn-danger btn-sm py-0 px-2">
                    &times; Remove Vehicle
                  </button>
                @endif
              </div>
              <div class="card-body row g-2">
                
                {{-- Brand --}}
                <div class="col-md-4">
                  <div class="form-floating">
                    <input wire:model="vehicles.{{ $index }}.brand" type="text" class="form-control" placeholder="Brand">
                    <label>Brand</label>
                    @error("vehicles.$index.brand") <span class="text-danger small">{{ $message }}</span> @enderror
                  </div>
                </div>

                {{-- Model --}}
                <div class="col-md-4">
                  <div class="form-floating">
                    <input wire:model="vehicles.{{ $index }}.model" type="text" class="form-control" placeholder="Model">
                    <label>Model</label>
                    @error("vehicles.$index.model") <span class="text-danger small">{{ $message }}</span> @enderror
                  </div>
                </div>

                {{-- Loading Capacity --}}
                <div class="col-md-4">
                  <div class="form-floating">
                    <input wire:model="vehicles.{{ $index }}.loading_capacity" type="text" class="form-control" placeholder="Loading Capacity">
                    <label>Loading Capacity</label>
                    @error("vehicles.$index.loading_capacity") <span class="text-danger small">{{ $message }}</span> @enderror
                  </div>
                </div>

                {{-- Lifting Height --}}
                <div class="col-md-3">
                  <div class="form-floating">
                    <input wire:model="vehicles.{{ $index }}.lifting_height" type="text" class="form-control" placeholder="Lifting Height">
                    <label>Lifting Height</label>
                    @error("vehicles.$index.lifting_height") <span class="text-danger small">{{ $message }}</span> @enderror
                  </div>
                </div>

                {{-- Mast Type --}}
                <div class="col-md-3">
                  <div class="form-floating">
                    <input wire:model="vehicles.{{ $index }}.mast_type" type="text" class="form-control" placeholder="Mast Type">
                    <label>Mast Type</label>
                    @error("vehicles.$index.mast_type") <span class="text-danger small">{{ $message }}</span> @enderror
                  </div>
                </div>

                {{-- Power Type --}}
                <div class="col-md-3">
                  <div class="form-floating">
                    <input wire:model="vehicles.{{ $index }}.power_type" type="text" class="form-control" placeholder="Power Type">
                    <label>Power Type</label>
                    @error("vehicles.$index.power_type") <span class="text-danger small">{{ $message }}</span> @enderror
                  </div>
                </div>

                {{-- Tire --}}
                <div class="col-md-3">
                  <div class="form-floating">
                    <input wire:model="vehicles.{{ $index }}.tire" type="text" class="form-control" placeholder="Tire">
                    <label>Tire</label>
                    @error("vehicles.$index.tire") <span class="text-danger small">{{ $message }}</span> @enderror
                  </div>
                </div>

                {{-- Fork Length --}}
                <div class="col-md-6">
                  <div class="form-floating">
                    <input wire:model="vehicles.{{ $index }}.fork_length" type="text" class="form-control" placeholder="Fork Length">
                    <label>Fork Length</label>
                    @error("vehicles.$index.fork_length") <span class="text-danger small">{{ $message }}</span> @enderror
                  </div>
                </div>

                {{-- Attachment --}}
                <div class="col-md-6">
                  <div class="form-floating">
                    <input wire:model="vehicles.{{ $index }}.attachment" type="text" class="form-control" placeholder="Attachment">
                    <label>Attachment</label>
                    @error("vehicles.$index.attachment") <span class="text-danger small">{{ $message }}</span> @enderror
                  </div>
                </div>

              </div>
            </div>
          @endforeach

          {{-- Modal Footer --}}
          <div class="modal-footer d-flex justify-content-start mt-4 shadow-sm bg-light p-3 rounded">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button style="background-color: #004998" type="submit" class="btn text-light">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus me-1" viewBox="0 0 16 16">
                <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5"/>
              </svg>
              Add Client (添加客户)
            </button>
          </div>

        </form>
      </div>

    </div>
  </div>

  {{-- Dynamic Confirmation Overlay Modal --}}
  @if ($showConfirmation)
    <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background: rgba(0,0,0,0.5); z-index: 1060;">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title fw-bold">Please Confirm (请确认)</h5>
            <button type="button" class="btn-close" wire:click="$set('showConfirmation', false)"></button>
          </div>
          <div class="modal-body shadow-sm">
            <p class="mb-1">Are you sure you want to create this client?</p>
            <small class="text-muted d-block">• Vehicles configured: <strong>{{ count($vehicles) }} item(s)</strong></small>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" wire:click="$set('showConfirmation', false)">Cancel</button>
            <button style="background-color: #004998" type="button" class="btn text-light" wire:click="createClient">Yes, Create</button>
          </div>
        </div>
      </div>
    </div>
  @endif

</div>