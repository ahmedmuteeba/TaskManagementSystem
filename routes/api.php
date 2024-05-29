<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => '/users'], function ($router) {
    $router->get('/', [UserController::class, 'index'])->middleware('api');
    $router->post('/register', [UserController::class, 'register']);
    $router->post('/login', [UserController::class, 'login']);
    $router->post('/{user_id}/status', [UserController::class, 'changeStatus'])->middleware('api');
});
Route::group(['middleware'=>['api'],'prefix' => '/tasks'], function ($router) {
    $router->get('/', [TaskController::class, 'index'])->middleware('role:user');
    $router->post('/create', [TaskController::class, 'store'])->middleware('role:admin');
    $router->post('/{task_id}/update', [TaskController::class, 'update'])->middleware('role:user');
    $router->post('/{task_id}/delete', [TaskController::class, 'destroy'])->middleware('role:admin');
});
