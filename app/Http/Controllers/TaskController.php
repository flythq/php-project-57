<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Label;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters(
                AllowedFilter::exact('status_id'),
                AllowedFilter::exact('assigned_to_id'),
                AllowedFilter::exact('created_by_id'),
            )
            ->with(['status', 'createdBy', 'assignedTo', 'labels'])
            ->orderByDesc('id')
            ->paginate(15);

        $statuses = TaskStatus::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('tasks.index', compact('tasks', 'statuses', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $statuses = TaskStatus::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        $labels = Label::orderBy('name')->get();

        return view('tasks.create', compact('statuses', 'users', 'labels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $labels = $data['labels'] ?? [];
        unset($data['labels']);

        $task = new Task($data);
        $task->created_by_id = Auth::id();
        $task->save();
        $task->labels()->sync($labels);

        flash(__('Task created successfully.'))->success();

        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): View
    {
        $task->load(['status', 'createdBy', 'assignedTo', 'labels']);

        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task): View
    {
        $statuses = TaskStatus::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        $labels = Label::orderBy('name')->get();

        return view('tasks.edit', compact('task', 'statuses', 'users', 'labels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTaskRequest $request, Task $task): RedirectResponse
    {
        $data = $request->validated();
        $labels = $data['labels'] ?? [];
        unset($data['labels']);

        $task->update($data);
        $task->labels()->sync($labels);

        flash(__('Task updated successfully.'))->success();

        return redirect()->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): RedirectResponse
    {
        Gate::authorize('delete', $task);

        $task->delete();

        flash(__('Task deleted successfully.'))->success();

        return redirect()->route('tasks.index');
    }
}
