<div class="container-fluid vh-100 p-0 d-flex">
  <!-- Left side: Testimonial -->
  <style>
    #bg{
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
      height: 100vh;
      background-image: linear-gradient(to bottom,#033c78, rgba(16, 12, 12, 0.8)), url({{asset('images/bg_image.jpg')}});
    }
    img{
       height: 120px;
       width: 120px;
       object-fit: cover;

    }
  </style>
  <div id="bg" class="col-lg-6 d-none d-lg-flex flex-column justify-content-center align-items-center text-white p-5" style="background-color:  #033c78; background-size: cover; background-position: center;">
      <img src="{{asset('images/asap_logo.jpg.avif')}}" alt="ss">
      <h1> ASAP FORKLIFT PHILIPPINES, INC.</h1>
    <h5 class="fw-bold mb-4">Client Record Management System<h5>
  </div>

  <!-- Right side: Login form -->
  <div class="col-lg-6 d-flex justify-content-center align-items-center bg-light">
    <form wire:submit='login' class="w-75 w-md-50 shadow-sm p-4 rounded bg-white">
      <h1 class="fw-bold mb-1 text-center">Welcome(欢迎)</h1>
      <p class="text-muted text-center mb-4">
        Enter your credentials to access your account
      </p>  

      @if (session()->has('error'))
      <div class="alert alert-danger">
        <i class="bi bi-exclamation-circle-fill me-2"></i>
        {{ session('error') }}
      </div>
      @endif

      <div class="mb-3">
        <label class="form-label">Username (用户名)</label>
        <input wire:model='username' type="text" class="form-control" placeholder="username">
        @error('username')
        <span class="text-danger small">{{ $message }}</span>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Password (密码)</label>
        <input wire:model='password' type="password" class="form-control" placeholder="Password">
        @error('password')
        <span class="text-danger small">{{ $message }}</span>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Select Role (选择岗位)</label>
        <select wire:model='role' class="form-select">
          <option value="">Select role</option>
          <option value="0">Chief Administrator (首席管理员)</option>
          <option value="1">Administrator (管理员)</option>
          <option value="2">Cashier (出纳)</option>
          <option value="3">Sales Executive (销售业务员)</option>
          <option value="4">MSD</option>
          <option value="5">Warehouse</option>
        </select>
        @error('role')
        <span class="text-danger small">{{ $message }}</span>
        @enderror
      </div>

      <div class="d-grid mb-3">
        <button type="submit" class="btn text-white fw-semibold" style="background-color:  #033c78;">
          Log in
        </button>
    
      </div>
    </form>
  </div>
</div>
