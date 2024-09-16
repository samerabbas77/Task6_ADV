<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\ProjectController;

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
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group([
    'middleware' => 'auth:api',
  ], function ($router) {
         //Authentecation
         Route::post('/logout', [AuthController::class, 'logout']);
         Route::post('/refresh', [AuthController::class, 'refresh']);
         Route::post('/me', [AuthController::class, 'info']);

 //User.....................................................................................
        Route::middleware('isAdmin')->group(function () {

              Route::apiResource('/user', UserController::class)->only(['index','store','update']);
              //restore
              Route::post('/user/restore/{id}',[UserController::class,'restore']);
    
          }); 
          //Admin and user
          Route::apiResource('/user', UserController::class)->only(['show','destroy']);
    
          //user update
          Route::put('/user/update/{user}',[UserController::class,'updateForUser']);

  //Project.......................................................................................
         Route::apiResource('project', ProjectController::class)->middleware('isAdmin');
         Route::post('project/assign/{project}',[ProjectController::class,'assign_user'])->middleware('isAdmin');
  //Task............................................................................................
          Route::apiResource('task', TaskController::class);

          Route::get('task/last/{project}',[TaskController::class,'getLatestTask'])->middleware('isAdmin');
          Route::get('task/old/{project}',[TaskController::class,'getOldestTask'])->middleware('isAdmin');

          Route::post('task/comment/{task}',[TaskController::class,'addComment']);

  });