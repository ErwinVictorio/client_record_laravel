<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Validate;
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{

    #[Validate('required')]
    public $username, $password, $role;

    public function login()
    {
        $this->validate();
        // check if the login credentials is correct 
        if (Auth::attempt([
            'username' => $this->username,
            'password' => $this->password,
        ])) {

            session()->regenerate(); // every login regerate login session 

            $user = Auth::user();

            // Now check if the user has correct role
            if ($user->role != $this->role) {
                Auth::logout(); // clear the session auto logout kapag yong role is not valid or hindi naman talaga sakanya yong role

                session()->flash('error', 'Access Denied!');
                return redirect()->route('login.view');
            }
            // Get the user id every success login then store in session
            $userId = Auth::user()->id;
            session(['userId' => $userId]);

            switch ($user->role) {
                case 0:
                    return redirect()->route('superAdminDashboard.view');
                    break;

                case 1:
                    return redirect()->route('admin.dashboard');
                    break;

                case 3:
                    return redirect()->route('salesman.dashboard');
                    break;

                case 2:
                    return redirect()->route('casher.dashboard');
                    break;

                case 4:
                    return redirect()->route('afterSales.dashboard');
                    break;

                case 5:
                    return redirect()->route('warehouse.dashboard');
                    break;

                default:
                    Auth::logout();
                    session()->flash('error', 'Invalid role.');
                    return redirect()->route('login.view');
            }
        }

        session()->flash('error', 'Invalid username or password.');
    }


    // To prvent Back To lOgin page if already login
    public function mount()
    {
        if (Auth::check()) {
            $user = Auth::user(); // get the cuurent user na naka login

            switch ($user->role) {
                case 0:
                    return redirect()->route('superAdminDashboard.view');
                    break;

                case 1:
                    return redirect()->route('admin.dashboard');
                    break;
                case 3:
                    return redirect()->route('salesman.dashboard');
                    break;
                case 2:
                    return redirect()->route('casher.dashboard');
                    break;

                case 4:
                    return redirect()->route('afterSales.dashboard');
                    break;

                case 5:
                    return redirect()->route('warehouse.dashboard');
                    break;

                default:
                    Auth::logout(); // logout if invalid role
                    return redirect()->route('login.view');
            }
        }
    }



    public function render()
    {
        return view('livewire.auth.login');
    }
}
