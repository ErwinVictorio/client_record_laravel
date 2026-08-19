<div wire:ignore.self class="modal fade" id="EditAtutoRepairMaintence_{{$recordId}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl">
      <div class="modal-content border-0 shadow-lg">
        
        <div class="modal-header text-white" style="background-color: #004998">
          @if (!session()->has('success') && !session()->has('error'))
            <h1 class="modal-title fs-5" id="staticBackdropLabel">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen-fill" viewBox="0 0 16 16">
                <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001"/>
              </svg>
              Client Information (客户信息)
            </h1>
          @else
            @if (session()->has('error'))
              <x-alert-message :color="'alert-danger'">{{ session('error') }}</x-alert-message>
            @endif
            @if (session()->has('success'))
              <x-alert-message :color="'alert-success'">{{ session('success') }}</x-alert-message>
            @endif
          @endif
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
  
        <div class="modal-body bg-light p-4">
          <form wire:submit.prevent='updateRecord'>
            <section class="row g-3 bg-white border rounded-3 p-3 p-md-4 shadow-sm">
              <h6 class="fw-bold mb-0">Client & Service Information</h6>
  
              {{-- Company Name --}}
              <div class="col-lg-3">
                <div class="form-floating">
                  <input wire:model.live='company_name' type="text" class="form-control" id="company_name" placeholder="Company Name">
                  <label for="company_name">Company Name (公司名称)</label>
                  @error('company_name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
              </div>
  
              {{-- Address --}}
              <div class="col-lg-3">
                <div class="form-floating">
                  <input wire:model.live='address' type="text" class="form-control" id="address" placeholder="Address">
                  <label for="address">Address (地址)</label>
                  @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
              </div>
  
              {{-- Email --}}
              <div class="col-lg-3">
                <div class="form-floating">
                  <input wire:model.live='email' type="email" class="form-control" id="email" placeholder="Email Address">
                  <label for="email">Email Address (邮箱)</label>
                  @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
              </div>
  
              {{-- Contact Number --}}
              <div class="col-lg-3">
                <div class="form-floating">
                  <input wire:model.live='contact_number' type="text" class="form-control" id="contact_number" placeholder="Contact Number">
                  <label for="contact_number">Contact Number (联系电话)</label>
                  @error('contact_number') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
              </div>

              @if ($managesJobOrderNumber)
                <div class="col-lg-3">
                  <div class="form-floating">
                    <input wire:model.live='job_order_number' type="text" class="form-control" id="JobOrderNumber_{{$recordId}}" placeholder="Job Order Number.">
                    <label for="JobOrderNumber_{{$recordId}}">Job Order Number.(工单编号)</label>
                    @error('job_order_number') <span class="text-danger">{{ $message }}</span> @enderror
                  </div>
                </div>
              @endif
  
              <div class="col-lg-3">
                <div class="form-floating">
                  <input wire:model.live='serial_number' type="text" class="form-control" id="serial_number_{{$recordId}}" placeholder="Serial Number">
                  <label for="serial_number_{{$recordId}}">Serial Number</label>
                  @error('serial_number') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
              </div>

              <div class="col-lg-3">
                <div class="form-floating">
                  <input wire:model.live='date_sold' type="date" class="form-control" id="date_sold_{{$recordId}}" placeholder="Date Sold">
                  <label for="date_sold_{{$recordId}}">Date Sold</label>
                  @error('date_sold') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
              </div>

              {{-- Contact Person --}}
              <div class="col-lg-3">
                <div class="form-floating">
                  <input wire:model.live='contact_person' type="text" class="form-control" id="contact_person" placeholder="Contact Person">
                  <label for="contact_person">Contact Person (联系人)</label>
                  @error('contact_person') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
              </div>
  
              {{-- Contact Person Number --}}
              <div class="col-lg-3">
                <div class="form-floating">
                  <input wire:model.live='contact_number_person' type="text" class="form-control" id="contact_person_number" placeholder="Contact Person Number">
                  <label for="contact_number_person">Contact Number  (联系电话)</label>
                  @error('contact_number_person') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
              </div>
  
              {{-- Bank Account Number (Optional) --}}
              <div class="col-lg-7">
                <div class="form-floating">
                  <input wire:model.live='bank_account_number' type="text" class="form-control" id="bank_Account_number" placeholder="Bank Account Number">
                  <label for="bank_account_number">Bank Account Number (optional) 银行账号</label>
                </div>
              </div>
  
            </section>

            <section class="bg-white border rounded-3 p-3 p-md-4 shadow-sm mt-3">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-success mb-0">Vehicle Specifications</h6>
                <button type="button" wire:click="addVehicle" class="btn btn-outline-success btn-sm rounded-pill px-3">
                  + Add More Vehicle
                </button>
              </div>

              @forelse ($vehicles as $index => $vehicle)
                <div class="border rounded p-3 mb-3" wire:key="maintenance-edit-vehicle-{{ $recordId }}-{{ $index }}">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-bold text-secondary">Vehicle #{{ $index + 1 }}</span>
                    <button type="button" wire:click="removeVehicle({{ $index }})" class="btn btn-sm btn-outline-danger py-0">
                      Remove
                    </button>
                  </div>

                  <div class="row g-2">
                    <div class="col-md-4">
                      <select wire:model.live="vehicles.{{ $index }}.power_type_selection" class="form-select form-select-sm">
                        <option value="">Select Power Type</option>
                        @foreach ($powerTypeOptions as $option)
                          <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                        <option value="Other">Other</option>
                      </select>
                      @error("vehicles.$index.power_type_selection") <span class="text-danger small">{{ $message }}</span> @enderror
                      @if (($vehicle['power_type_selection'] ?? '') === 'Other')
                        <input wire:model="vehicles.{{ $index }}.power_type_other" type="text" class="form-control form-control-sm mt-1" placeholder="Enter Power Type">
                        @error("vehicles.$index.power_type_other") <span class="text-danger small">{{ $message }}</span> @enderror
                      @endif
                    </div>
                    <div class="col-md-4">
                      <input wire:model="vehicles.{{ $index }}.model" type="text" class="form-control form-control-sm" placeholder="Model">
                      @error("vehicles.$index.model") <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                      <input wire:model="vehicles.{{ $index }}.serial_or_plate_number" type="text" class="form-control form-control-sm" placeholder="Serial Number / Plate Number">
                      @error("vehicles.$index.serial_or_plate_number") <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                      <input wire:model="vehicles.{{ $index }}.engine" type="text" class="form-control form-control-sm" placeholder="Engine">
                    </div>
                    <div class="col-md-4">
                      <input wire:model="vehicles.{{ $index }}.engine_series" type="text" class="form-control form-control-sm" placeholder="Engine Series">
                    </div>
                    <div class="col-md-4">
                      <input wire:model="vehicles.{{ $index }}.loading_capacity" type="text" class="form-control form-control-sm" placeholder="Loading Capacity">
                    </div>
                    <div class="col-md-3">
                      <input wire:model="vehicles.{{ $index }}.lifting_height" type="text" class="form-control form-control-sm" placeholder="Lifting Height">
                    </div>
                    <div class="col-md-3">
                      <select wire:model.live="vehicles.{{ $index }}.mast_type_selection" class="form-select form-select-sm">
                        <option value="">Select Mast Type</option>
                        @foreach ($mastTypeOptions as $option)
                          <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                        <option value="Other">Other</option>
                      </select>
                      @error("vehicles.$index.mast_type_selection") <span class="text-danger small">{{ $message }}</span> @enderror
                      @if (($vehicle['mast_type_selection'] ?? '') === 'Other')
                        <input wire:model="vehicles.{{ $index }}.mast_type_other" type="text" class="form-control form-control-sm mt-1" placeholder="Enter Mast Type">
                        @error("vehicles.$index.mast_type_other") <span class="text-danger small">{{ $message }}</span> @enderror
                      @endif
                    </div>
                    <div class="col-md-3">
                      <input wire:model="vehicles.{{ $index }}.brand" type="text" class="form-control form-control-sm" placeholder="Brand">
                      @error("vehicles.$index.brand") <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-3">
                      <select wire:model.live="vehicles.{{ $index }}.tire_selection" class="form-select form-select-sm">
                        <option value="">Select Tire</option>
                        @foreach ($tireOptions as $option)
                          <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                        <option value="Other">Other</option>
                      </select>
                      @error("vehicles.$index.tire_selection") <span class="text-danger small">{{ $message }}</span> @enderror
                      @if (($vehicle['tire_selection'] ?? '') === 'Other')
                        <input wire:model="vehicles.{{ $index }}.tire_other" type="text" class="form-control form-control-sm mt-1" placeholder="Enter Tire Type">
                        @error("vehicles.$index.tire_other") <span class="text-danger small">{{ $message }}</span> @enderror
                      @endif
                    </div>
                    <div class="col-md-6">
                      <input wire:model="vehicles.{{ $index }}.fork_length" type="text" class="form-control form-control-sm" placeholder="Fork Length">
                    </div>
                    <div class="col-md-6">
                      <input wire:model="vehicles.{{ $index }}.attachment" type="text" class="form-control form-control-sm" placeholder="Attachment">
                    </div>
                  </div>
                </div>
              @empty
                <div class="text-muted small">No vehicle specifications encoded. Click “Add More Vehicle” to add one.</div>
              @endforelse
            </section>
  
            {{-- Modal Footer --}}
            <div class="modal-footer bg-white mx-n4 mb-n4 mt-4 px-4">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
              <button style="background-color: #004998" type="submit" class="btn text-light px-4" wire:loading.attr="disabled" wire:target="updateRecord">
                   <span wire:loading wire:target="updateRecord" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                          <path d="M11 2H9v3h2z"/>
                          <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                        </svg>
                  Save Changes
              </button>
            </div>
  
          </form>
        </div>
  
      </div>
    </div>
  </div>
  
