<div wire:ignore.self class="modal fade" id="createAtutoRepairMaintenance" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
  
        <div class="modal-body">
          <form wire:submit.prevent='create_client_for_maintenance'>
            <section class="row g-3">
  
              {{-- Company Name --}}
              <div class="col-lg-3">
                <div class="form-floating">
                  <input wire:model='company_name' type="text" class="form-control" id="company_name" placeholder="Company Name">
                  <label for="company_name">Company Name (公司名称)</label>
                  @error('company_name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
              </div>
  
              {{-- Address --}}
              <div class="col-lg-3">
                <div class="form-floating">
                  <input wire:model='address' type="text" class="form-control" id="address" placeholder="Address">
                  <label for="address">Address (地址)</label>
                  @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
              </div>
  
              {{-- Email --}}
              <div class="col-lg-3">
                <div class="form-floating">
                  <input wire:model='email' type="email" class="form-control" id="email" placeholder="Email Address">
                  <label for="email">Email Address (邮箱)</label>
                  @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
              </div>
  
              {{-- Contact Number --}}
              <div class="col-lg-3">
                <div class="form-floating">
                  <input wire:model='contact_number' type="text" class="form-control" id="contact_number" placeholder="Contact Number">
                  <label for="contact_number">Contact Number (联系电话)</label>
                  @error('contact_number') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
              </div>


  
              {{-- Contact Person --}}
              <div class="col-lg-3">
                <div class="form-floating">
                  <input wire:model='contact_person' type="text" class="form-control" id="contact_person" placeholder="Contact Person">
                  <label for="contact_person">Contact Person (联系人)</label>
                  @error('contact_person') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
              </div>
  
              {{-- Contact Person Number --}}
              <div class="col-lg-3">
                <div class="form-floating">
                  <input wire:model='contact_number_person' type="text" class="form-control" id="contact_person_number" placeholder="Contact Person Number">
                  <label for="contact_number_person">Contact Number  (联系电话)</label>
                  @error('contact_number_person') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
              </div>
  
  
               <div class="col-lg-10">
                <div class="form-floating">
                  <input wire:model='job_order_number' type="text" class="form-control" id="JobOrderNumber" placeholder="Job Order Number.">
                  <label for="job_order_number">Job Order No.(工单编号)</label>
                  @error('job_order_number') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
              </div>
  
                            {{-- Bank Account Number (Optional) --}}
              <div class="col-lg-3">
                <div class="form-floating">
                  <input wire:model='bank_account_number' type="text" class="form-control" id="bank_Account_number" placeholder="Bank Account Number">
                  <label for="bank_account_number">Bank Account Number (optional) 银行账号</label>
                </div>
              </div>
            </section>
  
            {{-- Modal Footer --}}
            <div class="modal-footer d-flex justify-content-start mt-4">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button style="background-color: #004998" type="submit" class="btn text-light">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-plus" viewBox="0 0 16 16">
                  <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                  <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5"/>
                </svg>
                Add Client
                (添加客户)
              </button>
            </div>
  
          </form>
        </div>
  
      </div>
    </div>
  </div>
  
