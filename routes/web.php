<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Pages\CahierDashboard;
use App\Livewire\Auth\Login;
use App\Livewire\Admin\Pages\ManageSalesMan;
use App\Livewire\SalesMan\SalesManPage;
use App\Livewire\Admin\Pages\Department;




Route::get('/',Login::class)->name('login.view');

// group the admin related routes
Route::middleware(['isAdmin'])->prefix('admin')->group(function (){

    Route::get('/dashboard',Dashboard::class)->name('admin.dashboard');
    Route::get('/salesman',ManageSalesMan::class)->name('admin.salesman');
    Route::get('/department',Department::class)->name('view.department');
});



// grouping the cashier related routes
Route::middleware(['isSalesman'])->prefix('salesman')->group(function(){

    Route::get('/dashboard',SalesManPage::class)->name('salesman.dashboard');
  
});

    

Route::get('cashier/dashboard',CahierDashboard::class)->name('casher.dashboard');