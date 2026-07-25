<div wire:ignore.self class="modal fade" id="add_client" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addClientLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header d-flex justify-content-between align-items-center">
        @if (!session()->has('success') && !session()->has('error'))
        <h1 class="modal-title fs-5" id="addClientLabel">
          <i class="fas fa-user-plus me-2"></i>
          Client Information
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

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form wire:submit.prevent="validateAndConfirm">

          <!-- Client Type Selection Header -->
          <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
            <h5 class="text-primary fw-bold mb-0">Basic Information</h5>

            <!-- Segmented Switcher / Radio Buttons -->
            <div class="btn-group btn-group-sm" role="group" aria-label="Client Type">
              <input type="radio" class="btn-check" name="client_type" id="type_corporate" value="corporate" wire:model.live="client_type" checked>
              <label class="btn btn-outline-primary" for="type_corporate">
                <i class="fas fa-building me-1"></i> Corporate
              </label>

              <input type="radio" class="btn-check" name="client_type" id="type_personal" value="personal" wire:model.live="client_type">
              <label class="btn btn-outline-primary" for="type_personal">
                <i class="fas fa-user me-1"></i> Personal / Individual
              </label>
            </div>
          </div>

          <section class="row g-3">

            @if ($client_type === 'personal')
            <!-- PERSONAL CLIENT FIELDS -->
            <div class="col-lg-4">
              <div class="form-floating">
                <input wire:model="first_name" type="text" class="form-control" id="first_name" placeholder="First Name">
                <label for="first_name">First Name *</label>
                @error('first_name') <span class="text-danger small">{{ $message }}</span> @enderror
              </div>
            </div>

            <div class="col-lg-4">
              <div class="form-floating">
                <input wire:model="middle_name" type="text" class="form-control" id="middle_name" placeholder="Middle Name">
                <label for="middle_name">Middle Name (Optional)</label>
                @error('middle_name') <span class="text-danger small">{{ $message }}</span> @enderror
              </div>
            </div>

            <div class="col-lg-4">
              <div class="form-floating">
                <input wire:model="last_name" type="text" class="form-control" id="last_name" placeholder="Last Name">
                <label for="last_name">Last Name *</label>
                @error('last_name') <span class="text-danger small">{{ $message }}</span> @enderror
              </div>
            </div>
            @else
            <!-- CORPORATE CLIENT FIELD -->

            <!-- Suffix -->
            <div class="col-lg-6">
              <div class="form-floating">
                <select wire:model="suffix" class="form-select" id="suffix" aria-label="Company suffix">
                  <option value="">No suffix</option>
                  @foreach ($suffixes as $suffixOption)
                    <option value="{{ $suffixOption->suffix }}">{{ $suffixOption->suffix }}</option>
                  @endforeach
                </select>
                <label for="suffix">Company Suffix (Optional)</label>
                @error('suffix') <span class="text-danger small">{{ $message }}</span> @enderror
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-floating">
                <input wire:model="CompanyName" type="text" class="form-control" id="CompanyName" placeholder="Company Name">
                <label for="CompanyName">Company Name *</label>
                @error('CompanyName') <span class="text-danger small">{{ $message }}</span> @enderror
              </div>
            </div>
            @endif

            <!-- Address Field -->
            <div class="{{ $client_type === 'personal' ? 'col-lg-12' : 'col-lg-6' }}">
              <div class="form-floating">
                <input wire:model="address" type="text" class="form-control" id="address" placeholder="Address">
                <label for="address">Address</label>
                @error('address') <span class="text-danger small">{{ $message }}</span> @enderror
              </div>
            </div>

            <!-- Email Address -->
            <div class="col-lg-6">
              <div class="form-floating">
                <input wire:model="email" type="email" class="form-control" id="email" placeholder="Email Address">
                <label for="email">Email Address</label>
                @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
              </div>
            </div>

            <!-- Contact Number -->
            <div class="col-lg-6">
              <div class="form-floating">
                <input wire:model="contact_number" type="tel" class="form-control" id="contact_number" placeholder="Contact Number">
                <label for="contact_number">Contact Number</label>
                @error('contact_number') <span class="text-danger small">{{ $message }}</span> @enderror
              </div>
            </div>

            @if ($client_type !== 'personal')
            <!-- Contact Person Fields (Only visible for Corporate) -->
            <div class="col-lg-6">
              <div class="form-floating">
                <input wire:model="contact_person" type="text" class="form-control" id="contact_person" placeholder="Contact Person">
                <label for="contact_person">Contact Person</label>
                @error('contact_person') <span class="text-danger small">{{ $message }}</span> @enderror
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-floating">
                <input wire:model="contact_person_number" type="tel" class="form-control" id="contact_person_number" placeholder="Contact Person Number">
                <label for="contact_person_number">Contact Person Number</label>
                @error('contact_person_number') <span class="text-danger small">{{ $message }}</span> @enderror
              </div>
            </div>
            @endif

            <!-- Bank Account Number -->
            <div class="col-12">
              <div class="form-floating">
                <input wire:model="bank_Account_number" type="text" class="form-control" id="bank_Account_number" placeholder="Bank Account Number">
                <label for="bank_Account_number">Bank Account Number (optional)</label>
                @error('bank_Account_number') <span class="text-danger small">{{ $message }}</span> @enderror
              </div>
            </div>
          </section>

          <div class="modal-footer d-flex justify-content-start mt-4 bg-light p-3 rounded">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button style="background-color: #004998" type="submit" class="btn text-light">
              <i class="fas fa-user-plus me-1"></i>
              Add Client
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @if ($showConfirmation)
  <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background: rgba(0,0,0,0.5); z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold">Please Confirm</h5>
          <button type="button" class="btn-close" wire:click="$set('showConfirmation', false)"></button>
        </div>
        <div class="modal-body shadow-sm">
          <p class="mb-1">Are you sure you want to create this client?</p>
          <small class="text-muted d-block">Vehicle specifications will be added during status update.</small>
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
