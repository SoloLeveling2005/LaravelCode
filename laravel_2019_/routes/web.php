<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Admin\AdminAuthorization;
use \App\Http\Controllers\Admin\AdminController;
use App\Http\Middleware\Admin\AdminAuth;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('', function () {
    return redirect(route('events'));
});

// auth
// get - return authorization page
// post - authorizes the user
Route::match(['get', 'post'], 'auth', [AdminAuthorization::class, 'login'])->name('auth');


Route::middleware(AdminAuth::class)->group(function () {
    // log_out
    // post - user sign out

    Route::post('logout', function () {
        Auth::guard('organizer')->logout();
        return redirect(route('auth'));
    })->name('logout');

    // events
    // get - return page about all organizer events
    Route::get('events', [AdminController::class, 'events'])->name('events');


    // event
    // get - return event page with information about this event
    // info (event)
    // ticket list
    // session list
    // channel list
    // room list
    Route::get('event/{event_slug}', [AdminController::class, 'event'])->name('event');


    // create_event
    // get - return page with form.
    // post - create event.
    Route::match(['post', 'get'], 'create_event', [AdminController::class, 'create_event'])->name('create_event');


    // edit_event
    // get - return page with form.
    // patch - edit event.
    Route::match(['patch', 'get'], 'event/{slug}/edit_event', [AdminController::class, 'edit_event'])->name('edit_event');

    // create_ticket
    // get - return page with form.
    // post - create ticket.
    Route::match(['post', 'get'], 'event/{slug}/create_ticket', [AdminController::class, 'create_ticket'])->name('create_ticket');



    // session
    // get - return page with form.
    // post - create session.
    // patch - edit session.
    Route::match(['post', 'get', 'patch'], 'event/{slug}/session', [AdminController::class, 'session'])->name('session');


    // create_channel
    // get - return page with form.
    // post - create channel.
    Route::match(['post', 'get'], 'event/{slug}/create_channel', [AdminController::class, 'create_channel'])->name('create_channel');


    // create_room
    // get - return page with form.
    // post - create room.
    Route::match(['post', 'get'], 'event/{slug}/create_room', [AdminController::class, 'create_room'])->name('create_room');
});
