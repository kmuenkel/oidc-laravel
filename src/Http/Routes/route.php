<?php

use Illuminate\Support\Facades\Route;
use Lti\Http\Controllers\LtiController;

Route::group(['prefix' => 'oidc', 'as' => 'oidc.', 'middleware' => 'web'], function () {
    config('oidc.routes.auth') && Route::get('auth', [
        'as' => 'auth',
        'middleware' => config('oidc.routes.auth.middleware', []),
        'uses' => LtiController::class.'@auth'
    ]);
});
