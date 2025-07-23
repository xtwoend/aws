@extends('layouts.auth')

@section('title', 'Sign In')

@section('content')
<div class="row flex justify-content-center">
    {{-- <div class="col-4"> --}}
        <div class="card card-md">
            <div class="card-body">
                <h2 class="h2 text-center mb-4">Login to your account</h2>
                
                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" autocomplete="off" novalidate>
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label" for="email">Email address</label>
                        <input type="email" 
                            class="form-control @error('email') is-invalid @enderror" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            placeholder="your@email.com" 
                            autocomplete="off" 
                            required 
                            autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-2">
                        <label class="form-label" for="password">
                            Password
                            @if (Route::has('password.request'))
                                <span class="form-label-description">
                                    <a href="{{ route('password.request') }}">I forgot password</a>
                                </span>
                            @endif
                        </label>
                        <div class="input-group input-group-flat">
                            <input type="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                id="password" 
                                name="password" 
                                placeholder="Your password" 
                                autocomplete="off" 
                                required>
                            <span class="input-group-text">
                                <a href="#" class="link-secondary" title="Show password" data-bs-toggle="tooltip">
                                    <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                </a>
                            </span>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-2">
                        <label class="form-check">
                            <input type="checkbox" class="form-check-input" name="remember" id="remember_me" />
                            <span class="form-check-label">Remember me on this device</span>
                        </label>
                    </div>
                    
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Sign in</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="text-center text-muted mt-3">
            Don't have account yet? <a href="{{ route('register') }}" tabindex="-1">Sign up</a>
        </div>
    {{-- </div> --}}
</div>
@endsection
