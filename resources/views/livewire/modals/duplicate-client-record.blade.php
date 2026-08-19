<div wire:ignore.self class="modal fade" id="duplicateClientRecord_{{ $clientId }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="duplicateClientRecordLabel_{{ $clientId }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header d-flex justify-content-between align-items-center">
        <h1 class="modal-title fs-5" id="duplicateClientRecordLabel_{{ $clientId }}">
          <i class="fas fa-user-plus me-2"></i>
          Create New Unit Record
        </h1>

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form wire:submit.prevent="validateAndConfirm">
          <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
            <h5 class="text-primary fw-bold mb-0">Basic Information</h5>

            <div class="btn-group btn-group-sm" role="group" aria-label="Client Type">
              <input type="radio" class="btn-check" name="duplicate_client_type_{{ $clientId }}" id="duplicate_type_corporate_{{ $clientId }}" value="corporate" wire:model.live="client_type">
              <label class="btn btn-outline-primary" for="duplicate_type_corporate_{{ $clientId }}">
                <i class="fas fa-building me-1"></i> Corporate
              </label>

              <input type="radio" class="btn-check" name="duplicate_client_type_{{ $clientId }}" id="duplicate_type_personal_{{ $clientId }}" value="personal" wire:model.live="client_type">
              <label class="btn btn-outline-primary" for="duplicate_type_personal_{{ $clientId }}">
                <i class="fas fa-user me-1"></i> Personal / Individual
              </label>
            </div>
          </div>

          <section class="row g-3">
            @if ($client_type === 'personal')
            <div class="col-lg-4">
              <div class="form-floating">
                <input wire:model="first_name" type="text" class="form-control" id="duplicate_first_name_{{ $clientId }}" placeholder="First Name">
                <label for="duplicate_first_name_{{ $clientId }}">First Name *</label>
                @error('first_name') <span class="text-danger small">{{ $message }}</span> @enderror
              </div>
            </div>

            <div class="col-lg-4">
              <div class="form-floating">
                <input wire:model="middle_name" type="text" class="form-control" id="duplicate_middle_name_{{ $clientId }}" placeholder="Middle Name">
                <label for="duplicate_middle_name_{{ $clientId }}">Middle Name (Optional)</label>
                @error('middle_name') <span class="text-danger small">{{ $message }}</span> @enderror
              </div>
            </div>

            <div class="col-lg-4">
              <div class="form-floating">
                <input wire:model="last_name" type="text" class="form-control" id="duplicate_last_name_{{ $clientId }}" placeholder="Last Name">
                <label for="duplicate_last_name_{{ $clientId }}">Last Name *</label>
                @error('last_name') <span class="text-danger small">{{ $message }}</span> @enderror
              </div>
            </div>
            @else
            <div class="col-lg-6">
              <div class="form-floating">
                <input wire:model="CompanyName" type="text" class="form-control" id="duplicate_CompanyName_{{ $clientId }}" placeholder="Company Name">
                <label for="duplicate_CompanyName_{{ $clientId }}">Company Name *</label>
                @error('CompanyName') <span class="text-danger small">{{ $message }}</span> @enderror
              </div>
            </div>
            @endif

            <div class="col-lg-6">
              <div class="form-floating">
                <select wire:model="suffix" class="form-select" id="duplicate_suffix_{{ $clientId }}" aria-label="Company suffix">
                  <option value="">No suffix</option>
                  @foreach ($suffixes as $suffixOption)
                  <option value="{{ $suffixOption->suffix }}">{{ $suffixOption->suffix }}</option>
                  @endforeach
                </select>
                <label for="duplicate_suffix_{{ $clientId }}">Company Suffix (Optional)</label>
                @error('suffix') <span class="text-danger small">{{ $message }}</span> @enderror
              </div>
            </div>

            <div class="{{ $client_type === 'personal' ? 'col-lg-12' : 'col-lg-6' }}">
              <div class="form-floating">
                <input wire:model="address" type="text" class="form-control" id="duplicate_address_{{ $clientId }}" placeholder="Address">
                <label for="duplicate_address_{{ $clientId }}">Address</label>
                @error('address') <span class="text-danger small">{{ $message }}</span> @enderror
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-floating">
                <input wire:model="email" type="email" class="form-control" id="duplicate_email_{{ $clientId }}" placeholder="Email Address">
                <label for="duplicate_email_{{ $clientId }}">Email Address</label>
                @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-floating">
                <input wire:model="contact_number" type="tel" class="form-control" id="duplicate_contact_number_{{ $clientId }}" placeholder="Contact Number">
                <label for="duplicate_contact_number_{{ $clientId }}">Contact Number</label>
                @error('contact_number') <span class="text-danger small">{{ $message }}</span> @enderror
              </div>
            </div>

            @if ($client_type !== 'personal')
            <div class="col-lg-6">
              <div class="form-floating">
                <input wire:model="contact_person" type="text" class="form-control" id="duplicate_contact_person_{{ $clientId }}" placeholder="Contact Person">
                <label for="duplicate_contact_person_{{ $clientId }}">Contact Person</label>
                @error('contact_person') <span class="text-danger small">{{ $message }}</span> @enderror
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-floating">
                <input wire:model="contact_person_number" type="tel" class="form-control" id="duplicate_contact_person_number_{{ $clientId }}" placeholder="Contact Person Number">
                <label for="duplicate_contact_person_number_{{ $clientId }}">Contact Person Number</label>
                @error('contact_person_number') <span class="text-danger small">{{ $message }}</span> @enderror
              </div>
            </div>
            @endif

            <div class="col-12">
              <div class="form-floating">
                <input wire:model="bank_Account_number" type="text" class="form-control" id="duplicate_bank_Account_number_{{ $clientId }}" placeholder="Bank Account Number">
                <label for="duplicate_bank_Account_number_{{ $clientId }}">Bank Account Number (optional)</label>
                @error('bank_Account_number') <span class="text-danger small">{{ $message }}</span> @enderror
              </div>
            </div>
          </section>

          <div class="alert alert-info small mt-4 mb-0" role="alert">
            Vehicle specifications, supporting documents, status history, and sales list number will start empty for this new record.
          </div>

          <div class="modal-footer d-flex justify-content-start mt-4 bg-light p-3 rounded">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button style="background-color: #004998" type="submit" class="btn text-light">
              <i class="fas fa-copy me-1"></i>
              Save New Record
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @if ($showConfirmation)
  <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background: rgba(0,0,0,0.5); z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold">Please Confirm</h5>
          <button type="button" class="btn-close" wire:click="$set('showConfirmation', false)"></button>
        </div>
        <div class="modal-body shadow-sm">
          <p class="mb-1">Create a new unit record using this client information?</p>
          <small class="text-muted d-block">Vehicle specifications and documents will not be copied.</small>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" wire:click="$set('showConfirmation', false)">Cancel</button>
          <button style="background-color: #004998" type="button" class="btn text-light" wire:click="createNewUnitRecord">Yes, Create</button>
        </div>
      </div>
    </div>
  </div>
  @endif
</div>

@script
<script>
  $wire.on('hide-duplicate-client-record-modal-{{ $clientId }}', () => {
    const modalElement = document.getElementById('duplicateClientRecord_{{ $clientId }}');

    if (!modalElement) {
      $wire.dispatch('clients-updated');
      return;
    }

    const modal = window.bootstrap.Modal.getOrCreateInstance(modalElement);
    modalElement.addEventListener('hidden.bs.modal', () => {
      window.bootstrap.Modal.getInstance(modalElement)?.dispose();
      document.querySelectorAll('.modal-backdrop').forEach((backdrop) => backdrop.remove());
      document.body.classList.remove('modal-open');
      document.body.style.removeProperty('overflow');
      document.body.style.removeProperty('padding-right');
      $wire.dispatch('clients-updated');
    }, { once: true });
    modal.hide();
  });
</script>
@endscript
