<?php
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Pages\CahierDashboard;
use App\Livewire\Auth\Login;
use App\Livewire\Admin\Pages\ManageSalesMan;
use App\Livewire\SalesMan\SalesManPage;
use App\Livewire\Admin\Pages\Department;
use App\Livewire\Admin\Pages\DepartmentSummary;
use App\Livewire\SalesMan\AutoRepair;
use App\Livewire\Salesman\RepairAndMaintence;
use App\Livewire\Admin\Pages\AutoRepairAndMaintenance;
use App\Livewire\Admin\Pages\AutoRepairRecords;
use App\Livewire\Cashier\DepartmentSummary as SummaryDepartment;
use App\Livewire\SuperAdmin\Dashboard as SuperAdminDashboard;
use App\Livewire\SuperAdmin\EditUserAccount;
use App\Livewire\SuperAdmin\Page\Cashier;
use App\Livewire\SuperAdmin\Page\ManageDepartment;
use App\Livewire\SuperAdmin\Page\ManageSalesExecutive;
use App\Livewire\SuperAdmin\Page\AutoPartsRecord;
use App\Livewire\SuperAdmin\Page\AutoRepareAndMaintenanceRecords;
use App\Livewire\SuperAdmin\Page\DepartmentSummary as depSummary;
use App\Livewire\SuperAdmin\Page\SalesmanDashboard;
use App\Livewire\SuperAdmin\ManageClient\CreateFinishVehicle;
use App\Livewire\SuperAdmin\Page\AccountSetting;
use App\Livewire\AfterSales\Dashboard as AfterSalesDashboard;
use App\Livewire\Warehouse\Dashboard as WarehouseDashboard;

Route::get('/',Login::class)->name('login.view');

// group the admin related routes
Route::middleware(['isAdmin'])->prefix('admin')->group(function (){

    Route::get('/dashboard',Dashboard::class)->name('admin.dashboard');
    Route::get('/salesman',ManageSalesMan::class)->name('admin.salesman');
    Route::get('/department',Department::class)->name('view.department');
    Route::get('/department-summary',DepartmentSummary::class)->name('department-summary');
    Route::get('/auto-repair-record',AutoRepairRecords::class)->name('view.repair_records');
    Route::get('/repair-maintenance-record',AutoRepairAndMaintenance::class)->name('view.repair_maintenance');

});

// grouping the cashier related routes
Route::middleware(['isSalesman'])->prefix('salesman')->group(function(){
    
    Route::get('/dashboard',SalesManPage::class)->name('salesman.dashboard');
    Route::get('/autorepair',AutoRepair::class)->name('salesman.autoRepair');
    Route::get('/repair-and-maintenance',RepairAndMaintence::class)->name('salesman.maintenance_and_repair');
});

// Route for Cashier
Route::middleware(['isCashier'])->prefix('cashier')->group(function(){

    Route::get('/dashboard',CahierDashboard::class)->name('casher.dashboard');
    Route::get('/summary',SummaryDepartment::class)->name('summary');

});           

// Route for After Sales
Route::middleware(['isAfterSales'])->prefix('after-sales')->group(function(){
    Route::get('/dashboard', AfterSalesDashboard::class)->name('afterSales.dashboard');
});

// Route for Warehouse
Route::middleware(['isWarehouse'])->prefix('warehouse')->group(function(){
    Route::get('/dashboard', WarehouseDashboard::class)->name('warehouse.dashboard');
});


// routes for SuperAdmin

Route::middleware(['isSuperAdmin'])->prefix('superAdmin')->group(function(){

    Route::get('/Dashboard',SuperAdminDashboard::class)->name('superAdminDashboard.view');
    Route::get('/departments',ManageDepartment::class)->name('superAdmin.departments_list');
    Route::get('/managesalesexecutive',ManageSalesExecutive::class)->name('manageSalesExecutive.view');
    Route::get('/autoPartsRecord',AutoPartsRecord::class)->name('AutoPartsRecord.view');
    Route::get('/autoPartsMaintenence',AutoRepareAndMaintenanceRecords::class)->name('autoPartsMaintenence.view');
    Route::get('/cashier',Cashier::class)->name('cashier.view');
    Route::get('/departmentsummary',depSummary::class)->name('depSummary.view');
    Route::get('/salesmandashboard',SalesmanDashboard::class)->name('salesman.view');
    Route::get('/createfinishvehicle',CreateFinishVehicle::class)->name('createFinishVhicle.view');
    Route::get('/account-setting',AccountSetting::class)->name('accountSetting.view');
    Route::get('/edit-account/{id}', EditUserAccount::class)->name('edit-account.view');
});
