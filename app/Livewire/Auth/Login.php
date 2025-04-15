<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Validate;
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{

  #[Validate('required')]
  public $username,$password,$role;

  public function login()
  {
      $this->validate();
        // check if the login credentials is correct 
      if (Auth::attempt([
          'username' => $this->username,
          'password' => $this->password,
          ])) 
        {

          session()->regenerate(); // every login regerate login session 
          
          $user = Auth::user();
        
          // Now check if the user has correct role
          if ($user->role != $this->role) {
            Auth::logout();// clear the session auto logout kapag yong role is not valid or hindi naman talaga sakanya yong role
             
            session()->flash('error','Access Denied!');
           return redirect()->route('login.view');
          }
          // Get the user id every success login then store in session
          $userId = Auth::user()->id;
          session(['userId' => $userId]);

          switch($user->role) {
              case 1:
                  return redirect()->route('admin.dashboard');

              case 3:
                 return redirect()->route('salesman.dashboard');
                   
              // you can add case 2, 3 here
              default:
                  Auth::logout();
                  session()->flash('error', 'Invalid role.');
                  return redirect()->route('login.view');
          }
      }
  
      session()->flash('error', 'Invalid username or password.');
  }
  

    public function render()
    {
        return view('livewire.auth.login');
    }
}
