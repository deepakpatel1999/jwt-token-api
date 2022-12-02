<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\user\RoleController;
use App\Http\Controllers\user\PermissionController;
use App\Http\Controllers\user\SubjectController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::get('/get-user', [AuthController::class, 'get_user']);
});

Route::group(['prefix' => 'v1', 'middleware' => ['role:Admin']], function () {
    Route::post('/create-role', [RoleController::class, 'create_role']);
    Route::get('/read-role', [RoleController::class, 'read_role']);
    Route::get('/edit-role/{id}', [RoleController::class, 'edit_role']);
    Route::post('/update-role', [RoleController::class, 'update_role']);
    Route::get('/delete-role/{id}', [RoleController::class, 'delete_role']);
});

Route::group(['prefix' => 'v1', 'middleware' => ['role:Admin']], function () {
    Route::post('/create-permission', [PermissionController::class, 'create_permission']);
    Route::get('/read-permission', [PermissionController::class, 'read_permission']);
    Route::get('/edit-permission/{id}', [PermissionController::class, 'edit_permission']);
    Route::post('/update-permission', [PermissionController::class, 'update_permission']);
    Route::get('/delete-permission/{id}', [PermissionController::class, 'delete_permission']);
});

Route::group(['prefix' => 'v1', 'middleware' => ['role:Admin|Teacher']], function () {
    Route::post('/create-subject', [SubjectController::class, 'create_subject']);
    Route::get('/edit-subject/{id}', [SubjectController::class, 'edit_subject']);
    Route::post('/update-subject', [SubjectController::class, 'update_subject']);
    Route::get('/delete-subject/{id}', [SubjectController::class, 'delete_subject']);
});

Route::group(['prefix' => 'v1', 'middleware' => ['role:Admin|Teacher|Student']], function () {
    Route::get('/read-subject', [SubjectController::class, 'read_subject']);
});
