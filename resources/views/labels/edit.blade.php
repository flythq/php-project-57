<x-app-layout>
    <x-slot name="header">
        <h2 class="text-6xl text-gray-900 leading-tight">
            {{ __('Edit label') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @php $form = Html::form('PATCH', route('labels.update', $label))->novalidate(); @endphp
                    {!! $form->open() !!}

                        <div>
                            {!! Html::label(__('Name'), 'name')->class('block font-medium text-sm text-gray-700') !!}
                            {!! Html::text('name', $label->name)->id('name')
                                ->class('block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm')
                                ->attribute('required')
                                ->autofocus() !!}
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            {!! Html::label(__('Description'), 'description')->class('block font-medium text-sm text-gray-700') !!}
                            {!! Html::textarea('description', $label->description)->id('description')
                                ->class('block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm')
                                ->attribute('rows', 4) !!}
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('labels.index') }}"
                               class="underline text-sm text-gray-600 hover:text-gray-900 mr-4">
                                {{ __('Back') }}
                            </a>

                            {!! Html::submit(__('Update'))
                                ->class('inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150') !!}
                        </div>
                    {!! $form->close() !!}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
