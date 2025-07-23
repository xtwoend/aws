<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="mb-3">
        <label class="form-label" for="update_password_current_password">Current Password</label>
        <input type="password" 
               class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
               id="update_password_current_password" 
               name="current_password">
        @error('current_password', 'updatePassword')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label" for="update_password_password">New Password</label>
        <input type="password" 
               class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
               id="update_password_password" 
               name="password">
        @error('password', 'updatePassword')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label" for="update_password_password_confirmation">Confirm Password</label>
        <input type="password" 
               class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" 
               id="update_password_password_confirmation" 
               name="password_confirmation">
        @error('password_confirmation', 'updatePassword')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-footer">
        <button type="submit" class="btn btn-primary">Save Changes</button>

        @if (session('status') === 'password-updated')
            <span class="text-success ms-2">
                <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                Saved.
            </span>
        @endif
    </div>
</form>
