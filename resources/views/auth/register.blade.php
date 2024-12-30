<x-guest-layout>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <!-- Name -->
        <div class="mb-3">
            <x-text-input id="name" class="form-control" type="text" placeholder="Name" aria-label="Name" aria-describedby="name-addon"
                          name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <x-text-input id="email" class="form-control" type="email" name="email" placeholder="Email"
                          aria-label="Email" aria-describedby="email-addon" :value="old('email')" required autocomplete="email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <x-text-input id="password" class="form-control"
                            type="password"
                            name="password" placeholder="Password" aria-label="Password" aria-describedby="password-addon"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <!-- Confirm Password -->
        <div class="mb-3">
            <x-text-input id="password_confirmation" class="form-control"
                            type="password" placeholder="Confirm Password" aria-label="Password" aria-describedby="password-addon"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="form-check form-check-info text-left">
            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" checked>
            <label class="form-check-label" for="flexCheckDefault">
                I agree the <a href="javascript:;" class="bs-user-burgendy-txt font-weight-bolder">Terms and Conditions</a>
            </label>
        </div>
        <div class="text-center">
            <button type="submit" class="btn bs-user-burgendy my_btn_style_font_size text-white w-100 my-4 mb-2">Sign up</button>
        </div>
        <p class="text-sm mt-3 mb-0">Already have an account? <a href="{{ route('login') }}" class="bs-user-burgendy-txt font-weight-bolder">Sign in</a></p>
    </form>
</x-guest-layout>
