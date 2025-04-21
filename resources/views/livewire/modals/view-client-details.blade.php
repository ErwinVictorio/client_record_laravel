<div>
    <div  wire:key="client-modal-{{$clientId}}" class="modal fade" wire:ignore.self id="viewClientDetails_{{ $clientId }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 shadow-sm">
                <div style="background-color:  #004998" class="modal-header text-white">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Client Details /客户详情</h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                      @foreach ($findClient as $client )
                      <div class="row g-4">
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="text-muted">Contact Person /联络人</h6>
                                <p class="fw-bold mb-1">{{ $client->contact_person }}</p>

                                <h6 class="text-muted mt-3">Contact Number /联系电话</h6>
                                <p class="fw-bold mb-1">{{ $client->contact_number_person }}</p>

                                <h6 class="text-muted mt-3">Bank Account /银行账户</h6>
                                <p class="fw-bold mb-1">{{$client->bank_account_number ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="text-muted">Product Name /产品名称</h6>
                                <p class="fw-bold mb-1">{{$client->item_name ?? 'N/A' }}</p>

                                <h6 class="text-muted mt-3">Specification /规格</h6>
                                <p class="fw-bold mb-1">{{ $client->specification ?? 'N/A' }}</p>

                                <h6 class="text-muted mt-3">Quantity /数量</h6>
                                <p class="fw-bold mb-1">{{$client->quantity ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="border rounded-3 p-3">
                                <h6 class="text-muted">Address /地址</h6>
                                <p class="fw-bold mb-1">{{$client->address }}</p>
                            </div>
                        </div>
                    </div>
                      @endforeach
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
