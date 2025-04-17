<div>
  <div wire:ignore.self class="modal fade" id="deleteSalesmanModal_{{ $salesmanID }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
              <div style="background-color: #004998;" class="modal-header text-light">
                  <h1 class="modal-title fs-5" id="staticBackdropLabel">Delete Confirmation / 删除确认</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>

              <form wire:submit='onDeleteSalesman'>
                  <div class="modal-body">

                      @if (!session()->has('success'))
                          <p class="text-muted">
                              Are you sure you want to delete <strong>{{ $name->first_name . ' ' . $name->last_name }}</strong>?<br>
                              This action cannot be undone.<br><br>

                              您确定要删除 <strong>{{ $name->first_name . ' ' . $name->last_name }}</strong> 吗？<br>
                              此操作无法撤销。
                          </p>
                      @else
                          <div class="alert alert-success">
                              <div class="alert alert-primary d-flex align-items-center" role="alert">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-circle" viewBox="0 0 16 16">
                                      <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                      <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                                  </svg>
                                  <div class="ms-2">
                                      {{ session('success') }}<br>
                                      删除成功！
                                  </div>
                              </div>
                          </div>
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
