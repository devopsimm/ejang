<x-guest-layout>
        <div class="mb-4 text-sm text-gray-600">
            {{ new Illuminate\Support\HtmlString(__("Received an email with a login code? If not, click <a class=\"hover:underline\" href=\":url\">here</a>.", ['url' => route('verify.resend')])) }}
        </div>

        <form method="POST" action="{{ route('verify.store') }}">
            @csrf
            <div>
                <x-input-label for="two_factor_code" :value="__('Code')" />
                <x-text-input id="two_factor_code" class="mt-1 block w-full" type="text" name="two_factor_code" required autofocus />
                <x-input-error :messages="$errors->get('two_factor_code')" class="mt-2" />
            </div>
            <div class="mt-4 flex justify-end">
                <div class="text-center">
                    <button type="submit" class="btn bs-user-burgendy text-white w-100 mt-4 mb-0 my_btn_style_font_size">Sign in</button>
                </div>
            </div>
        </form>

</x-guest-layout>
