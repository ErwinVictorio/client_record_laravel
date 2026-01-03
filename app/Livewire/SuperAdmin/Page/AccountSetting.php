<?php

namespace App\Livewire\SuperAdmin\Page;

use App\Livewire\Modals\EditAccounts;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class AccountSetting extends Component
{
    use WithPagination;

    public $searchQuery = '';
    protected $paginationTheme = 'bootstrap';



    public $roleMap = [
        0 => ['label' => 'Super Admin', 'class' => 'bg-danger'],
        1 => ['label' => 'Admin',       'class' => 'bg-primary'],
        2 => ['label' => 'Cashier',     'class' => 'bg-success']
    ];

    public function updatingSearchQuery()
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
    }



    public function render()
    {
        $search = '%' . $this->searchQuery . '%';

        $usersAccounts = User::select('id', 'first_name', 'last_name', 'middle_name', 'NickName', 'username', 'role', 'department')
          ->whereIn('role',['0', '1','2'])
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