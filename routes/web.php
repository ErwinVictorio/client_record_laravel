<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Dashboard;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/admin/dashboard',Dashboard::class)->name('admin.dashboard');