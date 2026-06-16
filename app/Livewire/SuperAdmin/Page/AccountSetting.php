<?php

namespace App\Livewire\SuperAdmin\Page;

use App\Livewire\Modals\EditAccounts;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class AccountSetting extends Component
{
    use WithPagination;

    public $searchQuery = '';
    protected $paginationTheme = 'bootstrap';



    public $roleMap = [
        0 => ['label' => 'Super Admin', 'class' => 'bg-danger'],
        1 => ['label' => 'Admin',       'class' => 'bg-primary'],
        2 => ['label' => 'Cashier',     'class' => 'bg-success'],
        3 => ['label' => 'Sales Executive', 'class' => 'bg-info'],
        4 => ['label' => 'After Sales', 'class' => 'bg-warning text-dark'],
        5 => ['label' => 'Warehouse', 'class' => 'bg-dark'],
    ];

    public function updatingSearchQuery()
    {
        $this->resetPage();
    }

    #[On('accounts-updated')]
    public function refreshAccounts()
    {
        $this->resetPage();
    }

    public function deleteAccount($id)
    {
        // Prevent deleting the currently logged-in user
        if ($id == Auth::id()) {
            session()->flash('error', 'You cannot delete your own account while logged in.');
            return;
        }

        $user = User::findOrFail($id);
        $user->delete();

        session()->flash('message', 'Account deleted successfully.');
        $this->dispatch('accounts-updated');
    }



    public function render()
    {
        $search = '%' . $this->searchQuery . '%';

        $usersAccounts = User::select('id', 'first_name', 'last_name', 'middle_name', 'NickName', 'username', 'role', 'department')
          ->whereIn('role',['0', '1','2', '3', '4', '5'])
            ->where(function($query) use ($search) {
                $query->where('first_name', 'like', $search)
                      ->orWhere('last_name', 'like', $search)
                      ->orWhere('username', 'like', $search);
            })
            ->paginate(20);

        return view('livewire.super-admin.page.account-setting', [
            'usersAccounts' => $usersAccounts
        ]);
    }
}
