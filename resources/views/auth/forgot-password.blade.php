<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    @php $form = Html::form('POST', route('password.email'))->novalidate(); @endphp
    {!! $form->open() !!}
        <div>
            {!! Html::label(__('Email'), 'email')->class('block font-medium text-sm text-gray-700') !!}
            {!! Html::email('email')->id('email')
                ->class('block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm')
                ->attribute('required')
                ->autofocus() !!}
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            {!! Html::submit(__('Email Password Reset Link'))
                ->class('inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150') !!}
        </div>
    {!! $form->close() !!}
</x-guest-layout>
