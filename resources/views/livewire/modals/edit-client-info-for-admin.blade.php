<div>
  <div wire:ignore.self class="modal fade" id="EditClientIfo_{{$clientId}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
          <div class="modal-content">
                  <div class="modal-header">
                      <h1 class="modal-title fs-5" id="staticBackdropLabel">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                              <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                              <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                          </svg>
                          Edit Client Info
                      </h1>
                      <livewire:refresh-page/>
                      @if (session()->has('success'))
                           <x-alert-message :color=" 'alert-success' ">
                             {{session('success')}}
                           </x-alert-message>
                      @endif
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form wire:submit.prevent='updateClientInfo'> 
                  <div class="modal-body">
                    <section class="row gap-3">
                      <div class="form-floating mb-3 col-lg-5">
                        <input wire:model='company_name' type="text" class="form-control" id="company_name" placeholder="company name">
                        <label for="company_name">company name</label>
                         @error('company_name')
                           <span class="text-danger">{{$message}}</span>
                         @enderror
                    </div>

                    <div class="form-floating mb-3 col-lg-5">
                        <input wire:model='contact_number' type="text" class="form-control" id="contact_number" placeholder="contact number">
                        <label for="contact_number">contact number</label>
                        @error('contact_number')
                        <span class="text-danger">{{$message}}</span>
                      @enderror
                    </div>

                    <div class="form-floating mb-3 col-lg-5">
                        <input wire:model='email' type="email" class="form-control" id="email" placeholder="Email">
                        <label for="email">Email Address</label>
                        @error('email')
                        <span class="text-danger">{{$message}}</span>
                       @enderror
                    </div>

                    <div class="form-floating mb-3 col-lg-5">
                        <input wire:model='address' type="text" class="form-control" id="address" placeholder="Address">
                        <label for="address">Address</label>
                        @error('address')
                        <span class="text-danger">{{$message}}</span>
                      @enderror
                    </div>

                    <div class="form-floating mb-3 col-lg-5">
                        <input wire:model='contact_person' type="text" class="form-control" id="contact_person" placeholder="contact person">
                        <label for="contact_person">contact person</label>
                        @error('contact_person')
                        <span class="text-danger">{{$message}}</span>
                       @enderror
                    </div>

                    <div class="form-floating mb-3 col-lg-5">
                        <input wire:model='contact_number_person' type="text" class="form-control" id="contact_number_person" placeholder="contact number">
                        <label for="contact_number_person">contact number</label>
                        @error('contact_number_person')
                        <span class="text-danger">{{$message}}</span>
                       @enderror
                    </div>

                    <div class="form-floating mb-3 col-lg-5">
                        <input wire:model='bank_account_number' type="text" class="form-control" id="bank_account_number" placeholder="bank account number">
                        <label for="bank_account_number">bank account number</label>
                    </div>

                    <h5 class="text-muted">Product Info</h5>

                    <div class="form-floating mb-3 col-lg-5">
                        <input wire:model='model_number' type="text" class="form-control" id="model_number" placeholder="model number">
                        <label for="model_number">model number</label>
                    </div>

                    <div class="form-floating mb-3 col-lg-5">
                      <input wire:model='quantity' type="text" class="form-control" id="quantity" placeholder="quantity">
                      <label for="quantity">quantity</label>
                  </div>

                    <div class="form-floating mb-3 ">
                      <textarea wire:model='specification' class="form-control" placeholder="specification" id="specification" style="height: 100px"></textarea>
                      <label for="specification">specification</label>
                    </div>
                    </section>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button style="background-color: #004998" type="submit" class="btn text-light">
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
