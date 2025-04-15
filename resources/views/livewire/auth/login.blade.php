<div class="container-fluid d-flex flex-column justify-content-center align-items-center" style="height: 100vh; background-color: aliceblue;">
  <form wire:submit='login' class="card col-11 col-sm-8 col-md-6 col-lg-4 col-xl-3 shadow border-0">
    
    <div class="card-header text-center text-white p-4" style="background-color: #004998;">
      <h4 class="fw-bold mb-1">LOGIN</h4>
      <small style="color: rgb(210, 214, 214);">Enter your credentials to access your account</small>
    </div>

    @if (session()->has('error'))
    <div class="alert alert-danger m-3 mb-0">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-circle" viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
        <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
      </svg>
      {{ session('error') }}
    </div>
    @endif

    <div class="card-body p-4">

      <div class="mb-3">
        <div class="input-group">
          <span class="input-group-text">
            <i class="fas fa-user"></i>
          </span>
          <input wire:model='username' type="text" class="form-control" placeholder="Username" aria-label="Username">
        </div>
        @error('username')
        <span class="text-danger small">{{ $message }}</span>
        @enderror
      </div>

      <div class="mb-3">
        <div class="input-group">
          <span class="input-group-text">
            <i class="fas fa-lock"></i>
          </span>
          <input wire:model='password' type="password" class="form-control" placeholder="Password" aria-label="Password">
        </div>
        @error('password')
        <span class="text-danger small">{{ $message }}</span>
        @enderror
      </div>

      <div class="mb-3">
        <select wire:model='role' class="form-select">
          <option value="">Select role</option>
          <option value="1">Admin</option>
          <option value="2">Cashier</option>
          <option value="3">Salesman</option>
        </select>
        @error('role')
        <span class="text-danger small">{{ $message }}</span>
        @enderror
      </div>

      <div class="d-grid">
        <button type="submit" class="btn text-white" style="background-color: #0C2968;">
          Login <i class="fas fa-arrow-right ms-1"></i>
        </button>
      </div>

    </div>
  </form>
</div>
