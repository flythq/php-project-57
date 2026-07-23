<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @php $form = Html::form('POST', route('login'))->novalidate(); @endphp
    {!! $form->open() !!}
        @csrf

        <div>
            {!! Html::label(__('Email'), 'email')->class('block font-medium text-sm text-gray-700') !!}
            {!! Html::email('email')->id('email')
                ->class('block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm')
                ->attribute('required')
                ->autofocus()
                ->attribute('autocomplete', 'username') !!}
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            {!! Html::label(__('Password'), 'password')->class('block font-medium text-sm text-gray-700') !!}
            {!! Html::password('password')->id('password')
                ->class('block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm')
                ->attribute('required')
                ->attribute('autocomplete', 'current-password') !!}
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                {!! Html::checkbox('remember')->id('remember_me')
                    ->class('rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500') !!}
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                   href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            {!! Html::submit(__('Log in'))
                ->class('inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 ml-2') !!}
        </div>
    {!! $form->close() !!}
</x-guest-layout>
