<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <!-- Email Address -->
        <div class="mb-3">
            <x-text-input id="email" type="email" name="email" class="form-control"
                          placeholder="Email" aria-label="Email" aria-describedby="email-addon"
                          :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <!-- Password -->
        <div class="mb-3">
            <x-text-input id="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="password-addon"
                          type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="form-check form-switch">
           <input class="form-check-input" id="rememberMe" type="checkbox" name="remember">
           <label class="form-check-label" for="rememberMe">Remember me</label>
        </div>

        <div class="flex items-center justify-end form-group">
            @if (Route::has('password.request'))
                <a class="underline text-sm" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <div class="text-center">
                <button type="submit" class="btn bs-user-burgendy text-white w-100 mt-4 mb-0 my_btn_style_font_size">Sign in</button>
            </div>
        </div>
    </form>
    <div class="card-footer text-center pt-0 px-lg-2 px-1">
        <p class="mb-4 text-sm mx-auto">
            Don't have an account?
            <a href="javascript:;" class="bs-user-burgendy-txt font-weight-bold">Sign up</a>
        </p>
    </div>
</x-guest-layout>
