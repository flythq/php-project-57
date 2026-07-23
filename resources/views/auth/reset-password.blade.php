<x-guest-layout>
    @php $form = Html::form('POST', route('password.store'))->novalidate(); @endphp
    {!! $form->open() !!}
        {!! Html::hidden('token', $request->route('token')) !!}

        <div>
            {!! Html::label(__('Email'), 'email')->class('block font-medium text-sm text-gray-700') !!}
            {!! Html::email('email', $request->email)->id('email')
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
                ->attribute('autocomplete', 'new-password') !!}
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            {!! Html::label(__('Confirm Password'), 'password_confirmation')->class('block font-medium text-sm text-gray-700') !!}
            {!! Html::password('password_confirmation')->id('password_confirmation')
                ->class('block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm')
                ->attribute('required')
                ->attribute('autocomplete', 'new-password') !!}
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            {!! Html::submit(__('Reset Password'))
                ->class('inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150') !!}
        </div>
    {!! $form->close() !!}
</x-guest-layout>
