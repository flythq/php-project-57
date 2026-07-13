<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

Route::get('/', function () {
    return view('layouts.app');
});

Route::get('/testing-logs', function () {
    // Log to all channels in the stack (including Sentry)
    Log::info('This is an info message');
    Log::warning('This is an warning message');
    Log::error('This is an error message');
    // Log directly to the Sentry channel
    Log::channel('sentry_logs')->error('This will only go to Sentry');

    return "Logs sending!";
});
