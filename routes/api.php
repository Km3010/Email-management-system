<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\GroupController;
use App\Mail\SendEmailMailable;

Route::prefix('v1')->group(function () {
    Route::post('emails', [EmailController::class, 'store']);
    Route::post('groups', [EmailController::class, 'create_group']);
    Route::post('emails/{id}/attach', [EmailController::class, 'attachToGroup']);
    Route::post('emails/{id}/queue', [EmailController::class, 'sendToQueue']);

    Route::post('groups', [GroupController::class, 'store']);

    Route::get('/test-email', function () {
        $email = \App\Models\Email::first(); // Use any existing email record
        Mail::to(explode(',', $email->recipients))->send(new SendEmailMailable($email));
        return 'Email sent';
    });

});
