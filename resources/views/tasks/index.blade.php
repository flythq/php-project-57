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
                    @php $filterForm = Html::form('GET', route('tasks.index')); @endphp
                    {!! $filterForm->class('mb-4 flex flex-wrap gap-4 items-end')->novalidate()->open() !!}
                        <div>
                            {!! Html::select('filter[status_id]', $statuses->pluck('name', 'id'), request('filter.status_id'))
                                ->id('filter_status_id')
                                ->placeholder(__('Status'))
                                ->class('mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm') !!}
                        </div>

                        <div>
                            {!! Html::select('filter[assigned_to_id]', $users->pluck('name', 'id'), request('filter.assigned_to_id'))
                                ->id('filter_assigned_to_id')
                                ->placeholder(__('Assignee'))
                                ->class('mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm') !!}
                        </div>

                        <div>
                            {!! Html::select('filter[created_by_id]', $users->pluck('name', 'id'), request('filter.created_by_id'))
                                ->id('filter_created_by_id')
                                ->placeholder(__('Author'))
                                ->class('mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm') !!}
                        </div>

                        {!! Html::submit(__('Apply'))
                            ->class('bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded') !!}

                        @auth
                            <a href="{{ route('tasks.create') }}"
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-auto">
                                {{ __('Create task') }}
                            </a>
                        @endauth
                    {!! $filterForm->close() !!}

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
                                                @can('delete', $task)
                                                    @php
                                                        $deleteFormId = 'delete-form-' . $task->id;
                                                        $deleteForm = Html::form('DELETE', route('tasks.destroy', $task))
                                                            ->attribute('id', $deleteFormId)
                                                            ->class('hidden inline ml-3');
                                                    @endphp
                                                    <a href="#"
                                                       class="text-red-600 hover:text-red-800 ml-3"
                                                       onclick="event.preventDefault();
                                                                if (confirm('{{ __('Are you sure?') }}'))
                                                                    document.getElementById('{{ $deleteFormId }}').submit();">
                                                        {{ __('Delete') }}
                                                    </a>
                                                    {!! $deleteForm->open() !!}
                                                    {!! $deleteForm->close() !!}
                                                @endcan
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
