<div>
  <div wire:ignore.self class="modal fade" id="deleteClientModal_{{ $clientId }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h1 class="modal-title fs-5" id="staticBackdropLabel">Delete Confirmation / 删除确认</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>

              <form wire:submit.prevent='destroyClient'>
                  <div class="modal-body">

                      @if (!session()->has('success'))
                          <p class="text-muted">
                              Are you sure you want to delete <strong>{{ $company_name}}</strong>?<br>
                              This action cannot be undone.<br><br>

                              您确定要删除 <strong>{{ $company_name }}</strong> 吗？<br>
                              此操作无法撤销。
                          </p>
                          @if (session()->has('error'))
                              <div class="alert alert-danger">
                                  {{ session('error') }}
                              </div>
                          @endif
                      @else
                          <x-alert-message :color=" 'alert-success' ">
                              {{session('success')}}
                          </x-alert-message>
                      @endif

                  </div>

                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel / 取消</button>
                      <button type="submit" class="btn btn-danger">Yes, Delete / 是的，删除</button>
                  </div>
              </form>
          </div>
      </div>
  </div>
</div>
