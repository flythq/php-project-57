<x-app-layout>
    <x-slot name="header">
        <h2 class="text-6xl text-gray-900 leading-tight">
            {{ __('Labels') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @auth
                        <div class="mb-4">
                            <a href="{{ route('labels.create') }}"
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Create label') }}
                            </a>
                        </div>
                    @endauth

                    @if ($labels->isEmpty())
                        <p class="text-gray-500">{{ __('No labels') }}.</p>
                    @else
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b">
                                    <th class="py-2 px-3 uppercase">{{ __('ID') }}</th>
                                    <th class="py-2 px-3 uppercase">{{ __('Name') }}</th>
                                    <th class="py-2 px-3 uppercase">{{ __('Description') }}</th>
                                    <th class="py-2 px-3 uppercase">{{ __('Created at') }}</th>
                                    <th class="py-2 px-3 uppercase">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($labels as $label)
                                    <tr class="border-b">
                                        <td class="py-2 px-3">{{ $label->id }}</td>
                                        <td class="py-2 px-3">{{ $label->name }}</td>
                                        <td class="py-2 px-3">{{ $label->description ?? '—' }}</td>
                                        <td class="py-2 px-3">{{ $label->created_at?->format('d.m.Y') }}</td>
                                        <td class="py-2 px-3">
                                            @auth
                                                <form action="{{ route('labels.destroy', $label) }}"
                                                      method="POST"
                                                      class="inline"
                                                      onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="text-red-600 hover:text-red-800">
                                                        {{ __('Delete') }}
                                                    </button>
                                                </form>

                                                <a href="{{ route('labels.edit', $label) }}"
                                                   class="text-blue-600 hover:text-blue-800 ml-3">{{ __('Edit') }}</a>
                                            @endauth
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
