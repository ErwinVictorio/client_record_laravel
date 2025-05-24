@php
    $completeName = $salesmanInfo->first_name . ' ' . $salesmanInfo->last_name;
@endphp

<div> 
    <div class="modal fade" id="ModalViewSalesManInfo_{{$salesmanID}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div style="background-color:  #004998" class="modal-header">
                    <h1 class="modal-title fs-5 text-light" id="staticBackdropLabel">{{$completeName}}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-4">
                        <!-- Basic Info -->
                        <div class="col-lg-4">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-header bg-white border-bottom-0">
                                    <h6 style="color:  #004998" class="fw-bold">
                                        <i class="fas fa-user"></i>
                                        Basic Info (基本信息)
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled text-muted mb-0">
                                        <li><strong>Name (姓名):</strong> {{$completeName}}</li>
                                        <li><strong>Department (部门):</strong> {{$salesmanInfo->department}}</li>
                                        <li><strong>Username (用户名):</strong> {{$salesmanInfo->username}}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Sales Info -->
                        <div class="col-lg-8">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-header bg-white border-bottom-0">
                                    <h6 style="color: #004998" class="fw-bold">
                                        <i class="fas fa-chart-bar"></i>
                                        Sales Overview (销售概览)
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                         <!-- Total Client Count -->
                                    <div class="mt-4 text-center border-top pt-3">
                                        <h6 class="text-muted">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                                                <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                                            </svg>
                                            Total Clients (客户总数)
                                        </h6>
                                        <div class="fs-5 fw-bold">{{$totalClient}}</div>
                                    </div>
                                        <!-- Total Sold -->
                                        <div class="col-md-6">
                                            <div style="color: #004998" class="p-3 rounded shadow-sm text-center">
                                                <h6 class="mb-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-check-fill" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd" d="M15.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L12.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
                                                        <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                                                    </svg>
                                                    Total Sold (总销售)
                                                </h6>
                                                <div class="fs-4 fw-bold">{{$SoldClient}}</div>
                                                <div class="progress mt-2" style="height: 6px;">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{$soldPercent}}%;" aria-valuenow="{{$soldPercent}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <small>{{$soldPercent}}%</small>
                                            </div>
                                        </div>

                                        <!-- Total Pending -->
                                        <div class="col-md-6">
                                            <div style="color:  #004998" class="p-3 rounded shadow-sm text-center">
                                                <h6 class="mb-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hourglass-split" viewBox="0 0 16 16">
                                                        <path d="M2.5 15a.5.5 0 1 1 0-1h1v-1a4.5 4.5 0 0 1 2.557-4.06c.29-.139.443-.377.443-.59v-.7c0-.213-.154-.451-.443-.59A4.5 4.5 0 0 1 3.5 3V2h-1a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-1v1a4.5 4.5 0 0 1-2.557 4.06c-.29.139-.443.377-.443.59v.7c0 .213.154.451.443.59A4.5 4.5 0 0 1 12.5 13v1h1a.5.5 0 0 1 0 1zm2-13v1c0 .537.12 1.045.337 1.5h6.326c.216-.455.337-.963.337-1.5V2zm3 6.35c0 .701-.478 1.236-1.011 1.492A3.5 3.5 0 0 0 4.5 13s.866-1.299 3-1.48zm1 0v3.17c2.134.181 3 1.48 3 1.48a3.5 3.5 0 0 0-1.989-3.158C8.978 9.586 8.5 9.052 8.5 8.351z"/>
                                                    </svg>
                                                    Total Pending (待处理总数)
                                                </h6>
                                                <div class="fs-4 fw-bold">{{$Pending}}</div>
                                                <div class="progress mt-2" style="height: 6px;">
                                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{$PendingPercent}}%;" aria-valuenow="{{$PendingPercent}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <small>{{$PendingPercent}}%</small>
                                            </div>
                                        </div>
                                    </div>

               
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- /.modal-body -->
            </div> <!-- /.modal-content -->
        </div>
    </div>
</div>
