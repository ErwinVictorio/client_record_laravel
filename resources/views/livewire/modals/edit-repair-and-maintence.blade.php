<div wire:ignore.self class="modal fade" id="EditAtutoRepairMaintence_{{$recordId}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl">
      <div class="modal-content">
        
        <div class="modal-header">
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
          <livewire:refresh-page/>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
  
        <div class="modal-body">
          <form wire:submit.prevent='updateRecord'>
            <section class="row g-3">
  
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

              <div class="col-lg-3">
                <div class="form-floating">
                  <input wire:model.live='job_order_number' type="text" class="form-control" id="JobOrderNumber" placeholder="Job Order Number.">
                  <label for="job_order_number">Job Order Number.(工单编号)</label>
                  @error('job_order_number') <span class="text-danger">{{ $message }}</span> @enderror
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
  
            {{-- Modal Footer --}}
            <div class="modal-footer d-flex justify-content-start mt-4">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button style="background-color: #004998" type="submit" class="btn text-light">
                   <span wire:loading wire:target="updateClientInfo" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
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
  