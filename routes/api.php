<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TerminalController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\TicketController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('terminal', [TerminalController::class, 'index']);
Route::get('getCode', [TerminalController::class, 'getCode']);
Route::post('webhook', [WebhookController::class, 'in']);
Route::post('orderAndCharge', [OrderController::class, 'createAndCharge']);
Route::post('cash', [OrderController::class, 'cashPayment']);
Route::get('order', [OrderController::class, 'index']);
Route::get('order/{id}', [OrderController::class, 'getOne']);
Route::post('assign', [TicketController::class, 'assignTickets']);
Route::post('draw', [TicketController::class, 'doDraw']);
Route::get('ticket', [TicketController::class, 'index']);
Route::get('howManyTickets', [TicketController::class, 'howManyTickets']);
Route::get('winners', [TicketController::class, 'getWinners']);
//Route::post('markAsPaid', [OrderController::class, 'markAsPaid']);