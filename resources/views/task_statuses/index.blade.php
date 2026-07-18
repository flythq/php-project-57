<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Statuses') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <a href="{{ route('task_statuses.create') }}"
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Create status') }}
                        </a>
                    </div>

                    @if ($statuses->isEmpty())
                        <p class="text-gray-500">{{ __('No statuses') }}.</p>
                    @else
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b">
                                    <th class="py-2 px-3 uppercase">{{ __('ID') }}</th>
                                    <th class="py-2 px-3 uppercase">{{ __('Name') }}</th>
                                    <th class="py-2 px-3 uppercase">{{ __('Created at') }}</th>
                                    <th class="py-2 px-3 uppercase">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($statuses as $status)
                                    <tr class="border-b">
                                        <td class="py-2 px-3">{{ $status->id }}</td>
                                        <td class="py-2 px-3">{{ $status->name }}</td>
                                        <td class="py-2 px-3">{{ $status->created_at?->format('d.m.Y') }}</td>
                                        <td class="py-2 px-3">
                                            <a href="{{ route('task_statuses.edit', $status) }}"
                                               class="text-blue-600 hover:text-blue-800">{{ __('Edit') }}</a>

                                            <form action="{{ route('task_statuses.destroy', $status) }}"
                                                  method="POST"
                                                  class="inline"
                                                  onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-800 ml-3">
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
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
