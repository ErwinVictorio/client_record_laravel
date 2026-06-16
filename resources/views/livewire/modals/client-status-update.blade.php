<div>
  <div wire:ignore.self class="modal fade" id="ModalChangeStatus_{{ $clientId }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ModalChangeStatusLabel_{{ $clientId }}" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h1 class="modal-title fs-6 fw-bold" id="ModalChangeStatusLabel_{{ $clientId }}">
                      Update Status
                  </h1>
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
                      <select wire:model.live="SelectedStatus" class="form-select mb-3">
                          <option value="">Select Status (更改状态)</option>
                          <option value="For Approval">For Approval</option>
                          <option value="Pending">Pending</option>
                          <option value="Sold">Sold</option>
                      </select>
                      @error('SelectedStatus')
                          <span class="text-danger d-block mb-2 small">{{ $message }}</span>
                      @enderror

                      <div class="form-floating mb-3">
                          <input wire:model.live="salesList_no" type="text" class="form-control" id="SalesList_no_{{ $clientId }}" placeholder="SalesList No">
                          <label for="SalesList_no_{{ $clientId }}">SalesList No:</label>
                          @error('salesList_no')
                              <span class="text-danger small">{{ $message }}</span>
                          @enderror
                      </div>

                      <div class="form-floating mb-3">
                          <input wire:model.live="bank_account_number" type="text" class="form-control" id="bank_account_number_{{ $clientId }}" placeholder="Bank Account Number">
                          <label for="bank_account_number_{{ $clientId }}">Bank Account Number</label>
                          @error('bank_account_number')
                              <span class="text-danger small">{{ $message }}</span>
                          @enderror
                      </div>

                      <div class="mb-2">
                          <div class="d-flex justify-content-between align-items-center mb-1">
                              <label class="form-label fw-bold text-secondary mb-0">Supporting Documents (PDF Lamang, Max 5MB each)</label>
                              <button type="button" wire:click="addSupportingDocument" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                  + Add More Docs
                              </button>
                          </div>

                          @if (count($existing_supporting_document_paths) > 0)
                              <div class="small text-muted mb-2">
                                  Current files:
                                  @foreach ($existing_supporting_document_paths as $path)
                                      <span class="badge bg-light text-dark border">{{ basename($path) }}</span>
                                  @endforeach
                              </div>
                          @endif

                          @foreach ($supporting_docs as $index => $supportingDoc)
                              <div class="input-group mb-2" wire:key="supporting-doc-{{ $clientId }}-{{ $index }}">
                                  <input wire:model="supporting_docs.{{ $index }}" type="file" accept="application/pdf" class="form-control">
                                  @if (count($supporting_docs) > 1)
                                      <button type="button" wire:click="removeSupportingDocument({{ $index }})" class="btn btn-outline-danger">
                                          Remove
                                      </button>
                                  @endif
                              </div>
                              @error("supporting_docs.$index")
                                  <span class="text-danger small d-block mt-1">{{ $message }}</span>
                              @enderror
                          @endforeach

                          @error('supporting_docs')
                              <span class="text-danger small d-block mt-1">{{ $message }}</span>
                          @enderror
                          <div wire:loading wire:target="supporting_docs" class="text-primary small mt-1">
                              <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                              Uploading file...
                          </div>
                      </div>

                      <div class="d-flex justify-content-between align-items-center mt-3 mb-2">
                          <label class="form-label fw-bold text-success mb-0">Vehicle Specifications (车辆规格)</label>
                          <button type="button" wire:click="addVehicle" class="btn btn-outline-success btn-sm rounded-pill px-3">
                              + Add More Vehicle
                          </button>
                      </div>

                      @foreach ($vehicles as $index => $vehicle)
                          <div class="border rounded p-2 mb-2" wire:key="status-vehicle-{{ $clientId }}-{{ $index }}">
                              <div class="d-flex justify-content-between align-items-center mb-2">
                                  <span class="small fw-bold text-secondary">Vehicle #{{ $index + 1 }}</span>
                                  @if (count($vehicles) > 1)
                                      <button type="button" wire:click="removeVehicle({{ $index }})" class="btn btn-sm btn-outline-danger py-0">
                                          Remove
                                      </button>
                                  @endif
                              </div>

                              <div class="row g-2">
                                  <div class="col-md-4">
                                      <input wire:model="vehicles.{{ $index }}.brand" type="text" class="form-control form-control-sm" placeholder="Brand">
                                      @error("vehicles.$index.brand") <span class="text-danger small">{{ $message }}</span> @enderror
                                  </div>
                                  <div class="col-md-4">
                                      <input wire:model="vehicles.{{ $index }}.model" type="text" class="form-control form-control-sm" placeholder="Model">
                                      @error("vehicles.$index.model") <span class="text-danger small">{{ $message }}</span> @enderror
                                  </div>
                                  <div class="col-md-4">
                                      <input wire:model="vehicles.{{ $index }}.loading_capacity" type="text" class="form-control form-control-sm" placeholder="Loading Capacity">
                                  </div>
                                  <div class="col-md-3">
                                      <input wire:model="vehicles.{{ $index }}.lifting_height" type="text" class="form-control form-control-sm" placeholder="Lifting Height">
                                  </div>
                                  <div class="col-md-3">
                                      <input wire:model="vehicles.{{ $index }}.mast_type" type="text" class="form-control form-control-sm" placeholder="Mast Type">
                                  </div>
                                  <div class="col-md-3">
                                      <input wire:model="vehicles.{{ $index }}.power_type" type="text" class="form-control form-control-sm" placeholder="Power Type">
                                  </div>
                                  <div class="col-md-3">
                                      <input wire:model="vehicles.{{ $index }}.tire" type="text" class="form-control form-control-sm" placeholder="Tire">
                                  </div>
                                  <div class="col-md-6">
                                      <input wire:model="vehicles.{{ $index }}.fork_length" type="text" class="form-control form-control-sm" placeholder="Fork Length">
                                  </div>
                                  <div class="col-md-6">
                                      <input wire:model="vehicles.{{ $index }}.attachment" type="text" class="form-control form-control-sm" placeholder="Attachment">
                                  </div>
                              </div>
                          </div>
                      @endforeach
                  </div>

                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn text-light" style="background-color: #004998" wire:loading.attr="disabled" wire:target="supporting_docs,change_status">
                          <span wire:loading wire:target="change_status" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                          Submit (提交)
                      </button>
                  </div>
              </form>
          </div>
      </div>
  </div>
</div>
