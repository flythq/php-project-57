<x-app-layout>
    <x-slot name="header">
        <h2 class="text-6xl text-gray-900 leading-tight">
            {{ __('Tasks') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="GET" action="{{ route('tasks.index') }}"
                          class="mb-4 flex flex-wrap gap-4 items-end" novalidate>
                        <div>
                            <select id="filter_status_id" name="filter[status_id]"
                                    class="mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">{{ __('Status') }}</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->id }}"
                                            {{ (string) request('filter.status_id') === (string) $status->id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <select id="filter_assigned_to_id" name="filter[assigned_to_id]"
                                    class="mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">{{ __('Assignee') }}</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                            {{ (string) request('filter.assigned_to_id') === (string) $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <select id="filter_created_by_id" name="filter[created_by_id]"
                                    class="mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">{{ __('Author') }}</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                            {{ (string) request('filter.created_by_id') === (string) $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Apply') }}
                        </button>

                        @auth
                            <a href="{{ route('tasks.create') }}"
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-auto">
                                {{ __('Create task') }}
                            </a>
                        @endauth
                    </form>

                    @if ($tasks->isEmpty())
                        <p class="text-gray-500">{{ __('No tasks') }}.</p>
                    @else
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b">
                                    <th class="py-2 px-3 uppercase">{{ __('ID') }}</th>
                                    <th class="py-2 px-3 uppercase">{{ __('Status') }}</th>
                                    <th class="py-2 px-3 uppercase">{{ __('Name') }}</th>
                                    <th class="py-2 px-3 uppercase">{{ __('Author') }}</th>
                                    <th class="py-2 px-3 uppercase">{{ __('Assignee') }}</th>
                                    <th class="py-2 px-3 uppercase">{{ __('Created at') }}</th>
                                    <th class="py-2 px-3 uppercase">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tasks as $task)
                                    <tr class="border-b">
                                        <td class="py-2 px-3">{{ $task->id }}</td>
                                        <td class="py-2 px-3">{{ $task->status?->name }}</td>
                                        <td class="py-2 px-3">
                                            <a href="{{ route('tasks.show', $task) }}"
                                               class="text-blue-600 hover:text-blue-800">
                                                {{ $task->name }}
                                            </a>
                                        </td>
                                        <td class="py-2 px-3">{{ $task->createdBy?->name }}</td>
                                        <td class="py-2 px-3">{{ $task->assignedTo?->name ?? '—' }}</td>
                                        <td class="py-2 px-3">{{ $task->created_at?->format('d.m.Y') }}</td>
                                        <td class="py-2 px-3">
                                            @auth
                                                <a href="{{ route('tasks.edit', $task) }}"
                                                   class="text-blue-600 hover:text-blue-800">{{ __('Edit') }}</a>
                                            @endauth
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $tasks->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
