<div>
    <div wire:ignore.self class="modal fade" id="DeleteClient_{{$clientId}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div style="background-color: #004998" class="modal-header text-light">
              <h1 class="modal-title fs-5" id="staticBackdropLabel">Delete Connfirmation / 删除确认</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form wire:submit='delete_client'>
            <div class="modal-body">
                @if (!session()->has('success'))
                    <p class="text-muted">
                        Are you sure you want to delete <strong>{{$companyName}}</strong>?<br>
                        此操作无法撤销。
                    </p>
                @else

                  <x-alert-message :color=" 'alert-success' ">
                    {{session('success')}}
                  </x-alert-message>
                  @endif
                
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                Cancel / 取消
            </button>
              <button type="submit" class="btn btn-danger">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                    <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                  </svg>                   
                  Yes, Delete / 是的，删除
            </button>
          </form>
            </div>
          </div>
        </div>
      </div>
</div>
