<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLabelRequest;
use App\Models\Label;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $labels = Label::orderBy('id')->get();

        return view('labels.index', compact('labels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('labels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLabelRequest $request): RedirectResponse
    {
        Label::create($request->validated());

        flash(__('Label created successfully.'))->success();

        return redirect()->route('labels.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Label $label): View
    {
        return view('labels.edit', compact('label'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreLabelRequest $request, Label $label): RedirectResponse
    {
        $label->update($request->validated());

        flash(__('Label updated successfully.'))->success();

        return redirect()->route('labels.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Label $label): RedirectResponse
    {
        if ($label->tasks()->exists()) {
            flash(__('Не удалось удалить метку'))->error();

            return redirect()->route('labels.index');
        }

        $label->delete();

        flash(__('Label deleted successfully.'))->success();

        return redirect()->route('labels.index');
    }
}
