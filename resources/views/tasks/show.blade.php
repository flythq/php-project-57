<x-app-layout>
    <x-slot name="header">
        <h2 class="text-6xl text-gray-900 leading-tight">
            {{ __('View task') }}: {{ $task->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <div>
                        <span class="font-semibold">{{ __('Name') }}:</span>
                        <span class="ms-1">{{ $task->name }}</span>
                    </div>

                    <div>
                        <span class="font-semibold">{{ __('Status') }}:</span>
                        <span class="ms-1">{{ $task->status?->name }}</span>
                    </div>

                    <div>
                        <span class="font-semibold">{{ __('Description') }}:</span>
                        <div class="mt-1 whitespace-pre-wrap">{{ $task->description ?? '—' }}</div>
                    </div>

                    <div>
                        <span class="font-semibold">{{ __('Labels') }}:</span>
                        <div class="mt-1 flex flex-wrap gap-2">
                            @forelse ($task->labels as $label)
                                <span class="inline-block bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">
                                    {{ $label->name }}
                                </span>
                            @empty
                                <span>—</span>
                            @endforelse
                        </div>
                    </div>

                    <div class="flex items-center pt-4">
                        <a href="{{ route('tasks.index') }}"
                           class="underline text-sm text-gray-600 hover:text-gray-900 mr-4">
                            {{ __('Back') }}
                        </a>

                        @auth
                            <a href="{{ route('tasks.edit', $task) }}"
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                                {{ __('Edit') }}
                            </a>

                            @can('delete', $task)
                                <form action="{{ route('tasks.destroy', $task) }}"
                                      method="POST"
                                      onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                        {{ __('Delete') }}
                                    </button>
                                </form>
                            @endcan
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
