<x-guest-layout>
    {{ html()->form('POST', route('register'))->novalidate()->open() }}
        @csrf

        <div>
            {{ html()->label(__('Name'), 'name')->class('block font-medium text-sm text-gray-700') }}
            {{ html()->text('name')->id('name')
                ->class('block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm')
                ->attribute('required')
                ->autofocus()
                ->attribute('autocomplete', 'name') }}
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            {{ html()->label(__('Email'), 'email')->class('block font-medium text-sm text-gray-700') }}
            {{ html()->email('email')->id('email')
                ->class('block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm')
                ->attribute('required')
                ->attribute('autocomplete', 'username') }}
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            {{ html()->label(__('Password'), 'password')->class('block font-medium text-sm text-gray-700') }}
            {{ html()->password('password')->id('password')
                ->class('block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm')
                ->attribute('required')
                ->attribute('autocomplete', 'new-password') }}
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            {{ html()->label(__('Confirm Password'), 'password_confirmation')->class('block font-medium text-sm text-gray-700') }}
            {{ html()->password('password_confirmation')->id('password_confirmation')
                ->class('block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm')
                ->attribute('required')
                ->attribute('autocomplete', 'new-password') }}
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
               href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            {{ html()->submit(__('Register'))
                ->class('inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 ml-2') }}
        </div>
    {{ html()->form('POST', route('register'))->novalidate()->close() }}
</x-guest-layout>
